from progressbar import progressbar
import pandas as pd
import fitz  # PyMuPDF
import re

column_names = [
    "EX",
    "Tarifna oznaka",
    "Naziv",
    "Dopunska jedinica",
    "Carinska stopa (%)",
    "EU",
    "CEFTA",
    "IRN",
    "TUR",
    "CHE, LIE",
    "ISL",
    "NOR"
]

head_infos = []
section_infos = []

def clean_name(name):
    if isinstance(name, str):
        name = name.split("(")[0].strip()
        if name.endswith(':'):
            name = name[:-1].strip()
    return name

def get_head_and_sections(page_number: int):
    previous_head = {"title": None}
    for head in head_infos:
        if head:
            if page_number < head["page"]:
                break
            previous_head = head
    previous_section = {"title": None}
    for section in section_infos:
        if section:
            if page_number < section["page"]:
                break
            previous_section = section
    return previous_head["title"], previous_section["title"]

def roman_to_int(roman: str) -> int:
    roman_to_value = {'I': 1, 'V': 5, 'X': 10, 'L': 50, 'C': 100, 'D': 500, 'M': 1000}
    total = 0
    prev_value = 0
    for char in reversed(roman):
        current_value = roman_to_value[char]
        total += current_value if current_value >= prev_value else -current_value
        prev_value = current_value
    return total

def load_heading_and_sections():
    pdf_path = "assets/carinska_tarifa.pdf"
    pdf = fitz.open(pdf_path)
    titles = []

    last_known_head = None
    last_known_section = None
    non_existing_heads = [77, 98]

    for page_id in progressbar(range(len(pdf))):
        page = pdf[page_id]
        next_text_is_section = False
        next_text_is_head = False

        for text in page.get_text().split("\n"):
            text = text.strip()
            if next_text_is_section:
                if text:
                    last_known_section = text
                    section_infos.append({"page": page_id, "title": last_known_section})
                next_text_is_section = False
            elif next_text_is_head:
                if text:
                    last_known_head = text
                    head_infos.append({"page": page_id, "title": last_known_head})
                next_text_is_head = False

            if text.startswith('ODJELJAK '):
                parts = list(filter(bool, text.split(' ')))
                if len(parts) >= 2:
                    roman_num = parts[1].strip()
                    if roman_to_int(roman_num) == 1 and len(head_infos) == 0:
                        section_infos.clear()
                    if roman_to_int(roman_num) != len(section_infos) + 1:
                        raise RuntimeError(f"Expected section {len(section_infos) + 1} but found {text}")
                    next_text_is_section = True

            elif text.startswith('GLAVA '):
                parts = list(filter(bool, text.split(' ')))
                if len(parts) >= 2:
                    try:
                        num = int(parts[1].strip())
                        while len(head_infos) + 1 in non_existing_heads:
                            head_infos.append(None)
                        if num != len(head_infos) + 1:
                            raise RuntimeError(f"Expected GLAVA {len(head_infos) + 1} but found {text}")
                        next_text_is_head = True
                    except ValueError:
                        continue

    return titles

# Process the hierarchy based on the number of "-" characters
def process_hierarchy(df: pd.DataFrame):
    hierarchy = []
    result = []
    parent_tariff_stack = []
    previous_codes = []

    for i, row in df.iterrows():
        name = row["Naziv"]
        section = row["Odjeljak"]
        head = row["Glava"]
        code = row["Tarifna oznaka"]
        assert section, f"{name} is missing section"
        assert head, f"{name} is missing head"

        if pd.isna(name):
            previous_codes.append("")
            result.append("")
            continue

        name = clean_name(name)
        level = name.count("-")  # Determine hierarchy level based on dashes

        # Remove leading "-" and whitespace
        name = name.replace("-", "").strip()

        # Update the hierarchy to match the current level
        if level < len(hierarchy):
            hierarchy = hierarchy[:level]
            parent_tariff_stack = parent_tariff_stack[:level]
        hierarchy.append(name)
        parent_code = parent_tariff_stack[-1] if parent_tariff_stack else ""
        parent_tariff_stack.append(code if code else parent_code)

        previous_codes.append(parent_code)
        result.append(" >>> ".join([section, head] + hierarchy))

    df["Prethodna tarifna oznaka"] = previous_codes
    df["Puni Naziv"] = result
    df["Naziv"] = df["Puni Naziv"].apply(lambda x: x.split(" >>> ")[-1] if isinstance(x, str) else x)

if __name__ == '__main__':
    # Specify the path to your MD
    md_path = "assets/carinska_tarifa.md"

    mode = None
    row_idx = 0

    all_objects = []
    load_heading_and_sections()
    page_number = None

    for row in progressbar(list(open(md_path, encoding="utf-8"))):
        row = row.strip()
        match = re.fullmatch(r"\{(\d+)\}\*+", row)
        if match:
            page_number = int(match.group(1))
        elif row_idx == 0:
            raise RuntimeError('Page marker not found. Please add following params to converter: --page_separator="*************************************************" --paginate_output')

        head, section = get_head_and_sections(page_number)
        row_idx += 1
        # Are we entering a table?
        if mode is None:
            if row.replace(" ", "").replace("|", "") == "1234567891011":
                mode = "table"
                all_rows_in_current_table: list[str] = []

        elif mode == "table":
            if row == "":
                has_ex = False
                for column_values in all_rows_in_current_table:
                    if columns_values[0].lower() == "ex":
                        has_ex = True
                for column_values in all_rows_in_current_table:
                    if not has_ex:
                        columns_values = [""] + column_values
                    columns_values = columns_values[:len(column_names)]
                    obj = dict(zip(column_names, columns_values))
                    assert head
                    assert section
                    obj["Odjeljak"] = section
                    obj["Glava"] = head
                    obj["Naziv"] = obj["Naziv"]\
                        .replace("–", "-")\
                        .replace("4<br>", "-<br>")\
                        .replace("-<br>", "-")\
                        .replace("<br>", " ")\
                        .replace(":", "")
                    
                    if obj["CEFTA"] == '0':
                        obj["CEFTA"] = '0.0'
                    if obj["TUR"] == '0':
                        obj["TUR"] = '0.0'
                    all_objects.append(obj)
                mode = None
            else:
                columns_values = list(map(lambda s: s.strip(), row.split("|")))[1:]
                all_rows_in_current_table.append(columns_values)

    df = pd.DataFrame(all_objects)
    process_hierarchy(df)

    df.to_csv("assets/extracted_table.csv", index=False, encoding="utf-8")
    print("Done. Don't forget to translate!!!")