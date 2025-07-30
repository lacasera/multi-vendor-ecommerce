#  Laravel Multi Vendor Store with Filament and React

### Local Setup Instructions
1. clone repository
2. change `APP_PORT` to desired port (optional, defaults to `80`)
3. copy `.env.docker` into `.env` by running `cp .env.docker .env`
4. add  a `STRIPE_SECRET_KEY=` to the env
5. run `make setup` to build containers
6. visit `http://localhost:{APP_PORT}`

### Features

#### User Features
-	View products
-	Add items to cart
-	Checkout and make payments
-	View previous orders

#### Supplier Features
-	Sign in and sign up
-	Manage (Create, Read, Update, Delete) products
-	View orders placed by their users

### Tech Stack
- Backend: Laravel 
- Supplier Dashboard: Laravel Filament 
- Frontend: React (included in the same Laravel project for simplicity)
- Database: MySQL 
- Payment Integration: Stripe

### Architectural Overview

When suppliers create products, users can view and purchase products from multiple suppliers.
During checkout, the system ensures a structured tracking mechanism for orders and payments using the following entities:
- `Checkout`: Represents the overall user’s order and tracks payment status.
- `Order`: Tracks supplier-specific products purchased by a user. Since a checkout can contain products from multiple suppliers, separate orders are created for each supplier.
- `OrderItem`: Represents the specific products purchased within an order.
- `User`: This represents a user entity using the application. a user can be one of the following:
  1. `user`: a normal user purchasing products from the storefront
  2. `supplier`: a user that can list products.
  3. `admin`: a system user. (features not implemented)

#### User Checkout Flow

#### During checkout:

#### When a checkout is created:
1.	The product quantity is deducted from stock to prevent overselling. 
2.	When payment is confirmed, the checkout and associated orders are marked as paid.
3.	If payment fails, the checkout and orders are marked as failed, and the previously deducted product quantities are restored.

### NOTE
1. To guard against `RACE CONDITIONS` on product being out of stock but item is placed, a `pessimistic lock` is placed on the product row within a database transaction.
2. In an ideal scenario, relying on `Stripe’s webhook notification system` for payment confirmation would have been preferable for a more robust system. 
This approach would allow us to have a `job/worker` that automatically releases withheld product quantities after a specified period if payments are not confirmed within that timeframe.

### Shortcuts Taken Due to Time Constraints

To meet the time constraints, the following shortcuts were taken:
1.	Laravel Filament for Supplier Dashboard: Instead of building a separate supplier dashboard, Laravel Filament was used to speed up development.
2.	Monolithic Project Structure: The React store frontend is in the same Laravel project. Ideally, they would be separate applications.
3.	Product Filtering & Pagination: The backend API supports filtering and pagination, but it was not integrated into the frontend due to time constraints.
4.	Checkout Shipping & Delivery Info: The frontend includes a form for collecting user details during checkout, but the data is not stored since this is a demo app.
5.	Product Variations: A fully-fledged e-commerce application would need a variations table to store different versions of the same product. This was omitted due to time limitations.
6.  Order Item details are not displayed on the frontend due to time.
