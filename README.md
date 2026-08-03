# 🛒 Le Bon Coin Clone

A full-stack web application inspired by **Le Bon Coin**, developed as an academic project to demonstrate modern web development concepts, database management, and user authentication.

The platform enables users to create accounts, publish classified advertisements, browse listings, search for products, and manage their own advertisements through an intuitive and responsive interface.

---

## 📌 Overview

This project reproduces the core functionality of a classified advertisements platform while focusing on clean code architecture, responsive design, and secure database interaction.

It was developed to strengthen practical skills in full-stack web development using PHP and MySQL.

---

## ✨ Features

- 🔐 User Registration & Authentication
- 👤 User Profile Management
- 📝 Create, Edit & Delete Advertisements
- 🔍 Search and Browse Listings
- 📂 Category-Based Organization
- 💾 MySQL Database Integration
- 📱 Responsive Design
- 🎨 Clean and Modern User Interface
- ⚡ Dynamic Content Rendering
- 🛡️ Secure Database Queries (PDO)

---

## 🛠️ Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript (ES6)

### Backend
- PHP

### Database
- MySQL

### Development Tools
- MAMP
- phpMyAdmin
- Visual Studio Code
- Git & GitHub

---

## 📂 Project Structure

```text
LeBonCoin-Clone/
│
├── assets/
│   ├── css/style.css       # Modern, responsive, dark-mode ready
│   ├── js/main.js          # Vanilla JS (password strength, auto-hide alerts)
│   └── uploads/            # Secured media storage (.htaccess protected)
├── config/
│   ├── db.php              # PDO connection with strict error handling
│   └── init.php            # Centralized bootstrapping, security headers, helpers
├── includes/
│   ├── header.php          # Dynamic SEO, OG tags, navigation
│   └── footer.php
├── pages/
│   ├── admin/              # Admin-only routes (dashboard, users)
│   ├── annonces/           # CRUD, listing, detail, favorites
│   ├── auth/               # Registration, login, logout
│   └── messages/           # Internal messaging system
├── database.sql            # Normalized schema with indexes and constraints
├── index.php               # Landing page
└── README.md
```

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https:/tarek200614/github.com//leboncoin-clone.git
```

### 2. Navigate to the project

```bash
cd leboncoin-clone
```

### 3. Start your local server

Using MAMP (or XAMPP/WAMP):

- Place the project inside the `htdocs` folder.
- Start Apache and MySQL.

### 4. Import the database

- Open **phpMyAdmin**
- Create a new database
- Import the provided SQL file

### 5. Configure database credentials

Update the database connection settings in the configuration file:

```php
Host
Database Name
Username
Password
```

### 6. Launch the application

Open your browser and navigate to:

```
http://localhost:8888/leboncoin-clone/
```

---

## 📸 Screenshots

> Add screenshots of:

- Home Page
- <img width="572" height="472" alt="home page " src="https://github.com/user-attachments/assets/8c36a9f9-86ea-4f68-91d1-e51746aa6573" />
- Login
- <img width="1920" height="1080" alt="login" src="https://github.com/user-attachments/assets/1a94e216-1bc0-46e1-81eb-43dce18975f5" />
- Registration
- <img width="1920" height="1080" alt="registration" src="https://github.com/user-attachments/assets/4cd233b9-4fab-4ead-af16-2d3625fb4f45" />
- Product Listings
- <img width="1920" height="1080" alt="product-listings" src="https://github.com/user-attachments/assets/ec1e96be-defd-4177-945a-a7e465e2bced" />
- Advertisement Details
- <img width="1920" height="1080" alt="advertisement-details" src="https://github.com/user-attachments/assets/d2a0597f-d72a-4add-be9b-31b2d689254f" />
- User Dashboard
- <img width="1920" height="1080" alt="user-dashboard" src="https://github.com/user-attachments/assets/309a3f86-836f-4e82-968f-e9c299a2f706" />


---

## 🎯 Learning Objectives

This project demonstrates practical experience with:

- Full-Stack Web Development
- CRUD Operations
- User Authentication
- Session Management
- Responsive Web Design
- Database Design
- PHP & MySQL Integration
- Secure Backend Development

---

## 🔮 Future Improvements

- Product Image Upload
- Advanced Search Filters
- Favorites System
- Private Messaging
- Location-Based Search
- Notifications
- Admin Dashboard
- REST API
- Password Recovery
- Email Verification

---

## 👨‍💻 Author

**Abderrahmane Tarek MEGHARI**

AI & Data Science Student  
ECE Paris

- GitHub: https://github.com/tarek200614
- LinkedIn: https://www.linkedin.com/in/abderrahmane-tarek-meghari
- Email: meghariabderrhmanetarek@gmail.com

---

## 📄 License

This project was developed for educational purposes.

Feel free to explore the code and use it as inspiration for learning web development.

---

## ⭐ Acknowledgments

This project was created as part of my academic journey to strengthen my knowledge of PHP, MySQL, JavaScript, and modern web development practices.

It is inspired by the functionality of **Le Bon Coin** and was developed exclusively for educational purposes.
