# Real Estate Agency Portal


A web-based real estate agency platform built with PHP and MySQL. The system supports three user roles — agent, buyer, and renter — with secure authentication, role-based access control, and full CRUD functionality for property listings, inquiries, favorites, and transactions.

---

## Features

- Secure registration and login with bcrypt password hashing
- Role-based dashboards for agents, buyers, and renters
- Agents can add and edit property listings
- Buyers and renters can browse properties, save favorites, and submit inquiries
- Transaction recording with automatic property status updates via database trigger
- MySQL stored procedures, a database view, and a trigger

---

## Tech Stack

- **Backend:** PHP 8.0+
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS
- **Local Server:** XAMPP (Apache + MySQL)
- **Database Tool:** MySQL Workbench

---

## Project Structure

```
real_estate_portal/
├── config/
│   └── config.php              # Database connection constants and session start
├── classes/
│   ├── Database.php            # PDO connection class
│   └── RealEstateDatabase.php  # All database methods
├── includes/
│   ├── auth.php                # requireLogin() and requireRole() helpers
│   ├── header.php              # Shared HTML header and navigation
│   └── footer.php             # Shared HTML footer
├── assets/
│   └── style.css              # Site-wide stylesheet
├── sql/
│   └── real_estate_portal_db.sql  # Full database schema and sample data
├── index.php                  # Homepage
├── login.php                  # Login page
├── register.php               # Registration page
├── logout.php                 # Session destroy
├── dashboard.php              # Role-specific dashboard
├── properties.php             # Browse all listings
├── property_details.php       # Single property view
├── add_property.php           # Agent: add new listing
├── edit_property.php          # Agent: edit existing listing
├── submit_inquiry.php         # Buyer/Renter: send inquiry
└── favorites.php              # Buyer/Renter: saved properties
```


### Requirements
- XAMPP (or WAMP / MAMP / Laragon)
- PHP 8.0 or higher
- MySQL / MariaDB
