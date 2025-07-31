# WE4U – Elder Care System

WE4U is a web-based elder care management system designed to bring careseekers, caregivers, and consultants into a single, organized platform.

It simplifies the process of finding and booking caregivers or consultants, managing multiple elder profiles, and coordinating care sessions with built-in chat, scheduling, and document sharing.

With features like moderated registrations, medical information tracking, and real-time communication, WE4U ensures transparency, safety, and personalized care. The system is built using the MVC architecture and focuses on role-based access to support smooth interactions between all user types.

## Features

### Core Functionalities

- **Search & Booking**  
  Careseekers can search for and book caregivers or consultants based on specific needs.

- **Multiple Elder Profiles**  
  A single careseeker account can manage multiple elder profiles.

- **Online Payments**  
  Secure payment processing using Stripe integration.

- **Consultation Sessions**  
  Once a consultant accepts a request, a session is created where:
  - Prescriptions, documents, and videos can be shared.
  - Session details are organized for better care planning.

- **Real-Time Chat**  
  Instant messaging between careseekers, caregivers, and consultants.

- **Moderated Registration**  
  - Caregivers and consultants must submit documents during registration.
  - Moderator reviews and may schedule interviews before approval.

- **Custom Scheduling Systems**  
  Separate schedulers for caregivers and consultants to manage their availability.

- **Cancellation Logic**  
  Role-based cancellation policies to handle request cancellations.

- **Medical Information Management**  
  Elders' health data is recorded and viewable for more informed care.

- **Rating & Review System**  
  Feedback system for maintaining service quality.

- **Role-Based Login System**  
  Secure login system for five roles:
  - Admin
  - Moderator
  - Careseeker
  - Caregiver
  - Consultant

- **Request Tracking & History**  
  Track and view history of all service requests.

- **Email and Notification System**  
  Sends updates on request status, session events, and alerts.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript  
- **Backend**: PHP (no frameworks)  
- **Database**: MySQL  
- **Architecture**: MVC

## How to Run the Project (Using XAMPP)

### Prerequisites

- XAMPP installed on your local machine

### Setup Steps

1. **Start XAMPP**  
   - Open XAMPP Control Panel  
   - Start **Apache** and **MySQL**

2. **Clone or Download the Repository**  
   Place the project folder inside:  
   `C:\xampp\htdocs\`

3. **Import the Database**  
   - Open `phpMyAdmin` (visit `http://localhost/phpmyadmin`)
   - Create a new database (e.g., `we4u`)
   - Import the provided `.sql` file inside the `database` folder

4. **Configure Database Connection**  
   - Go to the project folder  
   - Open the database config file (usually located at `config/db.php` or similar)  
   - Set database name, user, and password (usually root with no password for local XAMPP)

5. **Run the Application**  
   - Open your browser  
   - Visit `http://localhost/we4u/` (replace `we4u` with your folder name if different)

## Contributors

- [Akith Jayalath](https://github.com/akithjayalath)
- [Nadun Hasalanka](https://github.com/NadunHasalanka)
- [Tanuri Mandini](https://github.com/tanurims) 
- [Senesh Gamage](https://github.com/seneshgamage)

## Video Demonstration

Watch the full project in action:  
[WE4U Video Demo](https://youtu.be/LkMQkxaO35Q)

