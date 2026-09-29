# GrandStay Hotel Booking Management System

This is the existing GrandStay hotel booking application prepared for Render deployment. The PHP pages, CSS, JavaScript, database structure, booking workflow, customer dashboard, and manager dashboard are preserved.

## Main features

### Customer
- Register and login
- Homepage link from both login and registration pages
- Dashboard
- Book/request a room
- View booking status
- Cancel pending booking
- Profile
- Settings / change password
- Logout

### Hotel Manager
- Dashboard
- View pending booking requests
- Accept or delete booking requests
- View confirmed reservations
- Delete reservations
- Add rooms
- Edit rooms
- Delete rooms
- Profile
- Settings / change password
- Logout

## Render deployment

The repository includes `render.yaml`, which defines:

1. A public PHP/Apache web service.
2. A private MySQL 8 service with a persistent disk at `/var/lib/mysql`.
3. The environment variables needed to connect the PHP application to MySQL.

### Deploy with Render Blueprint

1. Upload/push this project to a GitHub repository.
2. In Render, create a new **Blueprint** and select the repository.
3. Render will read `render.yaml`.
4. During the initial Blueprint setup, provide values for:
   - `MANAGER_PASSWORD`
   - `MANAGER_EMAIL`
   - `MANAGER_PHONE`
5. Deploy the Blueprint.
6. Wait for the MySQL private service and web service to become live.
7. Open the web service URL and use the existing homepage/login/register flow.

The database tables and initial rooms are created automatically on web-service startup. If no manager exists, the application creates one using the manager environment variables. If a manager already exists, it does not overwrite the existing account.

## Manager login

The default manager username remains:

`manager1`

Set your own `MANAGER_PASSWORD` in Render. This is intentionally not stored in the repository.

## Local/XAMPP use

The application can still be used locally with XAMPP. `config.php` continues to fall back to:

- Host: `localhost`
- User: `root`
- Password: empty
- Database: `hotel_booking`

For local setup, create the `hotel_booking` database in MySQL/phpMyAdmin and import `database.sql`, then open `home.php` through Apache.

## Important

The application uses PHP sessions, MySQLi prepared statements, password hashing, and role-based dashboard redirects. The Render-specific additions only provide deployment/database initialization; the existing application pages and functionality are otherwise preserved.
