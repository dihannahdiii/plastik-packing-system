# Vercel + Supabase Deployment Guide

## 🎯 Architecture Overview

```
Frontend (Next.js on Vercel) ──→ Supabase (Database + Auth + API)
```

**Important:** Vercel **CANNOT** host PHP/Laravel directly. You have 2 options:

---

## Option A: Convert to Next.js + Supabase (RECOMMENDED for Vercel)

### What needs to change:
- ❌ Remove Laravel backend
- ✅ Create Next.js frontend (React)
- ✅ Use Supabase for database
- ✅ Use Supabase Auth for login
- ✅ Use Supabase Client for API calls

### Pros:
- ✅ 100% FREE forever (Vercel + Supabase free tiers)
- ✅ Blazing fast performance
- ✅ Auto-scaling
- ✅ Global CDN

### Cons:
- ⚠️ Requires complete rewrite (2-3 days work)
- ⚠️ No PHP/Laravel
- ⚠️ Learning curve for Next.js

---

## Option B: Vercel (Frontend) + Laravel API (Railway) + Supabase (DB)

### Architecture:
```
Frontend (Vercel) ──→ Laravel API (Railway) ──→ Supabase (DB)
```

### What needs to change:
- ✅ Convert Blade views to React/Next.js
- ✅ Keep Laravel as API-only
- ✅ Laravel hosted on Railway
- ✅ Frontend on Vercel

### Pros:
- ✅ Keep existing Laravel code
- ✅ Separate frontend/backend
- ✅ Better for team collaboration

### Cons:
- ⚠️ More complex setup
- ⚠️ Need to manage 2 services

---

## 🚀 OPTION A: Full Next.js + Supabase (Pure Vercel)

This is the TRUE "Vercel + Supabase" approach. Let me create the migration guide:

### Step 1: Create Supabase Project

1. Go to https://supabase.com
2. Create new project: `plastik-packing`
3. Save database password

### Step 2: Create Database Schema in Supabase

In Supabase SQL Editor, run:

```sql
-- Users table (Supabase Auth handles this automatically)

-- Locations
CREATE TABLE locations (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Products
CREATE TABLE products (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Orders
CREATE TABLE orders (
  id BIGSERIAL PRIMARY KEY,
  order_number VARCHAR(255) UNIQUE NOT NULL,
  user_id UUID REFERENCES auth.users(id),
  customer_name VARCHAR(255) NOT NULL,
  customer_phone VARCHAR(20) NOT NULL,
  customer_address TEXT NOT NULL,
  status VARCHAR(50) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Order Details
CREATE TABLE order_details (
  id BIGSERIAL PRIMARY KEY,
  order_id BIGINT REFERENCES orders(id) ON DELETE CASCADE,
  product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
  quantity INTEGER NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Stock
CREATE TABLE stock (
  id BIGSERIAL PRIMARY KEY,
  product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
  location_id BIGINT REFERENCES locations(id) ON DELETE CASCADE,
  quantity INTEGER NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW(),
  UNIQUE(product_id, location_id)
);

-- Indexes
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_stock_product ON stock(product_id);
CREATE INDEX idx_stock_location ON stock(location_id);
```

### Step 3: Seed Data

```sql
-- Insert locations
INSERT INTO locations (name, description) VALUES
('Rak A1', 'Rak bagian atas kiri'),
('Rak A2', 'Rak bagian atas kanan'),
('Rak B1', 'Rak bagian tengah kiri'),
('Rak B2', 'Rak bagian tengah kanan'),
('Rak C1', 'Rak bagian bawah kiri'),
('Rak C2', 'Rak bagian bawah kanan');

-- Insert products
INSERT INTO products (name, description, price) VALUES
('Plastik HD 15x30', 'Plastik HD ukuran 15x30 cm', 500),
('Plastik HD 20x35', 'Plastik HD ukuran 20x35 cm', 750),
('Plastik PP 15x30', 'Plastik PP ukuran 15x30 cm', 600),
('Plastik PP 20x35', 'Plastik PP ukuran 20x35 cm', 850),
('Bubble Wrap Roll', 'Bubble wrap ukuran 1 meter', 50000),
('Tape Bening 2 inch', 'Tape bening lebar 2 inch', 5000),
('Tape Coklat 2 inch', 'Tape coklat lebar 2 inch', 5000),
('Kardus Kecil', 'Kardus ukuran kecil', 3000),
('Plastik HD 25x40', 'Plastik HD ukuran 25x40 cm', 1000),
('Plastik PP 25x40', 'Plastik PP ukuran 25x40 cm', 1200);
```

### Step 4: Create Next.js App

```bash
# Create new Next.js app
npx create-next-app@latest plastik-packing-frontend
cd plastik-packing-frontend

# Install Supabase client
npm install @supabase/supabase-js
npm install @supabase/auth-helpers-nextjs

# Install UI library (optional)
npm install @headlessui/react @heroicons/react
```

### Step 5: Configure Supabase Client

Create `.env.local`:
```env
NEXT_PUBLIC_SUPABASE_URL=https://your-project.supabase.co
NEXT_PUBLIC_SUPABASE_ANON_KEY=your-anon-key
```

Create `lib/supabase.js`:
```javascript
import { createClient } from '@supabase/supabase-js'

const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL
const supabaseAnonKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY

export const supabase = createClient(supabaseUrl, supabaseAnonKey)
```

### Step 6: Create Pages

