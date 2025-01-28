import pandas as pd
from sentence_transformers import SentenceTransformer
import faiss
import numpy as np
import os
import pickle

MODEL_NAME = "paraphrase-multilingual-MiniLM-L12-v2"

# Load CSV and preprocess data
def preprocess_csv(file_path):
    df = pd.read_csv(file_path)

    # Assuming the column to validate 10 digits is named "Naziv"; adjust if necessary
    # Replace "Naziv" with the actual column name if different
    df = df[df['Tarifna oznaka'].apply(lambda x: str(x).replace(' ', '').isdigit() and len(str(x).replace(' ', '')) == 10)]

    return df

# Generate embeddings for a column using SentenceTransformers
def generate_embeddings(data, column_name):
    cache_dir = "debug_assets"
    os.makedirs(cache_dir, exist_ok=True)
    cache_path = os.path.join(cache_dir, f"{MODEL_NAME}.pkl")

    if os.path.exists(cache_path):
        print(f"Loading embeddings from cache: {cache_path}")
        with open(cache_path, "rb") as f:
            embeddings = pickle.load(f)
    else:
        print(f"Generating embeddings and caching to: {cache_path}")
        model = SentenceTransformer(MODEL_NAME)
        embeddings = model.encode(data[column_name].tolist(), show_progress_bar=True)
        with open(cache_path, "wb") as f:
            pickle.dump(embeddings, f)

    return embeddings

# Build FAISS index for the embeddings
def build_faiss_index(embeddings):
    dim = embeddings.shape[1]
    index = faiss.IndexFlatL2(dim)
    index.add(embeddings)
    return index

# Search FAISS index for the closest match
def search_faiss_index(query, index, data):
    model = SentenceTransformer(MODEL_NAME)
    query_embedding = model.encode([query])
    distances, indices = index.search(query_embedding, len(data))  # Search for all matches
    
    # Return array of tuples (row, similarity)
    results = [(data.iloc[idx], dist) for idx, dist in zip(indices[0], distances[0])]
    return results

# Main script
if __name__ == "__main__":
    # File path to your CSV
    file_path = "assets/extracted_table.csv"

    # Preprocess the CSV
    data = preprocess_csv(file_path)

    # Generate embeddings for the "Naziv" column
    embeddings = generate_embeddings(data, column_name="Naziv")

    # Build FAISS index
    index = build_faiss_index(np.array(embeddings))

    # Query the index
    query = "Vjeverica"  # Replace with your query
    results = search_faiss_index(query, index, data)

    # Output the results
    for row, similarity in reversed(results[:10]):
        print("Row:")
        print(row)
        print("Distance:", similarity)

