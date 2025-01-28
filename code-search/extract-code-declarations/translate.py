import pandas as pd
import os
import time

if __name__ == '__main__':
    file_path = "assets/extracted_table.csv"
    data = pd.read_csv(file_path)
    input_path = "debug_assets/input_text_for_translation.txt"
    open(input_path, "w").write("\n".join(data["Naziv"].tolist()))
    output_path = "debug_assets/output_text_for_translation.txt"
    while not os.path.exists(output_path) or len(list(open(output_path))) == 0:
        time.sleep(1)
    time.sleep(1)
    lines = list(open('debug_assets/output_text_for_translation.txt', 'r'))
    os.unlink('debug_assets/output_text_for_translation.txt')
    assert len(lines) == len(data), f"Number of lines in input ({len(data)}) isn't the same as output ({len(lines)})"
    data["Naziv - ENG"] = list(map(lambda line: line.strip().lower(), lines))
    data.to_csv(file_path, index=False)