# Faiza Host Technical Services (FHTS) Management System

A production-ready Laravel 12.x + Livewire application for managing labor supply operations, worker attendance, payroll calculation, and project-based tax invoice generation.

## Features
- **Settings Module**: Manage company information, logos, VAT rates.
- **Projects Module**: Define construction projects and labor categories with hourly billing rates.
- **Workers Module**: Register workers and manage internal pay rates.
- **Attendance Module**: Mobile-friendly monthly attendance tracking grid.
- **Salary Calculation**: Generate and export worker salary reports via Cloudflare API PDF generation.
- **Invoice Generation**: Automated generation of tax invoices for client projects with PDF export capability.

## Developer Credit
- **Developer:** Arman Azij
- **Facebook:** [https://fb.com/armanaazij](https://fb.com/armanaazij)
- **GitHub Profile:** [https://github.com/rejaulalomkhan](https://github.com/rejaulalomkhan)
- **Repository Link:** [https://github.com/rejaulalomkhan/workers-management](https://github.com/rejaulalomkhan/workers-management)

## Requirements
- PHP 8.2+
- Composer
- MySQL/MariaDB

## Installation
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database setup
4. Run `php artisan key:generate`
5. Map `CLOUDFLARE_API_TOKEN` and `CLOUDFLARE_ACCOUNT_ID` in your `.env` for PDF printing.
6. Run `php artisan migrate --seed`
7. Run `npm install && npm run build`
8. Run `php artisan serve`
