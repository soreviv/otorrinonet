# OtorrinoNet Backend

This document provides instructions for setting up and running the backend service for the OtorrinoNet web application.

## Description

The backend is a Node.js application using the Express.js framework to manage and serve data for medical appointments. It connects to a PostgreSQL database to store and retrieve information.

## Prerequisites

Before you begin, ensure you have the following installed:
- [Node.js](https://nodejs.org/) (which includes npm)
- [PostgreSQL](https://www.postgresql.org/)

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd <repository-directory>/backend
   ```

2. **Install dependencies:**
   From within the `backend` directory, run the following command to install the required packages:
   ```bash
   npm install
   ```

## Configuration

The backend requires a `.env` file for database credentials.

1. **Create a `.env` file** in the `backend` directory:
   ```bash
   touch .env
   ```

2. **Add the following environment variables** to the `.env` file, replacing the placeholder values with your PostgreSQL database configuration:
   ```env
   DB_USER=your_database_user
   DB_HOST=your_database_host
   DB_NAME=your_database_name
   DB_PASSWORD=your_database_password
   DB_PORT=your_database_port
   ```

## Running the Application

- **Development Mode:**
  To run the server with `nodemon` for automatic restarts on file changes, use:
  ```bash
  npm run dev
  ```

- **Production Mode:**
  To run the server in a production environment, use:
  ```bash
  npm start
  ```

The server will start on the port specified in your application's configuration (default is typically 3000).