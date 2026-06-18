# UniPark - Coolify / VPS Deployment Guide

This guide explains how to deploy UniPark on a Coolify-managed VPS, exactly
the same way the NICU project is deployed.

Estimated setup time: **5 minutes** once Coolify + MySQL are ready.

---

## 1. Prerequisites on Coolify

Make sure you already have:

1. **Coolify** installed on your VPS.
2. **A MySQL 8.0 database resource** created inside the same Coolify project.

If you followed the NICU deployment guide, both already exist.

---

## 2. Create a new MySQL database for UniPark (1 minute)

Re-using the same MySQL container is fine — just create a new database + user
inside it so UniPark doesn't share data with NICU.

1. Open the MySQL service on Coolify → **Terminal** tab.
2. Run:

   ```bash
   mysql -u root -p
   ```

   (use the `MYSQL_ROOT_PASSWORD` from Coolify env vars)

3. Inside MySQL:

   ```sql
   CREATE DATABASE unipark CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'unipark'@'%' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
   GRANT ALL PRIVILEGES ON unipark.* TO 'unipark'@'%';
   FLUSH PRIVILEGES;
   EXIT;
   ```

   Replace `STRONG_PASSWORD_HERE` with a 16+ char password.

4. Save these 4 values for the next step:
   - `DB_HOST` = the MySQL container name on Coolify (e.g. `026029c51c66`)
   - `DB_PORT` = `3306`
   - `DB_DATABASE` = `unipark`
   - `DB_USERNAME` = `unipark`
   - `DB_PASSWORD` = the password you just set

---

## 3. Add UniPark as a new resource on Coolify

1. In Coolify → **Project → + New Resource** → **Public Repository (Dockerfile)**
2. Repository: `ZizoAlzeeka/unipark` — branch: `main`
3. Build pack: **Dockerfile** (auto-detected from `Dockerfile`)
4. **Port**: set to `80` (Coolify will route traffic to this port)
5. **Domain**: assign a domain (e.g. `https://unipark.<your-vps-ip>.sslip.io`)

---

## 4. Set environment variables on Coolify

In the UniPark resource → **Environment Variables**, add:

| Variable | Value |
|---|---|
| `APP_NAME` | `UniPark` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `http://unipark.<your-vps-ip>.sslip.io` (set to your real Coolify domain) |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `026029c51c66` ← your MySQL container name |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `unipark` |
| `DB_USERNAME` | `unipark` |
| `DB_PASSWORD` | the strong password you set in step 2 |
| `SESSION_DRIVER` | `database` |
| `CACHE_DRIVER` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `FILESYSTEM_DISK` | `local` |
| `LOG_CHANNEL` | `stderr` |
| `MAIL_MAILER` | `log` |
| `GOOGLE_MAPS_API_KEY` | (your Google Maps key, or leave blank) |
| `UNIVERSITY_EMAIL_DOMAIN` | `uoh.edu.sa` |

---

## 5. Deploy

Click **Deploy**. The build will:

1. Pull `php:8.2-apache`
2. Install PHP extensions (pdo_mysql, gd, intl, zip, …)
3. Run `composer install --no-dev`
4. Copy app into the production image
5. Start the container via `docker-entrypoint.sh`

The entrypoint will:

1. Wait for MySQL to be reachable
2. Create `.env` from `.env.example` and sync env vars
3. Generate `APP_KEY` if missing
4. Run `php artisan migrate --force`
5. Run `php artisan db:seed --force` (only if `users` table is empty)
6. Cache config, routes, views
7. Start Apache on `PORT` (80)

### Successful log signature

```
>> MySQL connection established!
>> Running database migrations...
>> Running database seeders...
>> Optimizing application for production...
>> UniPark is ready! Listening on port 80
```

---

## 6. Common pitfalls (same as NICU)

| Symptom | Cause | Fix |
|---|---|---|
| `PDOException: No such file or directory` | `DB_HOST=localhost` | Use the MySQL container name (e.g. `026029c51c66`) |
| Healthcheck fails on port 3000 | Coolify default port | Set **Port: 80** in Coolify resource settings |
| Redirect loop to `localhost:3000` | `FORCE_HTTPS` / `APP_URL` mismatch | Set `APP_URL` to your Coolify domain |
| 500 after deploy | `APP_KEY` not generated | Entrypoint auto-generates it; check `docker logs` |

---

## 7. Access phpMyAdmin for UniPark

You can re-use the same phpMyAdmin/Adminer container from the NICU setup.
Just log in as `unipark` user (created in step 2) and you'll see only the
`unipark` database.

---

## 8. Default seeded accounts

After the first deploy, the seeder creates test accounts. **Change passwords
immediately in production**:

| Role | Email | Password |
|---|---|---|
| Admin | (check `DatabaseSeeder.php`) | (check `DatabaseSeeder.php`) |
| Student | (check `DatabaseSeeder.php`) | (check `DatabaseSeeder.php`) |

Open `database/seeders/DatabaseSeeder.php` to find the seeded credentials.
