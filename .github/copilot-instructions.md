# AI Coding Agent Instructions

This is a **Laravel 12 self-order system** (Table-based restaurant ordering platform with QR code + Midtrans payment integration). Essential context for rapid productivity.

## Architecture Overview

**Core Data Flow:**

1. **QR Code → Table Session** (`TableController`): QR codes embed table IDs, stored in `session('table_number')`
2. **Menu → Cart (Session)** (`CartController`): Menu items stored in `Session::get('cart')` - NOT database
3. **Cart → Order** (`OrderController`): Session cart converted to Order model on checkout
4. **Payment Integration** (`MidtransService`): Creates Snap tokens for QRIS/payment processing

**Component Boundaries:**

- **MenuController**: Hardcoded menu array (9 items) filtered by category - upgrade to database gradually if needed
- **CartController**: Session-based cart with AJAX support + manual `getCartSummary()` helper
- **OrderController**: Converts session data to persistent Order records + payment creation
- **PaymentController**: Webhook handler for Midtrans payment callbacks
- **MidtransService**: External payment API wrapper - handles all Midtrans config + error logging

## Key Patterns & Conventions

### Session-Based Cart (NOT Database)

```php
// Cart stored as: ['menu_id' => ['name', 'price', 'quantity', 'image', 'notes']]
Session::put('cart', $cart);
$cartSummary = CartController::getCartSummary(); // Static helper
```

### AJAX-First Controller Responses

Controllers detect AJAX requests and return JSON:

```php
if ($request->ajax() || $request->wantsJson()) {
    return response()->json(['success' => true, 'cart_summary' => ...]);
}
return redirect()->back()->with('success', 'Item added');
```

### Environment-Driven Config

Midtrans credentials via `.env`: `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION`

### Array Data Casting

Orders store items as JSON: `protected $casts = ['items' => 'array'];`

## Development Workflow

**Setup:**

```bash
composer run setup  # Install dependencies, generate keys, migrate, build assets
composer run dev    # Concurrent: Laravel server + queue + logs + Vite HMR
```

**Core Commands:**

- `php artisan migrate --force` - Database schema
- `php artisan queue:listen --tries=1` - Background jobs
- `npm run dev` - Vite dev server (Tailwind + HMR)
- `npm run build` - Production build
- `php artisan tinker` - REPL for testing

**Testing:**

- `composer test` - PHPUnit suite (Feature + Unit)
- Tests assume sandboxed environment

## Project-Specific Details

### Dependencies

- **Payment:** `midtrans/midtrans-php` (v2.6) - Snap token creation + transaction status
- **QR Codes:** `simplesoftwareio/simple-qrcode` (v4.2) - Generate QR codes
- **Frontend:** Tailwind v4 + Bootstrap v5.3 + Axios (AJAX)
- **Database ORM:** Laravel Eloquent with PSR-4 autoload

### File Organization

- `app/Http/Controllers/` - Request handlers (7 controllers)
- `app/Models/` - Eloquent models (Order, Table, Menu via array, CartItem, User)
- `app/Services/MidtransService.php` - External payment service abstraction
- `resources/views/` - Blade templates organized by feature (cart/, menus/, orders/, payment/, qr/)
- `resources/css/app.css` + `resources/js/app.js` - Vite entry points (Tailwind CSS plugin)
- `database/migrations/` - Schema including Order/CartItem/Table/Menu tables
- `database/seeders/TableSeeder.php` - Populate restaurant tables

### Critical Files to Reference

- [app/Http/Controllers/MenuController.php](app/Http/Controllers/MenuController.php) - Hardcoded menu (UPDATE: migrate to DB if scaling)
- [app/Services/MidtransService.php](app/Services/MidtransService.php) - All payment logic here
- [routes/web.php](routes/web.php) - Route organization by feature (Home, Cart, Checkout, Payment)
- [app/Http/Controllers/CartController.php](app/Http/Controllers/CartController.php) - Session cart logic + AJAX handling

## Common Patterns

**When Adding Features:**

1. Add route in `routes/web.php` with descriptive comment sections
2. Create controller method returning `view(...)` or `response()->json()`
3. For AJAX: Check `$request->ajax()` before returning JSON
4. Session cart operations: Use static `CartController::getCartSummary()`

**When Debugging:**

- Check `.env` for Midtrans keys (sandbox vs production mode)
- Session errors? Verify session driver in `config/session.php`
- Payment failures? Check `storage/logs/laravel.log` for Midtrans exceptions

**Data Persistence:**

- Menu: Currently hardcoded arrays (design choice)
- Cart: Session only (intentional, survives page refresh within session)
- Orders: Database persistent via `Order` model
- Tables: Database seeded in `TableSeeder.php`

## Integration Points

- **Midtrans Webhooks** → `PaymentController@webhook` (handles `payment.success`, `payment.failed`)
- **Session Flash Messages** → Blade templates display `@if($errors->has(...))` or `session('success')`
- **QR Code Generation** → Via `simplesoftwareio/simple-qrcode` (see payment views)
- **Frontend State** → Axios AJAX to controllers returning JSON cart updates

---

**Last Updated:** January 22, 2026  
**Target PHP Version:** ^8.2 | **Laravel:** ^12.0
