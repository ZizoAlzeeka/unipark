
---

**Subject: Development of a University Parking Management System (UniPark) using Laravel & MySQL**

**Hello,**

We are looking to develop a comprehensive web application, "UniPark," designed to manage and organize parking facilities at a university campus. The primary goal is to create an efficient system that allows authorized users (students and staff) to reserve parking spots electronically, while providing administrators with powerful tools for monitoring and management. The project will follow an Agile development methodology, and we are seeking a professional developer to execute the project based on the detailed requirements below.

**1. Project Overview**
UniPark is a web application to be built on the Laravel framework with a MySQL database. It will enable authenticated users to log in with their university email, view an interactive map of parking spots, reserve a spot in advance, and manage their bookings. The system will also feature a dedicated admin panel for complete system management.

**2. User Roles & Permissions**
A Role-Based Access Control (RBAC) system must be implemented to support the following roles:

*   **Standard User (Student/Staff):**
    *   Register for an account and authenticate using their university email.
    *   View the interactive map with real-time parking spot statuses (Available, Reserved, Occupied).
    *   Book a parking spot for a specific time frame (defining start and end times).
    *   View and cancel their active reservations.

*   **System Administrator (Admin):**
    *   Inherits all permissions of a Standard User.
    *   Access to an admin dashboard to manage users (create, update, delete).
    *   Full CRUD (Create, Read, Update, Delete) functionality for parking zones and individual spots.
    *   View vehicle entry/exit logs.
    *   Generate statistical reports (e.g., most used spots, peak hours).

**3. Core Features Required**

*   **Authentication Module:**
    *   Secure user registration, login, and password reset functionality.
    *   Email validation to ensure it belongs to the university's domain (e.g., `@uoh.edu.sa`).

*   **Parking Management Module:**
    *   A full CRUD interface for the administrator to manage parking zones and individual spots.
    *   Ability to define spot properties, such as spot number and type (e.g., Standard, Staff, Disabled).

*   **Reservation Module:**
    *   An intuitive booking interface allowing users to select a spot from the map and define the reservation period.
    *   A robust conflict detection system to prevent double-booking.
    *   Functionality for users to cancel a reservation before its start time.

*   **Real-time Status Display:**
    *   The parking map must update dynamically via AJAX or a similar technology to reflect new reservations, cancellations, and status changes without requiring a page reload.

*   **Logging Module:**
    *   Log all vehicle entry and exit events. (For the initial version, this can be simulated via manual input in the admin panel).

*   **Admin Dashboard & Reporting:**
    *   Display key metrics (e.g., total spots, available spots, current reservations).
    *   Generate and export reports (CSV/PDF) on parking utilization and user activity.

**4. Technical Stack**
*   **Back-end:** Laravel (latest stable version).
*   **Front-end:** Blade templates with Bootstrap.
*   **Database:** MySQL.
*   **Version Control:** Git. A GitHub repository will be provided for the project.

**5. Expected Deliverables**
1.  The complete, well-documented source code for the application.
2.  A database schema file, along with all necessary Laravel migrations and seeders.
3.  A `README.md` file with clear instructions for setting up and running the project locally.
4.  Deployment of the application to a staging server for testing and review purposes.

**Work Plan:**
The project is divided into sprints as outlined in the attached project proposal document. We expect the development to adhere to the proposed timeline as closely as possible.

We are confident that your expertise will be a valuable asset to this project. Please do not hesitate to ask any questions you may have.