# Scandiweb Full Stack Developer assessment - Backend

This repository contains the backend of the Scandiweb Full Stack Developer assessment. It is a PHP-based application using GraphQL and MySQL to handle product data and cart functionality.

## **Technologies Used**

- PHP 8.1+
- MySQL 5.6
- GraphQL
- Composer (for dependency management)

## **Installation and Setup**

### **1. Clone the Repository**
```sh
git clone https://github.com/sadkingo/scandiweb-task.git
cd scandiweb-task/backend
```

### **2. Configure the Database**
1. Create a MySQL database named `scandiweb`.
2. Import the provided `data.json` file into the database.
3. Update the database configuration in `/.env` with your database credentials.

### **3. Install Dependencies**
Ensure you have Composer installed, then run:
```sh
composer install
```

### **4. Start the Backend Server**
Configure your web server (Apache, Nginx, or PHP's built-in server) to serve the PHP application. If using PHP's built-in server, run:
```sh
php -S localhost:8000 -t public
```

## **Usage**
After setting up the backend:
- The API will be available at `http://localhost:8000/graphql`.
- The frontend can interact with this backend to fetch product data and manage the cart.

