import pandas as pd
import json
from ollama import Client
import rapidfuzz

client = Client("http://localhost:5881")
MODEL = "gemma3:27b"

# Load csv
data = pd.read_csv("assets/extracted_table.csv")

# One column "Puni naziv" looks like this
# "live animals; products of animal origin >>> live animals >>> live horses, donkeys, mules and mules"
# the ">>>" denotes parent-child relationship
def load_tree():
    tree = {"children": {}, "name_eng": "ROOT"}  # Initialize the tree with a root
    for index, row in data.iterrows():
        full_name = row["Puni Naziv"]
        path = list(map(lambda str: str.strip(), full_name.split(">>>")))
        current_node = tree
        # Create the tree structure
        for i, name in enumerate(path):
            if name not in current_node["children"]:
                try:
                    name_eng = row["Puni Naziv - ENG"].split(">>>")[i].strip() 
                    if row["Tarifna oznaka"] and str(row["Tarifna oznaka"]) != "nan":
                        name_eng += " (HS CODE " + str(row["Tarifna oznaka"]) + ")"
                    current_node["children"][name] = {"row": row, "children": {}, "name_eng": name_eng}
                except IndexError:
                    print("Index error at index", i, "for", row.to_dict())
                    print()
                    print(path)
                    print()
                    print(row["Puni Naziv - ENG"].split(">>>"))
                    print()
                    print(row["Tarifna oznaka"])
                    exit(1)
            
            current_node = current_node["children"][name]
    return tree

tree = load_tree()

system_prompt = """You are an expert in harmonized codes for import/export of goods.
You will be given a product name and you need to respond with a list of possible subcategories that item belongs to.
You return json array of strings. You return empty array if none of the categories match.
If you think that item might belong in a subcategory of that subcategory, you should also include it."""

def get_relevant_categories(query: str, tree: dict, parent_categories: list[str]):
    eng_names = list(map(lambda value: value["name_eng"], tree["children"].values()))
    if len(eng_names) == 1:
        return eng_names
    print(" >>> ".join(parent_categories))
    print("", *eng_names, sep="\n- ")
    user_prompt = "You are now in category: " + " >>> ".join(parent_categories) + "\n\n" \
        + "Possible subcategories are: " + json.dumps(eng_names) + "\n" \
        + "Subcategory names that you choose must match EXACTLY like in the list above. Do not hallucinate.\n\n" \
        + "You are seraching subcategory for item with name: " + query
    messages = [
        {"role": "system", "content": system_prompt},
        {"role": "user", "content": user_prompt},
    ]
    ret = client.chat(MODEL, messages, format={
        "type": "array",
        "items": {
            "type": "string",
        }
    }, options={"temperature": 0.1})
    ret = json.loads(ret.message.content)
    print("Selected categories:", *ret, sep="\n>> ")
    print()
    return ret

class CategoryNotFoundError(Exception):
    pass

def find_category_by_english_name(tree: dict, eng_name: str):
    # Check exact match
    for key, value in tree["children"].items():
        if value["name_eng"] == eng_name:
            return key, value
    # Check if name is similar
    for key, value in tree["children"].items():
        if rapidfuzz.fuzz.ratio(eng_name, value["name_eng"]) > 90:
            print(f"WARNING: Found similar name '{value['name_eng']}' for '{eng_name}'")
            return key, value
    raise CategoryNotFoundError(f"Category with name '{eng_name}' not found")

# Recursively search the tree for relevant categories
def perform_search(query: str, tree: dict, parent_categories: list[str] = ["ROOT"]):
    ret = []
    relevant_categories = get_relevant_categories(query, tree, parent_categories)
    for category in relevant_categories:
        try:
            key, value = find_category_by_english_name(tree, category)
            if len(value["children"]) == 0:
                ret.append(value["row"]["Tarifna oznaka"])
            else:
                ret.extend(perform_search(query, value, parent_categories + [value["name_eng"]]))
        except CategoryNotFoundError:
            pass
    return ret

print(perform_search("fuel filter", tree))