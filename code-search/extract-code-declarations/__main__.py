import fitz  # PyMuPDF
import random
import pandas as pd
import os
try:
    from progressbar import progressbar
except ImportError:
    def progressbar(r):
        return r

def generate_random_color():
    """
    Generates a random color in RGB format.

    Returns:
    tuple: A tuple representing an RGB color (r, g, b).
    """
    return (random.random(), random.random(), random.random())

def remove_close_values(values: list):
    duplicates = []
    for i, val in enumerate(values):
        if values[i-1] == val - 1:
            duplicates.append(val)
    for d in duplicates:
        values.remove(d)

# # Initialize a global figure and axis for cumulative plotting
# fig, ax = plt.subplots(figsize=(8, 8))
# def add_rect_to_plot(rect):
#     """
#     Adds a Rect object to a Matplotlib plot without displaying it immediately.

#     Args:
#     rect (fitz.Rect): The rectangle object to add to the plot.
    
#     Returns:
#     None
#     """
#     # Extract coordinates and dimensions from the Rect
#     x0, y0, x1, y1 = rect.x0, rect.y0, rect.x1, rect.y1
#     width = x1 - x0
#     height = y1 - y0

#     # Generate a random edge color
#     edge_color = generate_random_color()
    
#     # Create a rectangle and add it to the plot
#     rectangle = Rectangle((x0, y0), width, height, edgecolor=edge_color, facecolor='none', lw=2)
#     ax.add_patch(rectangle)
    
#     # Adjust plot limits dynamically
#     ax.set_xlim(min(ax.get_xlim()[0], x0 - 10), max(ax.get_xlim()[1], x1 + 10))
#     ax.set_ylim(min(ax.get_ylim()[0], y0 - 10), max(ax.get_ylim()[1], y1 + 10))
#     ax.set_aspect('equal', adjustable='box')

# def show_plot():
#     """
#     Displays the cumulative plot with all added rectangles.
#     """
#     ax.set_xlabel("X")
#     ax.set_ylabel("Y")
#     ax.set_title("Cumulative Rectangle Plot")
#     plt.show()

def detect_table_lines_from_pdf(page: fitz.Page):
    """
    Detect and print the x-coordinates of vertical lines on a PDF page.
    
    Args:
    pdf_path (str): Path to the PDF file.
    page_number (int): Page number to analyze (0-indexed).
    """
    
    # Get all drawings (includes lines) on the page
    drawings = page.get_drawings()
    
    # Extract vertical lines
    vertical_lines = []
    horizontal_lines = []
    for item in drawings:
        if 'rect' in item:
            rect = item['rect']
            # print(rect)
            x0, x1 = rect.x0, rect.x1
            y0, y1 = rect.y0, rect.y1
            # add_rect_to_plot(rect)

            vertical_lines.append(round(x0))
            vertical_lines.append(round(x1))
            horizontal_lines.append(round(y0))
            horizontal_lines.append(round(y1))
    
    # Remove duplicates and sort the x-coordinates
    vertical_lines = sorted(set(vertical_lines))
    horizontal_lines = sorted(set(horizontal_lines))

    remove_close_values(vertical_lines)
    remove_close_values(horizontal_lines)
    # show_plot()

    return vertical_lines, horizontal_lines

def map_text_to_dataframe(page: fitz.Page, x_coords: list[int], y_coords: list[int]):
    """
    Maps text on a PDF page to a DataFrame based on the grid defined by x and y coordinates.

    Args:
    page (fitz.Page): The PDF page to process.
    x_coords (list): Sorted list of x-coordinates of vertical lines.
    y_coords (list): Sorted list of y-coordinates of horizontal lines.

    Returns:
    pd.DataFrame: A DataFrame with the text mapped to corresponding grid cells.
    """
    # Create an empty DataFrame with dimensions based on the number of cells
    n_rows = len(y_coords) - 1
    n_cols = len(x_coords) - 1
    df = pd.DataFrame([["" for _ in range(n_cols)] for _ in range(n_rows)])
    
    # Iterate through all the text on the page
    for text_block in page.get_text("dict")["blocks"]:
        for line in text_block.get("lines", []):
            for span in line.get("spans", []):
                text = span.get("text", "").strip()
                if not text:
                    continue
                
                # Get the bounding box of the text
                bbox = span.get("bbox", [])
                if len(bbox) != 4:
                    continue
                x, y, x_end, y_end = bbox
                x = (x + x_end) / 2
                y = (y + y_end) / 2
                
                # Determine the row and column based on coordinates
                row = next((i for i in range(n_rows) if y_coords[i] <= y < y_coords[i + 1]), None)
                col = next((j for j in range(n_cols) if x_coords[j] <= x < x_coords[j + 1]), None)
                
                # If the text falls within a valid cell, add it to the DataFrame
                if row is not None and col is not None:
                    if df.iloc[row, col]:  # If cell already contains text, append
                        df.iloc[row, col] += " " + text
                    else:
                        df.iloc[row, col] = text
    
    return df

