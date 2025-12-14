# Deployment Guide - FREE Hosting

## 🚀 Recommended FREE Deployment Strategy

Since this is a Laravel full-stack application, here are your **100% FREE** hosting options:

---

## Option 1: Railway.app (RECOMMENDED) ⭐

**Best for: Complete Laravel app with database**

### Why Railway?
- ✅ FREE $5/month credit (enough for small apps)
- ✅ Supports PHP + SQLite/PostgreSQL
- ✅ GitHub integration
- ✅ Automatic deployments
- ✅ Free SSL certificate
- ✅ Custom domain support

### Steps:

1. **Create `Procfile` in project root:**
```
web: php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

2. **Create `railway.json`:**
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

3. **Push to GitHub**

4. **Deploy on Railway:**
   - Go to https://railway.app
   - Sign up with GitHub
   - Click "New Project" → "Deploy from GitHub repo"
   - Select your repository
   - Add environment variables:
     ```
     APP_KEY=base64:YOUR_KEY_HERE
     APP_ENV=production
     APP_DEBUG=false
     DB_CONNECTION=sqlite
     ```
   - Railway will auto-deploy!

5. **Run migrations:**
   - In Railway dashboard, open Shell
   - Run: `php artisan migrate --force`
   - Run: `php artisan db:seed --force`

---

## Option 2: Fly.io

**Free allowance: 3 shared-cpu VMs, 3GB storage**

### Steps:

1. **Install Fly CLI:**
```bash
powershell -Command "iwr https://fly.io/install.ps1 -useb | iex"
```

2. **Login:**
```bash
fly auth login
```

3. **Create `fly.toml`:**
```toml
app = "plastik-packing"

[build]
  builder = "paketobuildpacks/builder:base"

[env]
  APP_ENV = "production"
  DB_CONNECTION = "sqlite"

[[services]]
  http_checks = []
  internal_port = 8000
  processes = ["app"]
  protocol = "tcp"

  [[services.ports]]
    force_https = true
    handlers = ["http"]
    port = 80

  [[services.ports]]
    handlers = ["tls", "http"]
    port = 443
```

4. **Deploy:**
```bash
fly launch
fly deploy
```

---

## Option 3: Render.com

**Free tier with limitations (sleeps after inactivity)**

### Steps:

1. **Create `render.yaml`:**
```yaml
services:
  - type: web
    name: plastik-packing
    env: php
    buildCommand: composer install && php artisan key:generate && php artisan migrate --force
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: DB_CONNECTION
        value: sqlite
```

2. **Deploy:**
   - Push to GitHub
   - Connect to https://render.com
   - Create new Web Service
   - Connect your repo
   - Render will auto-deploy

---

## Option 4: Split Frontend + Backend (Advanced)

### Frontend: Vercel (Free forever)
- Convert Blade views to API responses (JSON)
- Build React/Vue/Next.js frontend
- Deploy frontend to Vercel

### Backend: Railway/Fly.io (Free tier)
- Keep Laravel as API-only
- Use Laravel Sanctum for authentication

**NOT RECOMMENDED** for this project since it requires significant refactoring.

---

## 🎯 BEST CHOICE: Railway.app

**Why?**
1. Easiest setup for Laravel
2. No sleep/cold starts
3. Generous free tier
4. SQLite works perfectly
5. One-click deployment from GitHub

---

## 📝 Pre-Deployment Checklist

Before deploying, update these files:

### 1. `.env.example` (for production):
```env
APP_NAME="Sistem Plastik Packing"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 2. `composer.json` (add platform requirements):
```json
{
  "require": {
    "php": "^8.2",
    ...
  },
  "config": {
    "platform": {
      "php": "8.2"
    }
  }
}
```

### 3. Create `Procfile`:
```
web: php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

### 4. Update `.gitignore` (remove database.sqlite to keep it):
```
# Comment out or remove this line:
# *.sqlite
# *.sqlite3
```

---

## 🔒 Security Checklist

- ✅ Set `APP_DEBUG=false` in production
- ✅ Generate new `APP_KEY` for production
- ✅ Set strong passwords for admin/gudang users
- ✅ Enable HTTPS (automatic on Railway/Fly/Render)
- ✅ Add CORS configuration if using API

---

## 💰 Cost Comparison

| Platform | Free Tier | Best For |
|----------|-----------|----------|
| **Railway** | $5/month credit | Full Laravel apps |
| **Fly.io** | 3 shared VMs | Small apps |
| **Render** | Free (sleeps) | Testing/demos |
| **Vercel** | Unlimited | Static sites/APIs |
| **Heroku** | ❌ No longer free | N/A |

---

## 🚀 Quick Start - Railway Deployment

```bash
# 1. Create Procfile
echo "web: php artisan serve --host=0.0.0.0 --port=\${PORT:-8000}" > Procfile

# 2. Commit changes
git add .
git commit -m "Prepare for Railway deployment"

# 3. Push to GitHub
git push origin main

# 4. Go to railway.app and deploy!
```

---

## 📞 Need Help?

Common issues:
- **Database not found**: Make sure `database/database.sqlite` is committed to Git
- **APP_KEY error**: Generate with `php artisan key:generate` locally, copy to Railway env vars
- **Permission errors**: Set storage permissions in Railway startup command
- **Routes not working**: Check `APP_URL` in Railway environment variables

---

**Ready to deploy? Choose Railway.app for the easiest experience! 🎉**
