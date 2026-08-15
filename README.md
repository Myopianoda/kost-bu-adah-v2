# Kost Bu Adah

A web-based boarding house management system built with Laravel.

Kost Bu Adah manages the main boarding house workflow from room availability and tenant registration to booking, rental, billing, payment verification, and PDF receipt generation.

This repository is also being prepared as a portfolio project for **Software Quality Assurance / Software Testing**, including regression testing, bug investigation, security testing, and reproducible demo data.

---

## Features

### Administrator

* Admin authentication
* Dashboard overview
* Manage boarding house units
* Manage tenant data
* Review booking requests
* Approve or reject bookings
* Manage active rentals
* Manage tenant bills
* Verify uploaded payment proof
* Confirm payments
* Download payment receipts as PDF
* Manage expenses
* Export tenant, billing, and expense data
* View reports

### Tenant

* Tenant registration and login
* View available units
* Submit room booking requests
* View tenant dashboard
* Update profile
* View billing information
* Upload payment proof
* Download payment receipt after payment confirmation

---

## Main Application Flow

```text
Tenant Registration
        ↓
View Available Unit
        ↓
Submit Booking
        ↓
Admin Approval
        ↓
Active Rental
        ↓
Bill Generated
        ↓
Upload Payment Proof
        ↓
Pending Verification
        ↓
Admin Confirms Payment
        ↓
Paid
        ↓
PDF Receipt
```

---

## Tech Stack

* PHP 8.4
* Laravel 12
* MySQL 8
* Laravel Blade
* Eloquent ORM
* JavaScript
* Vite
* Composer
* npm

The current version is intended for **local demo and portfolio use**, not production deployment.

---

## Demo Data

A database seeder is included so the application can immediately contain usable demo data after a fresh setup.

Run:

```bash
php artisan migrate:fresh --seed
```

The seeder creates:

* 1 demo administrator
* 4 boarding house units
* 3 demo tenants
* 2 booking records
* 2 rental records
* 3 billing records

The seeded data represents several application states, including:

* Available room
* Room under booking
* Occupied room
* Pending booking
* Approved booking
* Active rental
* Completed rental
* Unpaid bill
* Overdue bill
* Paid bill

> **Warning:** `php artisan migrate:fresh --seed` deletes all existing database tables before recreating them. Use it only on a development or demo database.

---

## Demo Accounts

### Administrator

```text
Email: admin@kostbuadah.test
Password: password
```

Login route:

```text
/login
```

### Tenant Accounts

All demo tenant accounts use:

```text
Password: password
```

Available demo phone numbers:

```text
081200000001
081200000002
081200000003
```

Tenant login:

```text
/penyewa/login
```

Tenant registration:

```text
/penyewa/register
```

These credentials are intentionally created for local demonstration purposes only.

---

## Installation

### Requirements

Make sure the following tools are installed:

* PHP
* Composer
* MySQL
* Node.js
* npm

### 1. Clone the repository

```bash
git clone https://github.com/Myopianoda/kost-bu-adah-v2.git
cd kost-bu-adah-v2
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Configure the database

Create a MySQL database, for example:

```text
kost_bu_adah
```

Then update the database configuration in `.env`.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kost_bu_adah
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the username and password according to your local MySQL configuration.

### 6. Run migrations and seed demo data

```bash
php artisan migrate:fresh --seed
```

### 7. Create the storage symbolic link

```bash
php artisan storage:link
```

### 8. Install frontend dependencies

```bash
npm install
```

### 9. Build frontend assets

```bash
npm run build
```

### 10. Start the application

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

---

## Fresh Setup Validation

The portfolio version of this project has been tested using a completely fresh database.

The following process completed successfully:

```text
Fresh Database
      ↓
Run All Migrations
      ↓
Seed Demo Data
      ↓
Start Application
      ↓
Demo Data Available in UI
```

This validation helps ensure the project does not depend on manually modified database state from an older development environment.

---

## QA & Testing Work

This application is also used as a practical Software QA portfolio.

Testing and investigation performed during project restoration include:

* Core business-flow regression testing
* Payment workflow testing
* Fresh database testing
* Database schema verification
* Authorization testing
* Tenant data-isolation testing
* Bug reproduction
* Root-cause analysis
* Regression validation after fixes

---

## Bug Case Study — PAY-001

### Payment Status Schema Mismatch

**Area:** Billing / Payment

**Severity:** High

### Expected

After a tenant uploads payment proof:

```text
Upload Payment Proof
        ↓
menunggu_verifikasi
        ↓
Admin Confirmation
        ↓
lunas
```

### Actual

Uploading payment proof caused an HTTP 500 database error.

The application attempted to save:

```text
menunggu_verifikasi
```

but the database ENUM did not contain that value.

### Root Cause

Application logic had already introduced the `menunggu_verifikasi` billing state, but the corresponding database migration had not been added.

This caused the application code and database schema to become inconsistent.

### Fix

A new migration was added so the billing status supports:

```text
belum_bayar
menunggu_verifikasi
lunas
terlambat
```

The payment flow was then regression-tested through payment confirmation and PDF receipt generation.

---

## Security Improvements

### Tenant Billing Authorization

A tenant must only be able to access billing records belonging to their own rental.

Authorization was reviewed and improved to prevent a tenant from accessing another tenant's billing information by manually changing an ID in the URL.

### Separate Authentication Flows

Administrator and tenant authentication use separate application flows.

Administrator:

```text
/login
```

Tenant:

```text
/penyewa/login
/penyewa/register
```

---

## Repository Cleanup

The following cleanup and maintenance work has been completed:

* Removed an unused nested Laravel project
* Removed unused test API routes
* Improved `.env.example`
* Updated frontend dependencies
* Resolved reported frontend dependency vulnerabilities
* Verified frontend production build
* Added reproducible demo data
* Verified migrations from a fresh database
* Improved tenant billing authorization

---

## Payment Integration Note

The repository contains previous Midtrans integration code and a notification webhook.

Midtrans is currently **not required for the local portfolio demonstration**.

The main demonstrated payment workflow uses:

```text
Tenant uploads payment proof
        ↓
Admin verifies payment
        ↓
Billing status becomes paid
        ↓
PDF receipt becomes available
```

---

## Project Status

### Completed

* [x] Fresh project restoration
* [x] Administrator authentication
* [x] Tenant authentication
* [x] Unit management
* [x] Tenant management
* [x] Booking workflow
* [x] Rental workflow
* [x] Billing workflow
* [x] Payment-proof upload
* [x] Payment verification
* [x] PDF receipt generation
* [x] Payment schema bug fix
* [x] Tenant billing authorization fix
* [x] Repository cleanup
* [x] Fresh database migration validation
* [x] Demo database seeder

### QA Portfolio Work in Progress

* [ ] Structured test plan
* [ ] Manual test cases
* [ ] Regression checklist
* [ ] Formal bug reports
* [ ] Testing evidence
* [ ] Automated tests

---

## Portfolio Goals

This repository is being developed further to demonstrate practical skills in:

* Software testing
* Understanding business requirements and workflows
* Manual regression testing
* Writing test cases
* Bug reporting
* Root-cause investigation
* Database validation
* Basic authorization/security testing
* Git-based project maintenance
* Reproducible development environments
* Laravel web application development

---

## Repository

GitHub:

```text
https://github.com/Myopianoda/kost-bu-adah-v2
```

---

## Notes

This project was originally developed as an application project and later restored and improved for portfolio purposes.

Current development focuses on making the repository:

* Reproducible from a fresh clone
* Easy to demonstrate
* Easier to review
* Better documented
* Suitable for continued Software QA testing practice
