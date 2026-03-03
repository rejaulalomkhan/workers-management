import os
from PyPDF2 import PdfReader

try:
    print("--- PRD ---")
    reader = PdfReader("FaizaHost_PRD (1).pdf")
    for page in reader.pages:
        print(page.extract_text())
        
    print("--- OVERVIEW ---")
    reader = PdfReader("Overview.pdf")
    for page in reader.pages:
        print(page.extract_text())
except Exception as e:
    print(f"Error: {e}")
