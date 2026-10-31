# College Transport Management System

## Project Overview

The College Transport Management System is a web-based application developed to computerize and simplify the management of college transportation facilities.

The system provides separate interfaces for students and administrators. Students can register, log in, view available buses and routes, apply for college transport, view registration details, record payment information, and print payment receipts.

Administrators can manage drivers, buses, routes, trips, transport registrations, payment records, and access transport-related reports.

The application was developed and tested in a local XAMPP environment using PHP and MySQL.

## Technologies Used

### Frontend
- HTML
- CSS
- JavaScript

### Backend
- PHP

### Database
- MySQL

### Development Environment
- XAMPP
- Apache
- phpMyAdmin

## Main Features

### Student Module

- Student registration
- Student login and authentication
- Student dashboard
- View available buses
- View transport routes
- Transport registration
- View registration details
- Payment record management
- Payment receipt generation
- Password change
- Logout

### Admin Module

- Administrator login
- Student record viewing
- Driver management
- Bus management
- Route management
- Trip management
- Transport registration management
- Payment management
- Transport reports
- Logout

## Database

The system uses MySQL as its relational database management system.

The database contains eight main tables:

- Admin
- Student
- Driver
- Bus
- Route
- Trip
- Transport Registration
- Payment

Primary keys, foreign keys, unique constraints, and required-field constraints are used to maintain data integrity and relationships between the tables.

## Architecture

The project follows a three-tier architecture:

1. **Presentation Layer** — HTML, CSS and JavaScript
2. **Business Logic Layer** — PHP
3. **Data Layer** — MySQL

The application follows a client-server model where the browser interacts with the PHP application, which processes requests and communicates with the MySQL database.

## Security & Validation

The project includes:

- Separate student and administrator authentication
- Session-based access control
- Password hashing
- Input validation
- Duplicate record prevention
- Primary and foreign key constraints
- Database relationship validation
- Protected pages for authenticated users

## Reports

The administrator reports section provides:

- Dashboard summaries
- Transport registration reports
- Payment reports
- Trip reports

These reports help administrators review transport-related information stored in the database.

## Testing

The application was tested using:

- Unit Testing
- Integration Testing
- System Testing

Major workflows such as registration, login, transport registration, payment records, receipts, administrative management, reports, and protected-page access were tested.

The project was tested repeatedly after debugging and code improvements in the local XAMPP environment.

## Running the Project Locally

### Requirements

- XAMPP
- Apache
- MySQL
- PHP
- Web Browser

### Setup

1. Install and start XAMPP.
2. Start **Apache** and **MySQL**.
3. Copy the project folder into the XAMPP `htdocs` directory.
4. Open phpMyAdmin.
5. Create/import the `transport_management` database using:

```text
Database/transport_management.sql
```

6. Configure the database connection according to your local MySQL setup.
7. Open the project through the local Apache server in a web browser.

## Limitations

- The current version runs in a local XAMPP environment.
- Real-time GPS bus tracking is not implemented.
- There is no dedicated Android or iOS application.
- The administrator can view student records, but full student record CRUD is not implemented.
- Payment information is recorded in the system, but there is no external online payment gateway.

## Future Enhancements

Possible future improvements include:

- Online payment gateway integration
- Real-time GPS bus tracking
- SMS and email notifications
- Mobile application
- Automated route planning
- QR-code-based transport passes
- Automated trip attendance
- Advanced dashboards and reports
- Cloud deployment

## Author

**Yashika Bhatia**

BCA Project — College Transport Management System
