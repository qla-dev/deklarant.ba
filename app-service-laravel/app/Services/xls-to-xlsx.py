import pandas as pd
import sys

xls_file = sys.argv[1]
xlsx_file = sys.argv[2]

df = pd.read_excel(xls_file)
df.to_excel(xlsx_file, index=False, header=False)
