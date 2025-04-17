import json
import pandas as pd
from sentence_transformers import SentenceTransformer
import faiss
import os
import pickle

MODEL_NAMES = [
    "all-mpnet-base-v2",
    # "sentence-t5-xxl",
    # "openai/clip-vit-large-patch14",
    # "gtr-t5-large",
    # "text-embedding-ada-002"
]

MODELS = {}
for model_name in MODEL_NAMES:
    MODELS[model_name] = SentenceTransformer(model_name)

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
    all_embeddings = {}

    for model_name in MODEL_NAMES:
        cache_path = os.path.join(cache_dir, f"{model_name}.pkl")

        if os.path.exists(cache_path):
            print(f"Loading embeddings from cache: {cache_path}")
            with open(cache_path, "rb") as f:
                all_embeddings[model_name] = pickle.load(f)
        else:
            print(f"Generating embeddings and caching to: {cache_path}")
            model = MODELS[model_name]
            embeddings = model.encode(data[column_name].tolist(), show_progress_bar=True)
            all_embeddings[model_name] = embeddings
            with open(cache_path, "wb") as f:
                pickle.dump(embeddings, f)

    return all_embeddings

# Build FAISS index for the embeddings
def build_faiss_index(embeddings):
    indices = {}
    for model_name, embedding_array in embeddings.items():
        dim = embedding_array.shape[1]
        index = faiss.IndexFlatL2(dim)
        index.add(embedding_array)
        indices[model_name] = index
    return indices

# Search FAISS index for the closest match across models
def search_faiss_index(query, indices, data):
    results = []
    query_embeddings = {}

    # Generate query embeddings for all models
    for model_name in MODEL_NAMES:
        model = MODELS[model_name]
        query_embeddings[model_name] = model.encode([query])

    # Perform search for each model
    for model_name, index in indices.items():
        distances, indices_array = index.search(query_embeddings[model_name], len(data))
        results.append((distances[0], indices_array[0]))

    # Combine and sort by the sum of distances
    aggregated_results = {}
    for model_distances, model_indices in results:
        for idx, dist in zip(model_indices, model_distances):
            if idx in aggregated_results:
                aggregated_results[idx] += dist
            else:
                aggregated_results[idx] = dist

    # Convert to sorted list of tuples (index, sum_of_distances)
    sorted_results = sorted(aggregated_results.items(), key=lambda x: x[1])
    return sorted_results

# File path to your CSV
file_path = "assets/extracted_table.csv"
# Preprocess the CSV
data = preprocess_csv(file_path)
# Generate embeddings for the "Naziv" column
all_embeddings = generate_embeddings(data, column_name="Puni Naziv - ENG")
# Build FAISS indices
indices = build_faiss_index(all_embeddings)

def perform_search(query: str):
    results = search_faiss_index(query.lower(), indices, data)
    ret = [{
        "entry": json.loads(data.iloc[int(idx)].to_json()),
        "closeness": float(sum_of_distances)
    } for (idx, sum_of_distances) in results]
    return ret

# Main script
if __name__ == "__main__":
    for result in reversed(perform_search("chocolate")[:10]):
        print(result)
