# Absensi App

**Absensi** is a Laravel-based application built for managing attendance with a MySQL database connection. This app offers seamless installation and a straightforward usage process.

## Requirements

Before you begin, ensure you have the following installed on your system:

- PHP >= 7.3
- Composer
- Node.js & npm
- MySQL database or Laragon for easy create database

## Installation

Follow these steps to set up the application:

1. Clone the repository to your www folder inside laragon:
   ```bash
   git clone https://github.com/OmniDigitalTech/absensi-neoma.git
   ```
   - or download the zip file and extract it to your www folder inside laragon.


2. Navigate into the project directory:
   ```bash
   cd absensi-neoma
   ```

3. Install required Node.js dependencies using npm:
   ```bash
   npm install
   ```

4. Upgrade or Install PHP dependencies via Composer:
   ```bash
   composer upgrade
   ```
    or
    ```bash
   composer install
   ```
   
5. Set up the environment configuration:
    - Create your database and name it whatever you want (default is **absen**)
    - Copy the `.env.example` file to `.env`:

      ```bash
      cp .env.example .env
      ```
    - Configure the database and other environment variables in the `.env` file.


6. Generate key for access database migrations:
   ```bash
   php artisan key:generate
   ```

7. Run fresh database migrations and seeds:
   ```bash
   php artisan migrate:fresh --seed
   ```
   
8. Check if public folder exist inside `storage/app` directory. If not please run:
   ```bash
   mkdir storage\app\public
   ```

9. Generate a symbolic link for the `public/storage` directory:
   ```bash
   php artisan storage:link
   ```

10. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

After setup, you can access the application in your browser at your localhost.

## Features

- Attendance management
- MySQL database integration

## License

Include the license for your project, e.g., MIT, Apache 2.0, etc.

---
