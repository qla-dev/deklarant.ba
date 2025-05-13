import json
import pandas as pd
from sentence_transformers import SentenceTransformer
import faiss
import os
import pickle
import torch

MODEL_NAMES = [
    "all-mpnet-base-v2",
    # "sentence-t5-xxl",
    # "openai/clip-vit-large-patch14",
    # "gtr-t5-large",
    # "text-embedding-ada-002"
]

MODELS = {}
# Always initialize models on CPU
for model_name in MODEL_NAMES:
    MODELS[model_name] = SentenceTransformer(model_name, device="cpu")

def preprocess_csv(file_path):
    df = pd.read_csv(file_path)
    df = df[df['Tarifna oznaka'].apply(lambda x: str(x).replace(' ', '').isdigit() and len(str(x).replace(' ', '')) == 10)]
    return df

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

            # Move model temporarily to GPU if available
            device = "cuda" if torch.cuda.is_available() else "cpu"
            model = model.to(device)

            embeddings = model.encode(data[column_name].tolist(), show_progress_bar=True, device=device)

            # After encoding, move back to CPU
            model = model.to("cpu")
            MODELS[model_name] = model

            all_embeddings[model_name] = embeddings
            with open(cache_path, "wb") as f:
                pickle.dump(embeddings, f)

    return all_embeddings

def build_faiss_index(embeddings):
    indices = {}
    for model_name, embedding_array in embeddings.items():
        dim = embedding_array.shape[1]
        index = faiss.IndexFlatL2(dim)
        index.add(embedding_array)
        indices[model_name] = index
    return indices

def search_faiss_index(query, indices, data):
    results = []
    query_embeddings = {}

    for model_name in MODEL_NAMES:
        model = MODELS[model_name]
        device = "cuda" if torch.cuda.is_available() else "cpu"
        model = model.to(device)

        query_embeddings[model_name] = model.encode([query], device=device)

        model = model.to("cpu")
        MODELS[model_name] = model

    for model_name, index in indices.items():
        distances, indices_array = index.search(query_embeddings[model_name], len(data))
        results.append((distances[0], indices_array[0]))

    aggregated_results = {}
    for model_distances, model_indices in results:
        for idx, dist in zip(model_indices, model_distances):
            if idx in aggregated_results:
                aggregated_results[idx] += dist
            else:
                aggregated_results[idx] = dist

    sorted_results = sorted(aggregated_results.items(), key=lambda x: x[1])
    return sorted_results

file_path = "assets/extracted_table.csv"
data = preprocess_csv(file_path)
all_embeddings = generate_embeddings(data, column_name="Puni Naziv - ENG")
indices = build_faiss_index(all_embeddings)

def perform_search(query: str):
    results = search_faiss_index(query.lower(), indices, data)
    ret = [{
        "entry": json.loads(data.iloc[int(idx)].to_json()),
        "closeness": float(sum_of_distances)
    } for (idx, sum_of_distances) in results]
    return ret

if __name__ == "__main__":
    for result in reversed(perform_search("chocolate")[:10]):
        print(result)
