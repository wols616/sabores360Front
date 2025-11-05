# API Routes for Sabores360

This document lists the API endpoints the frontend uses. Use these exact paths when implementing the Spring backend so the views and JS work without changes.

Base path: `/api`

Authentication (auth)

- POST /api/auth/login

  - Description: Authenticate user.
  - Request: { "email": "string", "password": "string" }
  - Response (200): { "success": true, "token": "JWT|string", "user": { "id": int, "name": string, "email": string, "role": "client|seller|admin" } }
  - Error (401): { "success": false, "message": "invalid_credentials" }

- POST /api/auth/register

  - Description: Create a new client account.
  - Request: { "name": string, "email": string, "address": string, "password": string }
  - Response (201): { "success": true, "userId": int }
  - Error (400): { "success": false, "message": "email_exists" }

- POST /api/auth/forgot-password

  - Description: Request password reset email. Request body: { "email": string }
  - Response (200): { "success": true }
  - Error (404): { "success": false, "message": "email_not_found" }

- POST /api/auth/reset-password

  - Description: Reset password (uses token). Body: { "token": string, "password": string }
  - Response (200): { "success": true }
  - Error (400): { "success": false, "message": "invalid_token" }

- GET or POST /api/auth/logout
  - Description: Invalidate session/JWT. (Frontend links perform GET for compatibility; backend should accept GET and POST.)
  - Response (200): { "success": true }

Admin endpoints (/api/admin)

- GET /api/admin/dashboard

  - Description: Returns dashboard data for admin (orders summary, revenue, users, low-stock products).
  - Response: { "success": true, "data": { /_ dashboard fields _/ } }

- GET /api/admin/orders/stats

  - Description: Orders summary numbers.
  - Response: { "success": true, "stats": { "pending": int, "shipped": int, ... } }

- GET /api/admin/vendors

  - Description: List of vendors/sellers.
  - Response: { "success": true, "vendors": [ {id,name,...} ] }

- GET /api/admin/orders

  - Description: Paginated orders list. Query params: page, per_page, filters (status, date_from, date_to, vendor_id, search)
  - Response: { "success": true, "orders": [...], "pagination": {"page":1,"total_pages":N} }

- GET /api/admin/orders/{id}

  - Description: Get full order details.
  - Response: { "success": true, "order": { ... } }

- PUT /api/admin/orders/{id}/status

  - Description: Update order status. Body: { "status": string }
  - Response: { "success": true }

- GET /api/admin/orders/export

  - Description: Export orders CSV/XLS. Same query params as list.
  - Response: file stream (CSV/XLS)

- DELETE /api/admin/orders/{id}

  - Description: Delete an order.
  - Response: { "success": true }

- GET /api/admin/products/stats

  - Description: Statistics for products (total, low_stock_count, inactive, ...)

- GET /api/admin/categories

  - Response: { "success": true, "categories": [...] }

- GET /api/admin/products

  - Description: List/filter products. Query params: page, per_page, category, vendor, search, low_stock

- GET /api/admin/products/{id}

  - Description: Product details

- POST /api/admin/products

  - Description: Create product (form-data for images). Body: multipart/form-data {name,price,category_id,vendor_id,stock,is_available,image}

- PUT /api/admin/products/{id}

  - Description: Update product

- POST /api/admin/products/{id}/toggle-status

  - Description: Toggle active/inactive

- DELETE /api/admin/products/{id}

- GET /api/admin/products/export

- GET /api/admin/reports

  - Description: Reports data (sales, taxes, KPIs). Query params: date_from, date_to, period preset

- GET /api/admin/reports/export

- GET /api/admin/users

  - Response: { "success": true, "users": [...], "pagination": {...} }

- POST /api/admin/users

  - Create user

- PUT /api/admin/users/{id}

  - Update user

- POST /api/admin/users/{id}/status

  - Toggle user status (active/inactive). Body: { "status": "active|inactive" }

- DELETE /api/admin/users/{id}

Client endpoints (/api/client)

- GET /api/client/products

  - Description: List products available to client. Query params: category, search, page

- POST /api/client/orders

  - Description: Place an order. Body: { "delivery_address": string, "payment_method": string, "cart": [ {"id":int,"quantity":int} ], ... }
  - Response (201): { "success": true, "order_id": int }

- GET /api/client/orders

  - Description: Client's orders list. Query params: page, per_page

- GET /api/client/orders/{id}

- POST /api/client/orders/{id}/cancel

  - Description: Request order cancellation.

- POST /api/client/orders/{id}/reorder

  - Description: Reorder same items.

- GET /api/client/profile/stats

  - Response: { "success": true, "stats": { total_orders:int, total_spent:float, favorite_category:string } }

- GET /api/client/orders/recent

- GET /api/client/favorites

- PUT /api/client/profile

  - Body: { name, email, address }

- POST /api/client/change-password
  - Body: { current_password, new_password }

Seller endpoints (/api/seller)

- GET /api/seller/dashboard
- GET /api/seller/products/stock
- GET /api/seller/products
- POST /api/seller/products/{id}/availability
- POST /api/seller/products/{id}/stock
- POST /api/seller/products/stocks
- POST /api/seller/products/bulk-update
- GET /api/seller/orders
- GET /api/seller/orders/{id}
- POST /api/seller/orders/{id}/status
- POST /api/seller/orders/{id}/assign

Notes

- All JSON responses for successful API calls should include { "success": true, ... } and on errors { "success": false, "message": "error_code" }.
- Authentication: Prefer JWT in Authorization: Bearer <token> header or session cookie depending on backend.
- File uploads: use multipart/form-data. Responses should return created resource id and URL to uploaded file.

Implement these endpoints in Spring using the exact paths above so the frontend references match (e.g., `POST /api/auth/login`, `GET /api/client/products`).