def remove_and_test_first_rows(df: pd.DataFrame):
    """
    Removes the first 4 rows of the DataFrame, extracts all non-empty cells,
    sorts them alphabetically, and asserts if they match the expected values.

    Args:
    df (pd.DataFrame): The input DataFrame.
    expected_values (list): The expected sorted list of non-empty values from the first 4 rows.

    Returns:
    pd.DataFrame: The DataFrame with the first 4 rows removed.
    """
    # Extract the first 4 rows
    first_four_rows = df.iloc[:4]

    # Flatten all non-empty cells from the first 4 rows
    non_empty_cells = first_four_rows.values.flatten()
    non_empty_cells = [str(cell).strip() for cell in non_empty_cells if pd.notna(cell) and str(cell).strip()]

    # Sort the non-empty cells alphabetically
    sorted_cells = sorted(non_empty_cells)

    expected_values = ['1', '10', '11', '2', '3', '4', '5', '6', '7', '8', '9', 'CEFTA',
                       'CHE, LIE', 'Carinska stopa (%)', 'Carinska stopa (%) za robe porijeklom iz zemalja',
                       'Dopunska jedinica', 'EFTA', 'EU', 'IRN', 'ISL', 'NOR', 'Naziv', 'TUR', 'Tarifna oznaka']

    # Assert if the sorted cells match the expected values
    assert sorted_cells == sorted(expected_values), f"Sorted cells {sorted_cells} do not match expected {sorted(expected_values)}"

    # Return the DataFrame with the first 4 rows removed
    return df.iloc[4:].reset_index(drop=True)

