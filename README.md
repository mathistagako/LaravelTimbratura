Timbratura – Employee Work Time Tracking Application

Timbratura is a web-based application for tracking employee work hours through check-in/check-out (timbratura) records. The system is split into a backend API (LaravelTimbratura) and a frontend app (apptimbratura), working together to allow employees to log their attendance and managers to review time records. Both parts are currently in active development (work-in-progress), and certain features (like secure authentication) are not yet fully implemented.

Backend (LaravelTimbratura)
Overview

LaravelTimbratura is a Laravel-based backend project that provides a RESTful API for the time tracking application. It handles data management, business logic, and database interactions for employee records and attendance logs. This backend validates user login (currently via a simple password check) and stores clock-in/clock-out timestamps in a database. Note: Authentication is rudimentary at this stage – passwords are not hashed or encrypted in this early version, which is a known security issue to be addressed.

Features

Employee Records: Maintains employee data (e.g. name, email or ID, and a plaintext password for login).

Login API: Provides an endpoint to verify user credentials. Currently, it simply checks if the provided password matches the stored password for the user (no hashing or token generation yet).

Clock-In/Clock-Out API: Endpoints to record when an employee starts (clock-in) and ends (clock-out) a work session. Each record includes a timestamp (and possibly an employee identifier) stored in the database.

Attendance Logs: Stores a history of all check-in and check-out events. These could be used to compute work hours or generate reports (future feature).

Basic Validation: Includes input validation for API requests (e.g. ensuring required fields like password or user ID are present) and uses Laravel’s built-in request handling and validation features.

Tech Stack & Dependencies

Framework: Laravel (PHP framework) – leveraging Laravel’s MVC structure, Eloquent ORM for database interactions, and RESTful routing for API endpoints. (Make sure you have the appropriate PHP version installed as required by the Laravel version used, e.g. PHP 8+)

Database: MySQL or MariaDB (configurable via environment). The app uses Eloquent models and migrations to define the schema for employees and attendance records.

Key Libraries: Default Laravel packages (HTTP routing, Eloquent, etc.) and any Laravel-provided middleware. No third-party Laravel packages are heavily used yet (aside from those Laravel includes by default). For example, Laravel’s built-in CORS support is likely used to allow the frontend to communicate with the API from a different origin.

Date/Time Handling: PHP’s Carbon library (included with Laravel) is used for managing timestamps (for example, recording the current date/time for a clock-in event).

Security: Laravel’s authentication scaffolding is not yet integrated. Passwords are currently stored in plain text – which will be replaced with Laravel’s Hash utility for secure password hashing in a future update
laravel.com
. For now, be aware this is only for development/testing purposes.

Installation & Setup

Clone the repository: Clone the LaravelTimbratura backend repository to your local environment.

Install dependencies: Run composer install in the project root to install PHP/Laravel dependencies.

Environment setup: Copy .env.example to .env in the project root. Open the .env file and configure the settings:

Database credentials: Set your DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD for the MySQL database. Ensure a database with the given name exists.

App key: Generate an application key by running php artisan key:generate (this fills in the APP_KEY in your .env).

(Other .env settings like APP_URL or CORS settings can be adjusted as needed, e.g. to match the frontend’s URL during development.)

Run migrations: Execute php artisan migrate to create the necessary tables (e.g. employees, attendance logs) in your database. This uses Laravel’s migration files included in the repository to set up the schema.

Seeding initial data (optional): If provided, you can run php artisan db:seed to insert sample data (for example, a default admin/user account for testing). If no seeder is provided, you’ll need to manually create at least one employee record in the database with a password to be able to log in from the frontend.

Launch the development server: Start the Laravel server with php artisan serve. By default it will run at http://localhost:8000. (If you prefer, you can configure a local Apache/Nginx or use Laravel Valet/Sail for a more advanced setup.)

API Access: Once running, the API endpoints will be available. For example, an endpoint like POST /api/login might be used by the frontend to verify credentials, and POST /api/clock-in to record a check-in. (Refer to the code or documentation below for exact routes.)

Usage & API Endpoints

After setup, the backend will listen for API calls from the front-end or any HTTP client. Some core API endpoints (and their expected behavior) include:

POST /api/login – Verify an employee’s login credentials. The frontend sends the user’s identifier (e.g. email or employee ID) and password; the backend checks them against the database. On success, it may start a session or simply return a success response (e.g. a boolean or user data). Note: Without a full auth system, there is no JWT or session token implemented yet – the frontend must handle the logged-in state on its own for now.

POST /api/clock-in – Record a clock-in timestamp for the authenticated user. This would create a new attendance log entry (e.g. with employee_id, type = "in", and the current timestamp). The backend returns a confirmation or the created record.

POST /api/clock-out – Record a clock-out timestamp. Similar to clock-in, but marks an “out” entry, possibly linking it to the last “in” entry for that user to complete a work session.

