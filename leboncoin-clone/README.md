# 🛒 Le Bon Coin Clone

A full-stack web application inspired by **Le Bon Coin**, developed as an academic project to demonstrate modern web development concepts, database management, user authentication, and classified advertisement management.

The platform enables users to create accounts, publish classified advertisements, browse listings, search for products, manage favorites, exchange private messages, and administer users through an intuitive and responsive interface.

---

## 📌 Overview

This project reproduces the core functionality of a classified advertisements platform while focusing on clean code architecture, responsive design, secure database interaction (PDO), and complete administrative management.

It was developed to strengthen practical skills in full-stack web development using PHP, MySQL, CSS3, and Vanilla JavaScript.

---

## 🌐 Live Demo

> **🚀 Try the application live in your browser**

**[▶️ Launch Live Demo](YOUR_LIVE_DEMO_URL)**

The public live demo provides an interactive client-side preview of the frontend and user workflows (located in `demo/`), hosted statically on GitHub Pages without requiring a local PHP/MySQL server environment.

The complete **PHP 8+ and MySQL backend application** remains fully functional in the repository and can be deployed to any PHP hosting environment using the deployment instructions below.

### 🔑 Demo Accounts

For demonstration and testing purposes, pre-configured accounts are available:

- **Admin Account**:
  - **Email**: `admin@demo.local`
  - **Password**: `Demo1234!`
- **User / Student Account**:
  - **Email**: `student@demo.local`
  - **Password**: `Demo1234!`

### ⚡ Live Demo Features

- 🏠 **Landing Page**: Recent ads listing and interactive navigation
- 🔐 **Authentication**: User registration, secure login, session management, and logout
- 📝 **Advertisement CRUD**: Create ads with multi-photo upload, edit, delete, and view details
- 🔍 **Search & Filter**: Search ads by keyword and filter by category
- ⭐ **Favorites**: Save and manage favorite listings
- 💬 **Messaging**: Internal seller-buyer communication system
- 🛡️ **Admin Dashboard**: User management, account suspension/activation, and platform metrics
- 📱 **Responsive Design**: Optimized for desktop (1920px), tablet (768px), and mobile (375px)

---

## ✨ Features

- 🔐 **User Registration & Authentication**: Bcrypt password hashing, session security, CSRF protection
- 👤 **User Dashboard & Profiles**: Individual listing management and message inbox
- 📝 **Create, Edit & Delete Advertisements**: Multi-image upload, image validation, category tagging
- 🔍 **Search and Browse Listings**: Real-time category filtering and text search
- ⭐ **Favorites System**: Save favorite listings for quick access
- 💬 **Internal Messaging**: Send and receive private messages per advertisement
- 📂 **Category Organization**: Structured categories with relational SQL constraints
- 🛡️ **Admin Management**: View stats, suspend/activate user accounts
- 📱 **Responsive Interface**: Pure Vanilla CSS layout with flexbox and CSS grid
- ⚡ **Dynamic Content**: PDO prepared statements preventing SQL injection

---

## 🛠️ Technologies Used

### Frontend
- HTML5 & Semantic Elements
- CSS3 (Vanilla CSS with Design Tokens & CSS Variables)
- JavaScript ES6 (Vanilla JS)

### Backend
- PHP 8+ (PDO, Session Security, CSRF Tokens, Image Upload Validation)

### Database
- MySQL / MariaDB (Normalized Relational Schema with Foreign Keys & Indexes)

### Server & Security
- Apache Web Server (`.htaccess` security headers & route protection)
- HTTPS / TLS Ready

---

## 📂 Project Structure

