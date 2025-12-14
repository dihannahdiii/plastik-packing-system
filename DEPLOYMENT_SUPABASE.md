# Railway + Supabase Deployment Guide

## 🔵 Using Supabase Database with Railway Hosting

This guide shows how to use Supabase PostgreSQL database with your Laravel app hosted on Railway.

---

## Step 1: Create Supabase Project

1. Go to https://supabase.com
2. Sign up with GitHub
3. Click "New Project"
4. Name: `plastik-packing`
5. Database Password: **(save this!)**
6. Region: Choose closest to you
7. Click "Create new project"

---

## Step 2: Get Database Credentials

1. In Supabase dashboard, go to **Settings** → **Database**
2. Scroll to **Connection string**
3. Copy the **URI** format:
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres
   ```

---

## Step 3: Update Laravel Configuration

### Update `.env` (or Railway environment variables):

```env
# Change from SQLite to PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
```

### Update `config/database.php` (if needed):

Laravel already supports PostgreSQL by default, no changes needed!

---

## Step 4: Install PostgreSQL PHP Extension

### Update `composer.json`:

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "ext-pdo_pgsql": "*"
  }
}
```

Run:
```bash
composer update
```

---

## Step 5: Deploy to Railway

1. **Push code to GitHub:**
```bash
git add .
git commit -m "Configure PostgreSQL for Supabase"
git push origin main
```

2. **In Railway dashboard:**
   - Add environment variables from Step 3
   - Railway will redeploy automatically

3. **Run migrations:**
   - Open Railway shell
   - Run: `php artisan migrate:fresh --seed --force`

---

## Step 6: Verify Connection

In Railway shell:
```bash
php artisan tinker
```

Then run:
```php
DB::connection()->getPdo();
// Should return PDO object without errors

User::count();
// Should return number of users
```

---

## 🎯 Benefits of This Setup

### ✅ Railway (App Hosting)
- Hosts your PHP Laravel application
- Handles HTTP requests
- Serves Blade templates
- Manages deployments

### ✅ Supabase (Database)
- PostgreSQL database
- Automatic backups
- Better performance
- Database UI for viewing data
- Can scale independently

---

## 💰 Cost Breakdown

### Supabase Free Tier:
- ✅ 500MB database
- ✅ 2GB file storage
- ✅ Unlimited API requests
- ✅ Automatic backups (7 days)

### Railway Free Tier:
- ✅ $5/month credit
- ✅ Enough for small apps

**Total: 100% FREE for small projects!**

---

## 🔄 Migration from SQLite to PostgreSQL

If you already have SQLite data:

1. **Export SQLite data:**
```bash
php artisan db:seed --force
```

2. **Switch to PostgreSQL** (follow steps above)

3. **Re-run migrations:**
```bash
php artisan migrate:fresh --seed --force
```

Note: SQLite → PostgreSQL migration is straightforward for this app since you're using Laravel migrations.

---

## 📊 Accessing Your Database

### Via Supabase Dashboard:
1. Go to **Table Editor**
2. View all tables visually
3. Query data with SQL editor
4. Export data as CSV

### Via Railway Shell:
```bash
php artisan tinker
```

```php
// View orders
Order::with('details')->get();

// View users
User::all();

// Check stock
Stock::with('product', 'location')->get();
```

---

## 🐛 Troubleshooting

### Error: "could not connect to server"
**Solution:** Check database credentials in Railway env vars

### Error: "relation does not exist"
**Solution:** Run migrations: `php artisan migrate:fresh --force`

### Error: "password authentication failed"
**Solution:** Verify Supabase password is correct

### PostgreSQL-specific issues:
```bash
# Clear config cache
php artisan config:clear

# Test connection
php artisan db:show
```

---

## 🔒 Security Best Practices

1. **Use environment variables** - Never commit database credentials
2. **Enable SSL** - Supabase enforces SSL by default
3. **Rotate passwords** - Change Supabase password periodically
4. **IP restrictions** - Enable in Supabase if needed

---

## 📈 When to Upgrade

### Stay with SQLite if:
- Database < 100MB
- < 100 concurrent users
- Simple queries only

### Switch to Supabase if:
- Database > 100MB
- Need automatic backups
- Need better performance
- Planning to scale

---

## 🚀 Quick Deploy Commands

```bash
# 1. Update dependencies
composer require ext-pdo_pgsql

# 2. Update .env with Supabase credentials
# (do this manually or via Railway dashboard)

# 3. Commit and push
git add .
git commit -m "Add PostgreSQL support"
git push origin main

# 4. Deploy on Railway
# Railway auto-deploys from GitHub

# 5. Run migrations in Railway shell
php artisan migrate:fresh --seed --force
```

---

## 🎉 You're Done!

Your Laravel app is now:
- ✅ Hosted on Railway (free $5/month credit)
- ✅ Using Supabase PostgreSQL (free 500MB)
- ✅ Automatic backups
- ✅ Better performance
- ✅ Ready to scale

**Total cost: $0 for small projects!**
