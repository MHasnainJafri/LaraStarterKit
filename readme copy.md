# **Laravel Starter Kit Documentation**

Welcome to the Laravel Starter Kit! This starter kit is designed to accelerate your development process by providing a robust, secure, and high-performance foundation for building Laravel applications. Below, you will find detailed information about the features, setup instructions, and how to leverage the tools included in this kit.

---

## **Key Features**

### **7. CRUD Service Generator**
- Create CRUD services with a single command:
  ```bash
  php artisan service:create ModelName
  ```
- Automates repetitive tasks and ensures consistency across your application.

---
### **6. API Development**
- Built-in support for quick API development using the **`mhasnainjafri/restapikit`** library.
- Optimized for performance, caching, and scalability.

---
### **1. One-Click Disk Change**
- The filesystem can be changed globally with ease using custom global functions.
- Simplifies switching between local, cloud, or hybrid storage systems.

---

### **2. Real-Time Notifications**
- Powered by **Laravel Reverb**, real-time notifications are seamless and efficient.
- Ideal for chat applications, activity feeds, and live updates.

---

### **3. Social Authentication**
- Integrated with **Laravel Socialite**, enabling easy authentication via social platforms like Google, Facebook, GitHub, etc.

---

### **4. IDE Autocompletion and Suggestions**
- Includes **`php artisan ide-helper:generate`** for enhanced IDE autocompletion.
- Improves developer productivity by providing accurate suggestions and type hints.

---

### **5. Debugging with Telescope**
- **Laravel Telescope** is included as a development dependency (`composer require laravel/telescope --dev`).
- Provides insights into requests, exceptions, logs, database queries, and more.

---




### **8. Dockerization**
- Fully dockerized with **Franken** and **RoadRunner** for high performance and concurrency.
- Runs with **Laravel Octane** for blazing-fast request handling.

#### **Docker Commands**
```bash
# Build the Docker image
docker build -t <image-name>:<tag> -f <your-octane-driver>.Dockerfile .

# HTTP mode
docker run -p <port>:8000 --rm <image-name>:<tag>

# Horizon mode
docker run -e CONTAINER_MODE=horizon --rm <image-name>:<tag>

# Scheduler mode
docker run -e CONTAINER_MODE=scheduler --rm <image-name>:<tag>

# Reverb mode
docker run -e CONTAINER_MODE=reverb --rm <image-name>:<tag>

# HTTP mode with Horizon
docker run -e WITH_HORIZON=true -p <port>:8000 --rm <image-name>:<tag>

# Worker mode
docker run \
    -e CONTAINER_MODE=worker \
    -e WORKER_COMMAND="php /var/www/html/artisan foo:bar" \
    --rm <image-name>:<tag>

# Running a single command
docker run --rm <image-name>:<tag> php artisan about
```

---

### **9. Multi-Tenancy (Optional)**
- **Stancl/Tenancy** is not installed but can be added for multi-tenancy support.
- Ideal for SaaS applications where multiple tenants share the same codebase.

---

### **10. Progressive Web App (PWA)**
- Built-in PWA support for modern web applications.
- Enhances user experience with offline capabilities and app-like behavior.

---

### **11. Soft Deletes**
- Models support soft deletes out of the box, allowing you to "softly" delete records without permanently removing them from the database.

---

### **12. Webhooks**
- Includes webhook integration for **Stripe** payments.
- Easily extendable for other third-party services.

---

### **13. Caching**
- High-level caching mechanisms for faster response times.
- Supports popular cache drivers like Redis, Memcached, and more.

---

### **14. Roles and Permissions**
- Built-in role-based access control (RBAC) for managing user permissions.

---

## **Security Enhancements**

The starter kit includes several security features to protect your application:

- **Blocks Malicious Query Strings**: Prevents SQL injection, XSS, and other attacks.
- **Blocks Sensitive Files**: Prevents access to `.env`, `composer.json`, and version control files.
- **Disables Directory Listing**: Stops attackers from browsing your file structure.
- **Disables Server Signature**: Hides server version information.
- **Disables ETags**: Reduces unnecessary validation requests.

---

## **Performance Optimizations**

- **Immutable Cache for Static Assets**: Ensures fast load times for static files.
- **Keep-Alive Enabled**: Reduces latency for persistent connections.
- **Gzip Compression**: Minimizes response sizes for faster delivery.
- **Optimized MIME Types**: Ensures proper handling of modern file formats.

---

## **Logging & Monitoring**

- Logs suspicious requests to track hacking attempts.
- Includes **Laravel Telescope** for monitoring application activity during development.

---


## Some Important Packages 
#### Not installed in repo but usefull to know

The following Laravel packages are included in the project:

### **1. Laravel IDE Helper [Installed]**
- Generates IDE helper files to improve autocompletion and code suggestions.
  ```bash
  php artisan ide-helper:generate
  ```

### **2. Laravel Backup**
- Provides functionality to backup database and files, ensuring safe data storage.
  ```bash
  php artisan backup:run
  ```

### **3. Eloquent Sluggable**
- Automatically generates slugs for models based on the specified fields.
  ```php
  use Cviebrock\EloquentSluggable\Sluggable;
  ```

