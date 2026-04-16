# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**DawlyStor** is a multi-vendor marketplace platform with two separate codebases that work together:

| Codebase | Location | Stack | Port |
|---|---|---|---|
| Backend (this repo) | `D:/code/dawlyStor_backend-main` | Laravel 12, PHP 8.2+, MySQL | 8000 |
| Frontend | `D:/code/multi-vendor-master` | React 18 + Vite + Tailwind | 5173 |

## Commands

### Backend (Laravel)
```bash
# Start dev server
php artisan serve

# Run migrations fresh + seed
php artisan migrate:fresh --seed

# Run a specific seeder
php artisan db:seed --class=ToysCategorySeeder

# Clear all caches after config/route changes
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# Run tests
php artisan test
php artisan test --filter=WishlistTest          # single test class
php artisan test tests/Feature/Auth             # single directory
```

### Frontend
```bash
cd D:/code/multi-vendor-master
npm install
npm run dev      # http://localhost:5173
npm run build
```

## Architecture

### Backend Structure

**Auth guards** — three separate guards:
- `admin` → `App\Models\Admin` (platform admins)
- `store` → `App\Models\Store` (vendor/seller accounts)
- `customer` (Sanctum) → `App\Models\Customer`

**Two dashboard panels** (Inertia/React served by Laravel, not the separate frontend):
- `/admin/*` — platform admin panel
- `/store/*` — store owner dashboard
- Panel detection via `PanelsEnum` + `getPanel()` / `isAdminPanel()` helpers in `app/Helpers/functions.php`

**Customer-facing API** (consumed by the React frontend at `D:/code/multi-vendor-master`):
All routes prefixed `/api/customer/`. Public routes include home sections, products listing, store listing, and store-categories. Authenticated routes (Sanctum) include profile, orders, addresses, checkout, and wishlist.

### Key Models & Relationships

```
StoreCategory           ← platform-level category (Electronics, Fashion, Toys, etc.)
  └── Store             ← vendor/seller (authenticatable, has Wallet, Cashier subscription)
        ├── Category    ← store-level product sub-category (Smartphones, Laptops, etc.)
        └── Product     ← has media (Spatie), translatable name/description
              ├── Addition
              └── Option

Customer                ← authenticatable, has Sanctum tokens
  ├── Address
  ├── Order
  │     └── orderItems
  └── WishlistItems → Product
```

**Important**: `Store.category()` is a `belongsTo(StoreCategory::class)` — the store's platform category. `Store.categories()` is `hasMany(Category::class)` — the store's own product sub-categories.

### Translatable Fields (Spatie HasTranslations)

`Store`: `name`, `description`, `address`
`Product`: `name`, `description`
`StoreCategory`: `name`, `description`
`Category`: `name`, `description`

Always store as `['en' => '...', 'ar' => '...']`. Use `getByLocale($array)` helper to resolve by current locale. In seeders, use `Category::unsetEventDispatcher()` before bulk creating categories to bypass the auth guard check.

### Home Page Sections

The home page is driven by `Section` model records in the DB. `SectionResource` resolves each section by its `HomePageSectionsType` enum (`hero`, `features`, `products`, `categories`, `stores`, `vendor_cta`). The `data` JSON column on Section controls the source strategy (`latest`, `manual`, `featured_only`, etc.) and config.

### API Response Convention

All API endpoints use `successResponse($data, $message)` / `errorResponse($message, $status)` from `app/Helpers/functions.php`. Shape: `{ success, message, data, extra }`.

**Pagination**: The products endpoint uses `->get()` (no pagination) so the frontend can filter client-side across all products.

### Media (Spatie MediaLibrary)

- `MEDIA_DISK=public` in `.env`
- Store logos: collection `store-logos` → `$store->getFirstMediaUrl('store-logos')`
- Product images: collection `product-images` → `$product->getMedia('product-images')`
- Store category images: collection `store-categories`
- `APP_URL` must be `http://localhost:8000` (not just `http://localhost`) or media URLs will point to port 80

### Seeders

Run order for a full dataset:
1. `PermissionsSeeder` — roles & permissions
2. `SuperAdminsSeeder` — platform admin accounts
3. `CustomerSeeder` — demo customers
4. `StoreSeeder` — 8 store categories × 4 stores × 10 products = 320 products
5. `ToysCategorySeeder` — Toys & Games category (4 stores × 13 products)
6. `StoresOverTimeSeeder` → `BetaMarketExtraSeeder` — large product/order dataset for a single store (analytics demo)

## Frontend ↔ Backend Integration

The frontend reads `VITE_APP_API_BASE_URL` (defaults to `http://127.0.0.1:8000`). CORS and Sanctum stateful domains are configured in `.env` under `SANCTUM_STATEFUL_DOMAINS`.

### Category Filtering Convention

The frontend filter uses **store category ID** (integer) as the `?category=` URL param, matched against `product.store.category_id` in the response. All three entry points must use the same key:
- `CategoryBar.jsx` — links to `/products?category={storeCategory.id}`
- `CategoryCard.jsx` (home page) — links to `/products?category={category.id}`
- `Products.jsx` sidebar — buttons set `?category={storeCategory.id}`

The backend `GET /api/customer/products` response includes `store.category_id` via eager load `store:id,name,slug,category_id`.
