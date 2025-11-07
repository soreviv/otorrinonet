# OtorrinoNet

OtorrinoNet is a web application for scheduling medical appointments for an ENT (ear, nose, and throat) doctor's office. It allows patients to schedule appointments, and provides an admin panel for managing appointments and contact messages.

## Features

- **Appointment Scheduling:** Patients can schedule appointments through a simple form.
- **Admin Panel:** An admin panel for managing appointments and contact messages.
- **Contact Form:** A contact form for patients to send messages to the doctor's office.
- **Dynamic Time Slots:** The application dynamically calculates and displays available time slots for appointments.

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- PostgreSQL
- Composer
- A web server such as Nginx or Apache

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/otorrinonet.com.git
   cd otorrinonet.com
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Set up the database:**
   - Create a PostgreSQL database.
   - Import the database schema from `database_schema.sql`.

4. **Configure the environment:**
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database credentials and hCaptcha secret key.

5. **Configure your web server:**
   - Configure your web server to serve the `public` directory.
   - Ensure that the web server is configured to handle PHP files.

## Usage

- The main application is accessible at the root URL of your server.
- The admin panel is accessible at `/admin`.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
