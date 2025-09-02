1. Setup Instructions

Clone the repository:

    https://github.com/Sourav-gupta-98/laravel-project.git
    cd your-repo


Install dependencies:

    composer install


Environment setup:
Create a new .env file in the project root (if it doesn’t already exist).

    touch .env


Add the required configuration values, for example:

    APP_NAME=Laravel
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=Asdf@1234
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=database
    SESSION_DRIVER=file
    SESSION_LIFETIME=120

Run migrations:

    php artisan migrate


Start services:

    php artisan serve          # Laravel HTTP server  
    php artisan queue:work     # Queue worker for background jobs  
    php artisan workerman:serve # WebSocket server  
    php artisan test           # Run test suite  

2. Multi-Auth Strategy & Route Protection

    Implemented two guards: admin and customer.
    
    Each guard has its own middleware (AuthAdmin, AuthCustomer) that checks if the user is authenticated.
    
    Routes are segregated into:
    
    routes/admin.php → Admin auth, dashboard, product CRUD, order management
    
    routes/customer.php → Customer auth, dashboard, cart, and order placement

Example middleware logic:

    if (!auth()->guard('admin')->check()) {
        return redirect()->route('admin.login');
    }


This ensures separation of concerns and prevents unauthorized access across user types.

3. WebSocket Stack Used

    Workerman WebSocket server (app/WebSockets/WorkermanServer.php)
    
    Integrated with Laravel via a custom Artisan command (workerman:serve).
    
    Responsibilities:
    
    Handle authentication messages from clients
    
    Maintain presence state (online/offline) in both DB and memory
    
    Broadcast status updates (order status, presence) to relevant users in real time
    
    Example: when an Admin logs in, presence is updated in DB (logged_in_status = ACTIVE) and broadcast to all connected Admins and Customers.

4. Web Push Notification Setup & Subscription Logic using socket (workerman composer package)

    Integrated browser push notifications
    
    Flow:
    
    Customer subscribes to notifications in the browser.
    
    Subscription data (endpoint, keys) is stored in DB.
    
    When Admin updates an order, a push notification is triggered and delivered to the customer.
    
    Simultaneously, a WebSocket event is sent for real-time updates.
    
    This dual approach ensures that customers get updates even if the app/browser tab isn’t open.

5. Bulk Import Implementation & Optimizations

    Maatwebsite/Laravel-Excel used with:
    
    WithChunkReading → reads data in chunks of 500 rows to avoid memory spikes.
    
    ShouldQueue → processes rows asynchronously in queue workers.
    
    OnEachRow → processes one row at a time, enabling validation and error handling.

Implementation:

    public function chunkSize(): int {
        return 500;
    }


Optimizations:

    Each product gets a unique_id via UtilityService::generateUniqueCode().
    
    Missing fields (e.g., description, stock) are defaulted.
    
    Errors are logged but do not stop the import process.
    
    This design allows importing 100k+ records without timeouts.

6. Testing Guide
Run all tests:
    php artisan test

Test coverage includes:

Feature Tests:

Product creation flow


7. Notes on Architectural & Performance Decisions

Multi-auth separation: Choose custom middlewares + route files to keep Admin and Customer flows independent and secure.

Workerman WebSocket: Selected for its lightweight performance and simplicity vs. Laravel Echo Server or Pusher.

Chunked Import + Queue: Ensures large imports do not block HTTP requests or exhaust server memory.

Database Presence Updates: Presence is stored in DB (logged_in_status and logged_in_time), so state is preserved even if WebSocket server restarts.

Minimal UI: Kept intentionally simple to focus on backend clarity and performance.

Error Logging: Failures in import or WebSockets are logged without breaking the entire process.
