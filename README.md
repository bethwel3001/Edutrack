# 📚 EduTrack Attendance Dashboard

A modern, responsive PHP-based attendance tracking dashboard for students and staff. This system allows users to log in, mark attendance, and view their 30-day attendance history in a sleek, animated interface. Designed with simplicity and clarity in mind.

## 🔧 Features

- ✅ Login authentication (students/staff)
- 📅 View 30-day attendance summary
- 🖨️ Print-friendly reports with user name & role
- 🖥️ Responsive layout for mobile & desktop
- 🎨 Animated welcome and report sections
- 🎯 Inline CSS & JavaScript for portability

## 📋 Requirements

- PHP 7.4+
- MySQL/MariaDB
- Web server (Apache/Nginx or XAMPP/Laragon for local dev)

## 🚀 Usage

1. Clone/download the project.
2. Configure your database in `includes/config.php`.
3. Import the attendance schema (if provided).
4. Run on local server (`localhost/project-folder`).
5. Register/login to mark and view attendance.

## 📄 Print Report

The dashboard includes a "Print Report" button that:
- Formats the report for printing
- Automatically includes the user’s name and role
- Hides navigation and buttons in the printed version

## 👤 User Roles

- **Student**: View & mark attendance
- **Staff**: Same as students (customize as needed)

## 📌 Customization

- Colors and fonts can be edited in the `:root` variables in `<style>`
- Adjust attendance logic in `dashboard/index.php`
- Extend to include admin controls or export as PDF

---
## Launch
- On the broswer type
```bash
http://localhost/attendance/public/index.php
```
Made with ❤️ using PHP, Javascript and plain CSS