**Example: Orders List Page (`app/admin/orders/page.js`)**

```javascript
'use client'
import { useEffect, useState } from 'react'
import { supabase } from '@/lib/supabase'

export default function OrdersPage() {
  const [orders, setOrders] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchOrders()
  }, [])

  async function fetchOrders() {
    const { data, error } = await supabase
      .from('orders')
      .select(`
        *,
        order_details (
          *,
          products (*)
        )
      `)
      .order('created_at', { ascending: false })
    
    if (error) console.error(error)
    else setOrders(data)
    setLoading(false)
  }

  if (loading) return <div>Loading...</div>

  return (
    <div className="container mx-auto p-8">
      <h1 className="text-3xl font-bold mb-6">Daftar Pesanan</h1>
      <div className="bg-white rounded-lg shadow">
        <table className="min-w-full">
          <thead className="bg-gray-200">
            <tr>
              <th className="px-6 py-3">No. Pesanan</th>
              <th className="px-6 py-3">Konsumen</th>
              <th className="px-6 py-3">Status</th>
            </tr>
          </thead>
          <tbody>
            {orders.map(order => (
              <tr key={order.id}>
                <td className="px-6 py-4">{order.order_number}</td>
                <td className="px-6 py-4">{order.customer_name}</td>
                <td className="px-6 py-4">
                  <span className={`px-3 py-1 rounded-full text-xs ${
                    order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                    order.status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                    order.status === 'completed' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800'
                  }`}>
                    {order.status}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
```

### Step 7: Set Up Authentication

Create `app/login/page.js`:

```javascript
'use client'
import { useState } from 'react'
import { supabase } from '@/lib/supabase'
import { useRouter } from 'next/navigation'

export default function LoginPage() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const router = useRouter()

  async function handleLogin(e) {
    e.preventDefault()
    const { data, error } = await supabase.auth.signInWithPassword({
      email,
      password
    })

    if (error) {
      alert(error.message)
    } else {
      // Check user role from metadata
      const role = data.user.user_metadata.role
      if (role === 'admin') {
        router.push('/admin/orders')
      } else {
        router.push('/gudang')
      }
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center">
      <form onSubmit={handleLogin} className="bg-white p-8 rounded-lg shadow">
        <h2 className="text-2xl font-bold mb-6">Login</h2>
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          className="w-full px-4 py-2 border rounded mb-4"
        />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          className="w-full px-4 py-2 border rounded mb-4"
        />
        <button className="w-full bg-gray-800 text-white py-2 rounded">
          Login
        </button>
      </form>
    </div>
  )
}
```

### Step 8: Real-Time Features

```javascript
// Subscribe to order changes
useEffect(() => {
  const channel = supabase
    .channel('orders')
    .on('postgres_changes', 
      { event: '*', schema: 'public', table: 'orders' },
      (payload) => {
        console.log('Order changed:', payload)
        fetchOrders() // Refresh list
      }
    )
    .subscribe()

  return () => {
    supabase.removeChannel(channel)
  }
}, [])
```

### Step 9: Deploy to Vercel

```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel

# Follow prompts, add environment variables
```

Or use GitHub integration:
1. Push to GitHub
2. Import to Vercel
3. Add environment variables
4. Auto-deploy!

---

## 📊 Comparison: Laravel vs Next.js + Supabase

| Feature | Laravel (Current) | Next.js + Supabase |
|---------|------------------|-------------------|
| Language | PHP | JavaScript/TypeScript |
| Database | SQLite/PostgreSQL | PostgreSQL (Supabase) |
| Auth | Laravel Auth | Supabase Auth |
| Hosting | Railway ($5 credit) | Vercel (FREE unlimited) |
| Backend | Laravel controllers | Supabase API |
| Real-time | Manual polling | Built-in WebSockets |
| File Storage | Local | Supabase Storage |
| Learning Curve | You know it | Need to learn |

---

## ⏱️ Migration Time Estimate

- **Setup Supabase**: 30 minutes
- **Create Next.js app**: 1 hour
- **Convert home page**: 2 hours
- **Convert order pages**: 4 hours
- **Convert warehouse pages**: 4 hours
- **Testing**: 2 hours
- **Deploy**: 30 minutes

**Total: 2-3 days** for a developer familiar with React

---

## 💰 Cost

### Supabase Free Tier:
- ✅ 500MB database
- ✅ 1GB file storage
- ✅ Unlimited API requests
- ✅ 50,000 monthly active users

### Vercel Free Tier:
- ✅ Unlimited deployments
- ✅ 100GB bandwidth/month
- ✅ Automatic HTTPS
- ✅ Global CDN

**Total: $0 forever** (unless you exceed limits)

---

## 🎯 Recommendation

### If you want Vercel + Supabase ONLY:
1. **Accept the rewrite** - It's a 2-3 day project
2. **Learn Next.js** - Great skill to have
3. **Benefits are huge** - Better performance, scalability, cost

### If you want to stay with Laravel:
1. Use **Railway + Supabase** (Option B from before)
2. Keep your existing code
3. Deploy in 15 minutes

---

## 🚀 Quick Start Template

Want me to create the Next.js migration files? I can:
1. Create the folder structure
2. Generate Next.js pages matching your Blade views
3. Set up Supabase client
4. Create API routes

**This would be a separate project folder** since it's a complete rewrite.

Let me know if you want to proceed with the Next.js conversion!
