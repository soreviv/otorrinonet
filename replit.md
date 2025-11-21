# OtorrinoNet - Replit Setup

## Overview
OtorrinoNet is a Laravel 11 medical appointment booking application for an otorhinolaryngology practice. It allows patients to schedule appointments and provides an admin panel to manage appointments and contact messages.

**Project Status:** Configured and ready to run on Replit  
**Last Updated:** November 20, 2025

## Recent Changes
- November 20, 2025: Initial Replit setup completed
  - Installed PHP and Node.js dependencies
  - Created EventServiceProvider for Laravel compatibility
  - Fixed package.json duplicate entries
  - Configured Vite to run on port 5173 with HMR support for Replit
  - Set up workflow to run Laravel frontend (port 5000) and Vite dev server (port 5173)
  - Created comprehensive .gitignore for Laravel and Node.js
  - Generated Laravel application key
  - Updated Blade layouts to use @vite directive for proper asset loading

## Project Architecture

### Tech Stack
- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vite + Tailwind CSS + Blade templates
- **Database:** PostgreSQL (Neon-hosted)

### Port Configuration
- **Frontend (Laravel):** Port 5000 on 0.0.0.0 (public-facing)
- **Vite Dev Server:** Port 5173 on 0.0.0.0 (for asset serving and HMR)

### Key Files
- `vite.config.js` - Vite configuration for Replit environment
- `package.json` - Node.js dependencies
- `composer.json` - PHP dependencies
- `.env` - Environment configuration (database, app settings)

## Database Setup
**Status:** Pending user action

To complete the database setup:
1. Click on the "Replit Database" tool in your workspace
2. Select "Create a database" and choose PostgreSQL
3. Replit will automatically save connection credentials as environment variables
4. Run migrations: `php artisan migrate`

The application expects these environment variables:
- `DATABASE_URL` or individual DB_* variables
- `PGHOST`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`

## Running the Application
The workflow "Start application" runs both servers:
- Laravel backend server (artisan serve)
- Vite development server (npm run dev)

The application is accessible through the Replit webview on port 5000.

## Development Notes
- The project has both a custom PHP framework structure (app/controllers, app/core) and Laravel components
- PSR-4 autoloading warnings for custom framework files are expected and can be ignored
- Vite HMR (Hot Module Replacement) is configured to work with Replit's domain

## Deployment
Deployment configuration pending final testing with database.