def rename_columns(df: pd.DataFrame):
    columns = [
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
    df.columns = columns
    return df

def fix_tarifna_oznaka_and_naziv(df: pd.DataFrame):
    """
    Fixes the "Tarifna oznaka" and "Naziv" columns in a DataFrame.
    
    For each row, if "Tarifna oznaka" is empty, it checks "Naziv". If "Naziv" starts
    with at least 4 numbers, it splits the numerical part into "Tarifna oznaka" and
    the remaining text into "Naziv". Both columns are stripped of leading and trailing whitespace.

    Args:
    df (pd.DataFrame): The DataFrame containing "Tarifna oznaka" and "Naziv" columns.

    Returns:
    pd.DataFrame: The fixed DataFrame.
    """
    for index, row in df.iterrows():
        if not row["Tarifna oznaka"]:  # Check if "Tarifna oznaka" is empty
            naziv = row["Naziv"]
            # Check if "Naziv" starts with at least 4 numbers
            if naziv[:4].isdigit():
                # Find the first non-numeric and non-space character
                split_index = next((i for i, char in enumerate(naziv) if not char.isdigit() and char != " "), len(naziv))
                df.at[index, "Tarifna oznaka"] = naziv[:split_index].strip()  # Numerical part
                df.at[index, "Naziv"] = naziv[split_index:].strip()  # Remaining text
        elif not row["Naziv"]:  # Check if "Naziv" is empty
            tarifna_oznaka: str = row["Tarifna oznaka"]
            # Check if "Tarifna oznaka" doesn't have only numbers
            if not tarifna_oznaka.replace(" ", "").isdigit():
                # Find the first non-numeric and non-space character
                split_index = next((i for i, char in enumerate(tarifna_oznaka) if not char.isdigit() and char != " "), len(tarifna_oznaka))
                df.at[index, "Tarifna oznaka"] = tarifna_oznaka[:split_index].strip()  # Numerical part
                df.at[index, "Naziv"] = tarifna_oznaka[split_index:].strip()  # Remaining text
    return df

def roman_to_int(roman: str) -> int:
    roman_to_value = {
        'I': 1,
        'V': 5,
        'X': 10,
        'L': 50,
        'C': 100,
        'D': 500,
        'M': 1000
    }
    total = 0
    prev_value = 0

    for char in reversed(roman):
        current_value = roman_to_value[char]
        if current_value < prev_value:
            total -= current_value
        else:
            total += current_value
        prev_value = current_value

    return total

last_known_head = None
last_known_section = None
total_heads = 0
total_sections = 0
non_existing_heads = [77, 98]
def extract_head_and_section(page: fitz.Page):
    # Iterate through all the text on the page
    global last_known_head
    global last_known_section
    global total_heads
    global total_sections
    next_text_is_section = False
    next_text_is_head = False

    for text in page.get_text().split("\n"):
        text: str = text.strip()
        if next_text_is_section:
            if '\n' in text:
                continue
            # print("SECTION")
            # print(text)
            last_known_section = text
            total_sections += 1
            next_text_is_section = False
        if next_text_is_head:
            if '\n' in text:
                continue
            # print("HEAD")
            # print(text)
            last_known_head = text
            total_heads += 1
            next_text_is_head = False
        if text.startswith('ODJELJAK '):
            non_empty = list(filter(bool, text.split(' ')))
            num = non_empty[1].strip()
            if roman_to_int(num) != total_sections + 1:
                raise RuntimeError(f"Expected section {total_sections + 1} but found {text} instead")
            # Sections reset at 2 and then sections start
            if roman_to_int(num) == 2 and total_heads == 0:
                total_sections = -1
            next_text_is_section = True
        if text.startswith('GLAVA '):
            non_empty = list(filter(bool, text.split(' ')))
            num = int(non_empty[1].strip())
            while total_heads + 1 in non_existing_heads:
                total_heads += 1
            if num != total_heads + 1:
                raise RuntimeError(f"Expected GLAVA {total_heads + 1} but found {text} instead")
            next_text_is_head = True

def do_page(pdf: fitz.Document, page_number: int):
    page = pdf[page_number]
    extract_head_and_section(page)
    vertical_lines, horizontal_lines = detect_table_lines_from_pdf(page)
    df = map_text_to_dataframe(page, vertical_lines, horizontal_lines)
    df = remove_and_test_first_rows(df)
    df = rename_columns(df)
    df = fix_tarifna_oznaka_and_naziv(df)
    df["Odjeljak"] = [last_known_section] * len(df)
    df["Glava"] = [last_known_head] * len(df)
    os.makedirs('debug_assets/extracted-table-declarations-csv', exist_ok=True)
    df.to_csv(f'debug_assets/extracted-table-declarations-csv/table_{page_number}.csv', index=False)
    return df

# Helper function to clean up names and remove references like "(1)"
def clean_name(name):
    if isinstance(name, str):
        name = name.split("(")[0].strip()
        if name.endswith(':'):
            name = name[:-1].strip()
    return name

# Process the hierarchy based on the number of "–" characters
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

        clean = clean_name(name)
        level = clean.count("–")  # Determine hierarchy level based on dashes

        # Remove leading "–" and whitespace
        clean = clean.replace("–", "").strip()

        # Update the hierarchy to match the current level
        if level < len(hierarchy):
            hierarchy = hierarchy[:level]
            parent_tariff_stack = parent_tariff_stack[:level]
        hierarchy.append(clean)
        parent_code = parent_tariff_stack[-1] if parent_tariff_stack else ""
        parent_tariff_stack.append(code if code else parent_code)

        previous_codes.append(parent_code)
        result.append(" >>> ".join([section, head] + hierarchy))

    df["Prethodna tarifna oznaka"] = previous_codes
    df["Puni Naziv"] = result
    df["Naziv"] = df["Puni Naziv"].apply(lambda x: x.split(" >>> ")[-1] if isinstance(x, str) else x)

if __name__ == '__main__':
    # Specify the path to your PDF
    pdf_path = "assets/carinska_tarifa.pdf"

    # Open the PDF
    pdf = fitz.open(pdf_path)
    dfs = []

    for i in progressbar(range(len(pdf))):
        try:
            dfs.append(do_page(pdf, i))
        except AssertionError:
            pass

    single_table = pd.concat(dfs, ignore_index=True)

    process_hierarchy(single_table)

    single_table.to_csv("assets/extracted_table.csv", index=False)
