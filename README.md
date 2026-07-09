# Nefolio

Nefolio SMM Panel local setup and deployment system.

## Setup Instructions

1. Copy the sample environment settings to `.env`:
   ```bash
   cp .env.example .env
   ```
2. Adjust the values in `.env` to match your local database and credentials.
3. Run the local development server:
   ```bash
   ./start.sh
   ```
   Or:
   ```bash
   php -S 0.0.0.0:5400 router.php
   ```

## CI/CD Deployment

This repository uses GitHub Actions to automatically deploy updates to the production site via FTP when pushed to the `main` branch.

### Prerequisites

You must set up the following repository secret in your GitHub repository settings:
* `FTP_PASSWORD`: The FTP user password (`iA72MnOVKFeO`).
