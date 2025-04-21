# Customs Declaration Processing API

This Laravel API processes customs declaration documents (PDF/XLSX) through a multi-step pipeline to extract and enrich item data.

## Configuration
You need to install Redis. For Windows install it from [here](https://github.com/tporadowski/redis/releases/download/v5.0.14.1/Redis-x64-5.0.14.1.msi).

**Copy .env.example into .env file**
### Common configuration
You **always** set these variables in `.env` file.
```
QUEUE_CONNECTION=redis

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis

MARKER_URL=http://localhost:9091
OLLAMA_URL=http://localhost:5000
OLLAMA_MODEL=qwen2.5-coder:32b-instruct-q3_K_L
SEARCH_API_URL=http://localhost:9124/search-api

# AI Processing Mode
FAKE_AI_DELAY=3000
```
### Real AI configuration
You need to have all the AI resources installed locally
```
USE_REAL_AI=true
```
### Fake AI configuration
This configuration serves pre-recorder .json files, also it fakes delay for processing.
```
USE_REAL_AI=false
```
### Sync queue mode
If you want to avoid installing redis you can set `QUEUE_CONNECTION=redis` in `.env` file. In that case you don't need to run command 2 from below, everything is done in same server. However, there is no multiprocessing, and your upload requests won't finish until previous things are done, which is not a real situation we will be using.

## Running
To install dependencies run `composer install`.

You need 2 commands to run this server, one is to run server and the other is to run queue worker.
1. In one terminal run `php artisan serve --host 0.0.0.0 --port 8080`
2. In another terminal run `php artisan queue:work`

## Architecture Overview

### Core Components
1. **File Upload Endpoint** - Accepts document uploads and initiates processing
2. **Task Processing Pipeline** - Background jobs that handle each processing step
3. **Status Tracking** - Allows users/admins to monitor task progress
4. **Admin Dashboard** - Horizon-based queue monitoring

### Processing Flow
1. **Upload** - User submits document via API
2. **Conversion** - Document converted to markdown via Marker service
3. **Extraction** - LLM (Ollama) extracts structured item data from markdown
4. **Enrichment** - Search API matches items with known codes
5. **Completion** - Final enriched data stored and available via API

### Technical Stack
- Laravel 12 (API-only)
- Redis for queue management
- Optional: Horizon for queue monitoring (requires pcntl extension)
- External services:
  - Marker (document conversion)
  - Ollama (LLM processing)
  - Custom search API

## API Endpoints
- `POST /api/upload` - Submit document for processing
- `GET /api/tasks/{id}` - Check task status
- `GET /api/tasks/{id}/result` - Get processing results

## Admin Interface
Access Horizon dashboard at `/horizon` to monitor queues and jobs.
