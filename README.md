# 🏨 Simple Hotel Management System  

A Laravel-based **Hotel Management System** that allows users to book rooms, manage reservations, and handle hotel operations efficiently.  

![Hotel Management](public/images/belerick.png)

---

## 🚀 Features  
✅ User Registration & Login (Authentication)  
✅ Room Booking & Reservation Management  
✅ Admin Panel for Managing Rooms & Users  
✅ Payment Integration (Optional)  
✅ Search & Filtering for Available Rooms  
✅ Laravel Blade Templates for UI  

---
## Gibo nganay database sa phpmyadmin
    create database hotel_mgmt
## 🛠 Installation Guide  

Follow these steps to set up the project:  

### 1️⃣ Clone the Repository  

    git clone https://github.com/IvanNasaktan/simple_hotel_management_system.git

### then open the folder in vscode with this command:
    cd simple_hotel_management_system


#### 2. Install PHP Dependencies: Use Composer to install the required PHP dependencies for Laravel

    composer install

#### 3. Install JavaScript Dependencies: Install the required JavaScript packages (Bootstrap, etc.) using npm

    npm install

#### 4. Set Up Environment Variables: Create a .env file by copying the example file

    cp .env.example .env

#### 5. Open the .env file and update the following variables to match your local environment

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=hotel_mgmt
    DB_USERNAME=root
    DB_PASSWORD=

### 6. Go to your xampp and create a database named "siyenshopdb", make sure your xampp server is running

#### 7. Run the Artisan Key Generate Command

    php artisan key:generate

### 8. Run the artisan migrate to migrate the database to your local machine

    php artisan migrate

### 9. Run the database seed command to populate your database

    php artisan db:seed

#### 10. Run the Application: Finally, run the application locally

    php artisan serve

The application will be available at http://localhost:8000
