import pandas as pd
import os
import time

if __name__ == '__main__':
    file_path = "assets/extracted_table.csv"
    data = pd.read_csv(file_path)
    
    # Write Bosnian names to xlsx (single column, no index/header)
    input_path = "debug_assets/input_for_translation.xlsx"
    bosnian_names = pd.DataFrame(data["Puni Naziv"])
    bosnian_names.to_excel(input_path, index=False, header=False)
    
    # Wait for translated xlsx file
    output_path = "debug_assets/output_translated.xlsx"
    while not os.path.exists(output_path):
        time.sleep(1)
    
    # Read translated xlsx
    translated = pd.read_excel(output_path, header=None)
    
    # Verify and update data
    assert len(translated) == len(data), f"Row count mismatch: input {len(data)} vs output {len(translated)}"
    data["Puni Naziv - ENG"] = translated[0].str.strip().str.lower()
    data.to_csv(file_path, index=False)
    
    # Delete files
    os.unlink(output_path)
    os.unlink(input_path)