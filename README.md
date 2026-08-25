# 🛒 Le Bon Coin Clone

A full-stack web application inspired by **Le Bon Coin**, developed as an academic project to demonstrate modern web development concepts, database management, and user authentication.

The platform enables users to create accounts, publish classified advertisements, browse listings, search for products, and manage their own advertisements through an intuitive and responsive interface.

---

## 📌 Overview

This project reproduces the core functionality of a classified advertisements platform while focusing on clean code architecture, responsive design, and secure database interaction.

It was developed to strengthen practical skills in full-stack web development using PHP and MySQL.

---

## 🌐 Live Demo

### 🚀 Interactive Frontend Demo

A fully interactive **static frontend demonstration** of the Le Bon Coin Clone is available online.

<p align="center">

<a href="https://demo-green-phi-88.vercel.app/">
  <img src="https://img.shields.io/badge/🛒%20LEBONCOIN%20CLONE-LIVE%20DEMO-2563EB?style=for-the-badge&logo=vercel&logoColor=white" alt="LeBonCoin Clone Live Demo">
</a>

&nbsp;&nbsp;&nbsp;

<a href="https://demo1-lake-seven.vercel.app/">
  <img src="https://img.shields.io/badge/🚀%20QCM%20ANTI--CHEAT-LIVE%20DEMO-7C3AED?style=for-the-badge&logo=vercel&logoColor=white" alt="QCM Anti-Cheat Live Demo">
</a>

</p>

<p align="center">
  <sub>
    Interactive browser-based demonstrations deployed with Vercel.
  </sub>
</p>


The live demo reproduces the application's main user interfaces and workflows using **HTML5, CSS3, and Vanilla JavaScript**, without requiring a PHP runtime or MySQL database.

### 🎯 Available Demo Features

- 🏠 Homepage and navigation
- 🔍 Advertisement search and category filtering
- 📋 Advertisement listings
- 🖼️ Advertisement detail pages and image galleries
- 👤 User authentication simulation
- 📊 User dashboard
- 📝 Advertisement creation, editing, and deletion
- ❤️ Favorites management
- 💬 Buyer–seller messaging simulation
- 🛡️ Admin dashboard
- 👥 User management and account status simulation
- 📱 Responsive layouts for desktop, tablet, and mobile
- 💾 Client-side state persistence using `localStorage`

### 🔐 Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| **Administrator** | `admin@demo.local` | `Demo1234!` |
| **User** | `student@demo.local` | `Demo1234!` |

> **Demo environment:** Authentication, advertisements, favorites, messaging, and administration features are simulated client-side for demonstration purposes. No production user data is processed.

### 🏗️ Demo Architecture

The live demonstration is intentionally isolated from the PHP/MySQL backend:

```text
demo/
├── index.html      # Demo application interface
├── style.css       # Responsive styling
├── script.js       # SPA logic and interactive features
└── assets/         # Images and static resources
```

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

# Home Page

<p align="center">
<img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/800d0d23-0f44-4b6e-a309-71915e70a520" />
</p>

---

# Login

<p align="center">
<img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/fe2af27f-8dec-4c38-b3a4-c91bd2f6ee37" />
</p>

---

# Registration

<p align="center">
<img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/57f8fe53-8279-44cf-a2a6-4e494d25ac36" />
</p>

---

# Product Listings

<p align="center">
<img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/a90877e3-7342-4360-8dea-2ae65cff4ac3" />
</p>

---

# Advertisement Details

<p align="center">
<img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/cf70beea-cf37-40a7-a8a9-f4a1bf1ae9f1" />
</p>

---

# User Dashboard

<p align="center">
<img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/54e7b74f-1f69-4025-9ca0-3a146c672d98" />
</p>

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