GET /api/attendance – (Planned) Retrieve attendance records. In a future iteration, this endpoint could return a list of all clock-in/out records for the logged-in user (or for all users if an admin role is considered). This would allow the frontend to display a history or summary of hours worked.

Note: Proper CORS configuration is important since the frontend runs separately. The Laravel backend should be configured to accept requests from the frontend’s origin (e.g. localhost port for development). Laravel 7+ includes a config/cors.php file where you can adjust allowed origins, headers, and methods. Make sure the frontend’s URL (e.g. http://localhost:3000 for a React dev server) is allowed so that API calls succeed without being blocked by the browser.

Project Status and Roadmap

This backend is currently in development. The basic functionality for logging time entries is in place, but several important features are pending:

Secure Authentication: Implementing hashed passwords and possibly a full authentication system (Laravel Sanctum or Passport for API tokens, or session-based auth with Laravel’s built-in Auth) is a top priority. In the finished system, passwords will be stored securely (using Bcrypt hashing) and the login process will issue an auth token or session cookie instead of using plaintext matching
laravel.com
.

User Management: Future iterations may add registration of new users (or an admin interface to add employees), password reset functionality, and role-based access (e.g. admin vs regular employee rights).

Data Reporting: Additional endpoints or functionalities to summarize work hours (e.g. total hours per day/week) and to detect anomalies (like missed clock-outs) could be added. The backend might also enforce rules (e.g. preventing duplicate clock-ins without clock-out).

Improved Validation & Error Handling: Enhancing how the API responds to invalid input or server errors (with clear messages) and using proper HTTP status codes.

Testing: Implementing unit and feature tests (using Laravel’s testing suite) to ensure reliability of the login and time logging features.

Frontend (apptimbratura)
Overview

The apptimbratura repository contains the front-end application of the Timbratura system. It provides a user-friendly interface for employees to log in and record their check-in/check-out times, communicating with the Laravel backend via HTTP requests. This front-end is implemented as a single-page application (SPA) using modern JavaScript. All user interactions – such as entering credentials or clicking the “Clock In” button – trigger API calls to the backend and update the interface accordingly. The UI is kept simple and functional, given the project’s early stage.

Features

Login Screen: A simple login form where the user enters their identifier (username or email, depending on how employees are defined) and password. On submission, the app sends these credentials to the backend login API and processes the response. If the credentials match (even though not securely hashed yet on backend), the app considers the user “logged in” for the session.

Clock-In/Clock-Out Interface: Once logged in, the employee is presented with controls to Clock In and Clock Out. For example, a button to clock in will send a request to the backend’s /api/clock-in. On success, the UI might display the time of clock-in and change the available action to clock-out (to prevent multiple clock-ins in a row). Similarly, pressing Clock Out records the end of the work period.

Status Display: The frontend provides feedback to the user. After clocking in, it may show a message like “✅ You have clocked in at 09:00 AM” and update the interface to expect a clock-out next. After clocking out, it could display the total time worked in that session or a confirmation “✅ You have clocked out at 5:00 PM”.

Attendance Log View (Basic): Planned: The app may include a simple view of the user’s recent attendance records (e.g. a list of today’s punches or the current week’s history). This would be fetched from an endpoint like /api/attendance. (If not implemented yet, it’s a forthcoming feature to help users see their logged times.)

Error Alerts: If login fails or an API call is unsuccessful (wrong password, server down, etc.), the frontend will show an error message to the user (e.g. “Login failed: invalid credentials” or “Could not connect to server”). This ensures the user gets feedback in case of issues (though at this stage errors might be handled in a very basic way, such as an alert() or a simple text message on the page).

Tech Stack

Framework/Library: React (with JavaScript ES6+). The project was bootstrapped with a modern build tool (e.g. Create React App or Vite), providing a component-based structure. React was chosen for its simplicity in building interactive UIs and managing state. (If using a different framework like Angular or Vue, the overall concept is similar: a SPA that communicates with the Laravel API. Ensure you have the respective CLI tools installed if needed.)

Language: JavaScript (possibly some TypeScript). Components manage the UI and application state (e.g. whether a user is logged in, or whether they are currently clocked in).

HTTP Client: The app uses the Fetch API or Axios to send AJAX requests to the backend. For example, an Axios call might post to /api/login and receive JSON indicating success or failure. Subsequent calls (clock-in/out) include necessary data (like the user’s ID or an auth token if it were implemented).

UI & Styling: HTML5 and CSS3 for layout and styling. The design is currently basic – likely a simple form for login and buttons for actions. (If a UI framework is used, e.g., Bootstrap or Material UI, that would be mentioned here. As of now, assume minimal styling or a lightweight CSS library for responsive design.)

State Management: React’s built-in state and hooks (e.g. useState, useEffect) handle UI updates. For example, after a successful login, the app might store the user info in state (and possibly in localStorage if persisting session was desired later). For an Angular app, this would be handled via services and RxJS for state management.

Routing: Since this is a small app, it might not use complex client-side routing yet. The login and clock-in interface could be on a single page or conditionally rendered components. In the future, a routing library (React Router or Angular Router) could be introduced for multiple pages (e.g. an admin dashboard).

Installation & Setup

Clone the repository: Clone the apptimbratura frontend repo to your local machine.

Install Node dependencies: Navigate into the project directory and run npm install (this will download all required packages as listed in package.json, such as React and any other dependencies). Ensure you have a recent version of Node.js and npm installed (Node 16+ recommended for modern build tools).

Configure API connection: By default, the frontend is likely configured to send requests to http://localhost:8000 (the Laravel backend’s default address). Check the configuration:

If the project was created with Create React App, there may be a file like .env or a constant in the code for the API base URL (e.g. REACT_APP_API_URL). Update it if your backend is running on a different host or port.

In an Angular project, check environment.ts for the API endpoint base URL.

The goal is to ensure the frontend knows where to reach the Laravel API. For development, if both are on the same machine, you might use localhost with the correct port. Adjust any config file or constant accordingly.

Run the development server: Start the frontend app in development mode:

For React, run npm start. This typically launches a dev server (e.g. on http://localhost:3000) and hot-reloads as you make changes.

For Angular, run ng serve (or npm start if configured) to launch on http://localhost:4200.

The terminal will indicate which URL the app is running at. Open that URL in your browser to view the application. You should see the login screen when the app loads.

Log in and test: Use a test employee account to log in. For instance, if you seeded a user or created one manually in the backend (step 5 of backend setup), enter that user’s ID/username and password. Upon successful login, the app should navigate to or display the clock-in interface.

Clock in/out process: Click the Clock In button to record a start time. You should receive feedback on the UI (e.g. the button might change to “Clock Out” or a message “Clock-in successful at [time]”). Confirm in the backend (database or logs) that the entry was created. When you’re done, click Clock Out to record the end time. Again, you should see confirmation.

Build for production: When you are ready to deploy the frontend, run npm run build. This creates an optimized production build of the app (bundled static files, typically in a /build or /dist folder). These files can be deployed on a web server. (If deploying in tandem with the Laravel app, you could serve these static files via Laravel or any web server and ensure the API endpoints are reachable.)

Usage Notes

Maintaining Session: Since the backend currently does not issue tokens or sessions, the frontend treats the login in a minimalist way. For example, after a successful login, the app might simply route the user to the clock-in page and store the employee’s info in memory. If the page is refreshed, this state could be lost, meaning the user might have to log in again. In future updates, persistent login (using JWT or session cookies) will be implemented so that the session can survive refreshes or require fewer logins.

Demo Credentials: If a default demo user is provided (e.g. in the database seeder), use those credentials to test the app. For example, the README might include something like “Demo login: email: test@example.com, password: password”. Make sure to update or remove such test credentials in production.

Error Handling: During development, you can check the browser’s console and the Laravel server log for any error messages. Common issues might be CORS errors (if the backend isn’t allowing the frontend’s origin – see CORS setup in backend README), or network errors if the API URL is wrong. Also ensure both backend and frontend are running simultaneously.

Project Status and Future Work

The frontend application is functional but not complete. Planned improvements and upcoming features include:

Improved Authentication Flow: Once the backend adds secure auth (tokens or sessions), the frontend will be updated to store auth tokens (perhaps in memory or localStorage) and attach them to API requests (e.g. via Authorization headers) for protected routes. A logout button will also be added to clear credentials.

Input Validation & Feedback: Enhancing form validation on the login page (e.g. required fields, error messages for wrong credentials) and on any other input (though clock-in doesn’t require user input aside from the action itself). Currently, validation is basic.

UI/UX Enhancements: The interface can be made more intuitive and visually appealing. Plans include adding a proper CSS framework or custom styling for better layout, responsive design for mobile use (so employees can clock in via phone), and perhaps visual indicators (like color changes when clocked in vs out).

Attendance Records View: Building out a page or section where users can see their past clock-ins/outs, total hours worked in a day or week, etc. This might involve charts or tables summarizing data, which could leverage a library or custom components.

Role-Based Views: In the future, an admin dashboard might be introduced. The admin could view all employees’ attendance, edit entries, or generate reports. This would require additional frontend routes/components (and corresponding backend endpoints).

Offline Mode (Stretch Goal): Considering a scenario where an employee might not have internet temporarily, the app could allow “offline” punches that sync later. This would be a more advanced feature, possibly using IndexedDB or local storage to queue events. (This is just a potential idea for the far future to increase robustness.)

Note: Both the backend and frontend are under active development. Contributors are welcome to explore the code, suggest improvements, or integrate new features. For any setup issues or questions, please refer to the code documentation and comments within the repositories. Being a work-in-progress, please use this system in a controlled test environment only – do not use in production until security (password hashing, authentication) and data integrity features are properly in place.
