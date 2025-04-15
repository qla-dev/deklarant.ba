# Customs Declaration Processing API

This Laravel API processes customs declaration documents (PDF/XLSX) through a multi-step pipeline to extract and enrich item data.

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

## Configuration
Set these environment variables:
- `MARKER_URL=http://localhost:9123`
- `OLLAMA_URL=http://localhost:5000`
- `OLLAMA_MODEL=qwen2.5-coder:32b-instruct-q3_K_L`
- `SEARCH_API_URL=http://localhost:9124/search-api`
- `QUEUE_CONNECTION=redis`

## API Endpoints
- `POST /api/upload` - Submit document for processing
- `GET /api/tasks/{id}` - Check task status
- `GET /api/tasks/{id}/result` - Get processing results

## Admin Interface
Access Horizon dashboard at `/horizon` to monitor queues and jobs.
