# Web-Based Farm Resource Management System with Machinery Scheduling and Inventory Monitoring for PSARECO

A modern web-based farm resource management system developed for the **Polot Somagongsong Agrarian Reform Cooperative (PSARECO)**. The system is designed to streamline cooperative operations through centralized record management, online machinery scheduling, fertilizer and pesticide sales management, inventory monitoring, and automated reporting.

It provides a centralized and user-friendly platform that helps PSARECO manage farmer-member records, cooperative users, farm machinery, agricultural supplies, rental requests, sales transactions, and inventory levels efficiently.

---

## 🚀 Key Features

* 👨‍🌾 **Farmer-Member Management**
* 👤 **Cooperative User Management**
* 🚜 **Farm Machinery Management**
* 📅 **Online Machinery Scheduling and Booking**
* ✅ **Machinery Rental Request Approval**
* 🗓️ **Calendar-Based Machinery Availability Monitoring**
* 📋 **Machinery Rental History and Borrowed Equipment Tracking**
* 🌱 **Fertilizer and Pesticide Management**
* 🛒 **Fertilizer and Pesticide Sales Management**
* 📦 **Real-Time Inventory Monitoring**
* 🔄 **Automatic Stock Deduction**
* ⚠️ **Low-Stock Alert Notifications**
* 📊 **Inventory and Sales Reports**
* 🧾 **Centralized Record Management**

---

## 🎯 System Objectives

The main objective of this system is to develop a Web-Based Farm Resource Management System with Machinery Scheduling and Inventory Monitoring for the Polot Somagongsong Agrarian Reform Cooperative (PSARECO) that improves record management, machinery scheduling, sales and inventory monitoring, and overall operational efficiency through a centralized web-based platform.

Specifically, the system aims to:

### 1. Record Management Module
* **1.1** Manage and maintain records of farmer-members and cooperative users.
* **1.2** Manage records of farm machinery, including machinery information and availability status.
* **1.3** Manage records of fertilizers and pesticides.
* **1.4** Maintain centralized and organized records to support efficient monitoring and reporting.

### 2. Machinery Scheduling Management Module
* **2.1** Implement an online booking system for farm machinery rental requests.
* **2.2** Allow authorized personnel to review and approve machinery rental requests based on equipment availability.
* **2.3** Provide a calendar-based machinery scheduling and availability monitoring system.
* **2.4** Record machinery rental history and monitor the status of borrowed equipment.

### 3. Sales and Inventory Management Module
* **3.1** Record fertilizer and pesticide sales transactions.
* **3.2** Monitor inventory levels through real-time stock updating and automatic stock deduction.
* **3.3** Provide low-stock alert notifications to support timely inventory replenishment.
* **3.4** Generate inventory and sales reports to support monitoring, analysis, and decision-making.

---

## 🧩 System Modules

### 📁 Record Management Module
Provides a centralized repository for managing important cooperative records, including:
* Farmer-member profiles
* Cooperative user accounts
* Farm machinery records
* Machinery availability status
* Fertilizer records
* Pesticide records
* Other relevant agricultural resource information

### 🚜 Machinery Scheduling Management Module
Facilitates the scheduling and monitoring of farm machinery used by cooperative members. Key functions include:
* Machinery rental requests & online booking
* Request review and approval
* Machinery availability checking
* Calendar-based scheduling
* Rental status monitoring & borrow/return tracking
* Machinery rental history

### 📦 Sales and Inventory Management Module
Manages the cooperative's fertilizer and pesticide inventory and sales transactions. Key functions include:
* Fertilizer and pesticide product management
* Stock-in, real-time inventory updates, and stock monitoring
* Sales transaction recording with automatic stock deduction
* Low-stock notifications
* Sales and inventory reporting

---

## 🛠️ Technology Stack

| Component | Technology |
| :--- | :--- |
| **Backend Framework** | Laravel |
| **Language** | PHP |
| **Database** | MySQL |
| **Templating Engine**| Blade |
| **Styling / UI** | Bootstrap / CSS |
| **Client-Side** | JavaScript |
| **Asset Bundler** | Vite |
| **Local Dev Env** | Laragon |

---

## 💻 Getting Started

Follow these steps to set up the system:

###

1. **Clone the repository**

    ```sh
    git clone https://github.com/DJmistMAGO/psareco.git
    cd psareco
    ```

2. **Install PHP dependencies**

    ```sh
    composer install
    ```

3. **Install Node.js dependencies**

    ```sh
    npm install
    ```

4. **Copy the example environment file and set your configuration**

    ```sh
    cp .env.example .env
    ```

5. **Generate the application key**

    ```sh
    php artisan key:generate
    ```

6. **Run database migrations**

    ```sh
    php artisan migrate
    ```

7. **Start the development server**

    ```sh
    php artisan serve
    ```

8. **Compile frontend assets**

    ```sh
    npm run dev
    ```

9. **Link Storage**

    ```sh
    php artisan storage:link
    ```

Now you can access the application at `http://localhost:8000`.

---

### Or if using Laragon

If you are using Laragon, follow these steps:

1. Place the cloned `psareco` folder inside your Laragon `www` directory (e.g., `C:\laragon\www\psareco`).
2. Start Laragon and ensure Apache/Nginx and MySQL are running.
3. Open a terminal in the project directory and run:

    ```sh
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    npm run dev
    php artisan storage:link
    ```

4. Visit your project in the browser at `http://psareco.test.com` (or the domain Laragon assigns).

