# SMECJobs Application

SMECJobs is a comprehensive job portal and career platform connecting Jobseekers with Employers, while providing a powerful Admin portal for platform management.

## Tech Stack

- **Frontend**: [Nuxt 3](https://nuxt.com/) (Vue.js, TypeScript, Tailwind CSS)
- **Backend**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Database**: MySQL / MariaDB

---

## 🚀 Hosting & Deployment Guide

This guide covers how to deploy the SMECJobs platform to a production environment (e.g., Ubuntu VPS with Nginx, or services like AWS/DigitalOcean).

### 1. Server Requirements

- PHP 8.2 or higher
- Node.js 18.x or higher
- Composer
- MySQL 8.0 or MariaDB
- Nginx or Apache
- PM2 (for running the Nuxt Node server)

### 2. Backend Deployment (Laravel)

1. **Clone the repository** to your server:
   ```bash
   git clone <your-repo-url>
   cd smecjobs/backend
   ```

2. **Install Dependencies**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Edit the `.env` file to configure your production Database, Mail server (SMTP), and App URL.*

4. **Database Migration**:
   ```bash
   php artisan migrate --force
   ```
   *(Optional) If you need initial data, run `php artisan db:seed --force`*

5. **Optimize Framework**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

6. **Storage Permissions**:
   Ensure the web server has write permissions to the storage directory:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

### 3. Frontend Deployment (Nuxt 3)

1. **Navigate to Frontend**:
   ```bash
   cd ../frontend
   ```

2. **Install Dependencies**:
   ```bash
   npm install
   ```

3. **Configure Environment**:
   Ensure your Nuxt app points to your production Laravel API. You may need to create a `.env` in the frontend directory:
   ```env
   NUXT_PUBLIC_API_BASE=https://api.yourdomain.com/api
   ```

4. **Build the Application**:
   ```bash
   npm run build
   ```

5. **Start the Application with PM2**:
   Install PM2 globally if you haven't:
   ```bash
   npm install -g pm2
   ```
   Start the Nuxt server:
   ```bash
   pm2 start .output/server/index.mjs --name "smecjobs-frontend"
   pm2 save
   pm2 startup
   ```

### 4. Nginx Configuration (for Node.js SSR Setup)

You will need to configure Nginx to serve both the Laravel API and proxy requests to the Nuxt frontend.

**Example config for API (api.yourdomain.com)**:
```nginx
server {
    listen 80;
    server_name api.yourdomain.com;
    root /path/to/smecjobs/backend/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
}
```

**Example config for Frontend (yourdomain.com)**:
```nginx
server {
    listen 80;
    server_name yourdomain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

---

### Alternative: Static SPA Deployment (No Node.js Server Needed)

If you do not want to run a Node.js server (PM2) and prefer to host the frontend entirely as static files alongside Laravel, you can generate a Static Single Page Application (SPA).

1. **Configure Nuxt for SPA**: In your `frontend/nuxt.config.ts`, ensure SSR is disabled if you want a pure SPA:
   ```ts
   export default defineNuxtConfig({
     ssr: false
   })
   ```
2. **Generate the Static Output**: Run the generate command in your frontend directory.
   ```bash
   npm run generate
   ```
   *This creates an `.output/public` directory containing your fully compiled static HTML, CSS, and JS.*

3. **Serve via Laravel (or Nginx)**: 
   - **Option A (Separate Domain/Subdomain)**: Simply point a standard Nginx static block or a service like AWS S3 / Netlify to the generated `.output/public` folder.
   - **Option B (Combined with Laravel)**: You can copy the contents of `.output/public` directly into Laravel's `backend/public` directory. Nginx will then serve your Nuxt application at the root domain, while still allowing `/api` requests to fall through to Laravel's index.php. 
   *(Note: For Option B, you may need to configure Nuxt Router or Laravel fallback routes so deep-linking works correctly).*

*Don't forget to secure your domains with SSL using Let's Encrypt (Certbot).*