### **4. Socialite [Installed]**
- Handles authentication via social networks such as Facebook, Google, GitHub, etc.

### **5. Laravel Telescope**
- Provides debugging and monitoring tools for your Laravel application.
  ```bash
  php artisan telescope:install
  ```

### **6. Orchestral Testbench [Installed]**
- Facilitates the testing of Laravel packages by providing a testing environment for packages.

### **7. spatie/laravel-activitylog**
- Tracks activities within your application and logs them for auditing purposes.
  ```php
  activity()->log('User logged in');
  ```

### **8. nWidart/laravel-modules**
- Enables modular development in Laravel by allowing you to break your app into separate modules.
  ```bash
  php artisan module:make Blog
  ```

### **9. archtechx/tenancy**
- Provides multi-tenancy support in Laravel applications, enabling data isolation for different tenants.

### **11. mhasnainjafri/restapikit [Installed]**
- A set of tools to make building RESTful APIs easier within Laravel.

### **11. binarcode/laravel-RestApiKit**
- A set of tools to make building RESTful APIs easier within Laravel.

### **12. ahmedesa/laravel-api-tool-kit**
- Additional tools to simplify and enhance the development of APIs in Laravel.

### **13. spatie/laravel-medialibrary**
- Associate files with Eloquent models and manage file storage with ease.
  ```php
  $user->addMedia($file)->toMediaCollection();
  ```

### **14. spatie/image-optimizer**
- Optimizes images by reducing their size without losing quality.
  ```bash
  php artisan optimize:images
  ```

### **15. beyondcode/laravel-query-detector** *(--dev)*
- Detects problematic database queries and logs slow queries during development.

### **16. spatie/laravel-query-builder**
- A package to help you build and customize queries for Eloquent models easily.

### **17. owen-it/laravel-auditing**
- Audits user activity and data changes across your application.
  ```bash
  php artisan audit:run
  ```

### **18. vcian/laravel-db-auditor** *(--dev)*
- Allows tracking and auditing of changes to the database schema during development.

### **19. Localization**
- Provides support for localization (translations) in your application.
  ```php
  trans('messages.welcome');
  ```

### **20. spatie/laravel-translatable**
- Easily handle multi-language content in your Eloquent models.

### **21. barryvdh/laravel-ide-helper**
- Improves your IDE's ability to understand and auto-complete Laravel's facades and helper functions.

### **22. spatie/laravel-sitemap**
- Easily generate and maintain your site's XML sitemap.
  ```bash
  php artisan sitemap:generate
  ```

### **23. bavix/laravel-wallet**
- Provides wallet functionality within Laravel to store money and perform transactions.

### **24. echolabsdev/prism**
- Package for integrating Large Language Models (LLMs) into your AI applications.

### **25. spatie/laravel-server-side-rendering**
- Adds support for server-side rendering of JavaScript within your Laravel application.



## Development Tools

### **--dev Packages**
Some packages are only installed in the development environment:

- **beyondcode/laravel-query-detector**: Detects slow or inefficient queries during development.
- **vcian/laravel-db-auditor**: Allows schema tracking in development.


## **How to Build and Run the Docker Image**

### **Prerequisites**
- Docker installed on your system.
- Docker Compose installed on your system.
- Laravel Octane, Laravel Horizon, and Laravel Reverb set up.

### **Steps**
1. **Build the Docker Image**:
   ```bash
   docker build -t <image-name>:<tag> -f <your-octane-driver>.Dockerfile .
   ```

2. **Run the Docker Container**:
   - **HTTP Mode**:
     ```bash
     docker run -p <port>:8000 --rm <image-name>:<tag>
     ```
   - **Horizon Mode**:
     ```bash
     docker run -e CONTAINER_MODE=horizon --rm <image-name>:<tag>
     ```
   - **Scheduler Mode**:
     ```bash
     docker run -e CONTAINER_MODE=scheduler --rm <image-name>:<tag>
     ```
   - **Reverb Mode**:
     ```bash
     docker run -e CONTAINER_MODE=reverb --rm <image-name>:<tag>
     ```
   - **HTTP Mode with Horizon**:
     ```bash
     docker run -e WITH_HORIZON=true -p <port>:8000 --rm <image-name>:<tag>
     ```
   - **Worker Mode**:
     ```bash
     docker run \
         -e CONTAINER_MODE=worker \
         -e WORKER_COMMAND="php /var/www/html/artisan foo:bar" \
         --rm <image-name>:<tag>
     ```

3. **Run a Single Command**:
   ```bash
   docker run --rm <image-name>:<tag> php artisan about
   ```

---

## **Why This is the Best Laravel Starter Kit?**

- **Super Secure 🔒**: Blocks known attack vectors and prevents information leaks.
- **Blazing Fast 🚀**: Reduces load times using caching, compression, and Keep-Alive.
- **Fully Optimized ⚡**: Ensures better security headers, performance, and MIME type handling.
- **Tracks Attacks 👀**: Logs malicious attempts for review.

This starter kit makes your Laravel app secure, fast, and efficient!

---

## **Next Steps**

1. Clone the repository.
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Configure your `.env` file.
4. Build and run the Docker container.
5. Start developing your application!

---

If you have any questions or need further assistance, feel free to reach out. Happy coding! 🚀