```text
LeBonCoin-Clone/
│
├── assets/
│   ├── css/style.css       # Responsive design system & component styles
│   ├── js/main.js          # Vanilla JS (password strength, alert auto-dismiss)
│   └── uploads/            # Media storage protected by .htaccess
├── config/
│   ├── db.php              # Environment-aware PDO connection
│   ├── init.php            # Centralized bootstrapping, security headers & URL helper
│   └── style.css           # Fallback utility styles
├── includes/
│   ├── header.php          # Dynamic SEO, navigation bar & unread message badge
│   └── footer.php          # Global footer component
├── pages/
│   ├── admin/              # Admin routes (dashboard, user management)
│   ├── annonces/           # Advertisement CRUD (create, view, edit, delete, list)
│   ├── auth/               # Authentication (login, register, logout)
│   ├── favoris/            # Favorites management
│   └── messages/           # Internal messaging system
├── .env.example            # Environment configuration template
├── .htaccess               # Apache web server configuration & security headers
├── database.sql            # Normalized schema with seed data & demo accounts
├── seed_database.php       # Database automated seeder script
├── index.php               # Application landing page
└── README.md
```

---

## 🚀 Deployment

Follow these steps to deploy the application on standard PHP/MySQL web hosting (cPanel, Shared Hosting, VPS, or Apache/Nginx):

### 1. Upload Project Files
Upload all files from the deployment package to your web server document root (e.g. `public_html/` or `htdocs/`).

### 2. Create MySQL Database
Log into your MySQL database server (via phpMyAdmin, cPanel, or CLI) and create a new database:
```sql
CREATE DATABASE leboncoin_clone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Import `database.sql`
Import the provided `database.sql` file into your database to create tables, foreign keys, indexes, categories, default ads, and demo accounts:
```bash
mysql -u your_username -p leboncoin_clone < database.sql
```
*(Or use phpMyAdmin **Import** tab).*

### 4. Configure Database Credentials
Copy `.env.example` to `.env` (or set server environment variables):
```env
APP_ENV=production
BASE_URL=https://your-domain.com/

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=leboncoin_clone
DB_USER=your_db_user
DB_PASS=your_db_password
```
*(Note: If `.env` is omitted, `config/db.php` will default to local fallback values).*

### 5. Configure PHP Version
Ensure your hosting server runs **PHP 8.0 or higher** with `pdo_mysql` and `fileinfo` extensions enabled.

### 6. Configure HTTPS & Apache
Ensure Apache `mod_rewrite` and `mod_headers` are enabled. The included `.htaccess` file enforces security headers and protects `.env` and `database.sql` files.

### 7. Launch Application
Navigate to your domain in your web browser:
```
https://your-domain.com/
```

### 8. Log In with Demo Accounts
Use the pre-seeded demo accounts:
- **Admin**: `admin@demo.local` / `Demo1234!`
- **User**: `student@demo.local` / `Demo1234!`

---

## 📸 Screenshots

- Home Page
- <img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/800d0d23-0f44-4b6e-a309-71915e70a520" />
- Login
- <img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/fe2af27f-8dec-4c38-b3a4-c91bd2f6ee37" />
- Registration
- <img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/57f8fe53-8279-44cf-a2a6-4e494d25ac36" />
- Product Listings
- <img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/a90877e3-7342-4360-8dea-2ae65cff4ac3" />
- Advertisement Details
- <img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/cf70beea-cf37-40a7-a8a9-f4a1bf1ae9f1" />
- User Dashboard
- <img width="1672" height="941" alt="image" src="https://github.com/user-attachments/assets/54e7b74f-1f69-4025-9ca0-3a146c672d98" />

---

## 🎯 Learning Objectives

This project demonstrates practical experience with:

- Full-Stack Web Development
- Complete CRUD Operations
- Secure User Authentication & Session Management
- Responsive Web Design without External Frameworks
- Database Normalization & Foreign Key Constraints
- PHP 8 & MySQL Integration
- Secure Backend Development (PDO, CSRF, XSS, File MIME validation)

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

---

## ⭐ Acknowledgments

This project was created as part of an academic journey to master PHP, MySQL, JavaScript, and modern web development best practices. Inspired by **Le Bon Coin**.
