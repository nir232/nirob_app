# hotel_Book - Render Ready + Local PC Test

This package keeps the existing hotel booking application and adds the configuration
needed to deploy it to Render with PHP 8.2 + Apache + MySQL 8.

## Local PC test (XAMPP)

1. Put this folder in `C:\xampp\htdocs\hotel_Book`.
2. Start Apache and MySQL.
3. Create a database named `hotel_booking` in phpMyAdmin.
4. Import `database.sql`.
5. Open `http://localhost/hotel_Book/`.

Local manager:
- Username: `manager1`
- Password: `manager123`

## Render deployment

1. Put the CONTENTS of this folder directly in the root of a GitHub repository.
2. In Render choose **New -> Blueprint** and select that repository.
3. Render reads `render.yaml` and creates:
   - `grandstay-mysql`: private MySQL 8 service with a persistent disk
   - `grandstay-hotel-booking`: PHP/Apache web service
4. When Render asks for `MANAGER_PASSWORD`, `MANAGER_EMAIL`, and `MANAGER_PHONE`,
   enter the values you want. If `MANAGER_PASSWORD` is left unset, the initializer
   falls back to `manager123`.
5. The web service waits for MySQL, initializes the tables/seed rooms, and creates
   a manager account if no manager exists.
6. After deployment, open the web service URL and log in with the configured manager
   credentials.

IMPORTANT:
- The MySQL service uses a persistent disk at `/var/lib/mysql`.
- The MySQL service is a paid/private service on Render because persistent disks
  require a paid service.
- Do not expose the MySQL service publicly.
