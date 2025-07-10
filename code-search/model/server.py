from flask import Flask, request, jsonify, render_template_string
from model.search import perform_search
import os
import signal
import threading
import time

# Define the "search" function
def search(query, good_candidates: list[str]):
    print("Searching for '", query, "'")
    results = perform_search(query, good_candidates)[:10]
    return results

# Create the Flask app
app = Flask(__name__)

@app.route('/search-api')
def handle_request():
    query = request.args.get("query")
    good_candidates = request.args.get("good_candidates", "").split(",")
    if query:
        return jsonify(search(query, good_candidates))
    else:
        return jsonify({"error": "Missing 'query' parameter"}), 400

# HTML template for GET route
HTML_TEMPLATE = """
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>Search</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        strong {
            display: inline-block;
            width: 120px;
        }
        li {
            margin-top: 20px;
        }
        body {
            font-family: "Roboto", serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings: "wdth" 100;
        }
    </style>
</head>
<body>
    <form method="GET">
        <label for="query">Search Query:</label>
        <input type="text" id="query" name="query" placeholder="Item name" required>
        <input type="text" id="good_candidates" name="good_candidates" placeholder="Good candidates">
        <button type="submit">Search</button>
    </form>
    {% if query %}
    <h2>You searched for: {{ query }}</h2>
    {% endif %}
    {% if results %}
    <ul>
        {% for result in results %}
        <li>
            <strong>Naziv:</strong> {{ result['entry']['Naziv'] }}<br>
            <strong>Tarifna oznaka:</strong> {{ result['entry']['Tarifna oznaka'] }}<br>
            <strong>Closeness:</strong> {{ result['closeness'] }}
        </li>
        {% endfor %}
    </ul>
    {% endif %}
</body>
</html>
"""

@app.route('/', methods=['GET'])
def search_page():
    query = request.args.get('query')
    good_candidates = request.args.get("good_candidates", "").split(",")
    results = search(query, good_candidates) if query else None
    return render_template_string(HTML_TEMPLATE, query=query, results=results)

def monitor_restart_file():
    restart_file = ".restart-requested"
    while True:
        if os.path.exists(restart_file):
            print("Restart requested via .restart-requested file")
            os.remove(restart_file)
            os.kill(os.getpid(), signal.SIGINT)
        time.sleep(2)

if __name__ == "__main__":
    # Cleanup restart flag if it exists
    if os.path.exists(".restart-requested"):
        os.remove(".restart-requested")

    # Start monitoring restart file in a background thread
    threading.Thread(target=monitor_restart_file, daemon=True).start()

    # Start Flask app
    app.run(port=9124, host="0.0.0.0")
