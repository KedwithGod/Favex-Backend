

Favex Backend App (SQLite)
📖 Overview

This guide walks you through setting up and running the Laravel application with SQLite. It covers environment setup, database configuration, migrations, seeders, API documentation, and debugging tools.

Tip: Follow each step carefully to ensure your application works correctly in development.

⚙️ Prerequisites

Ensure your system has the following installed:

PHP ≥ 8.0

Composer (for dependency management)

Note: SQLite is lightweight and does not require a separate database server, making it perfect for local development.

🛠️ Setup Steps
1. Clone the Repository

Clone your project repository and navigate into the folder.

Tip: Use a descriptive folder name to avoid confusion with other projects.

2. Install Dependencies

Install all required PHP packages using Composer. This ensures your application has everything it needs to run.

Note: If you encounter version conflicts, check your PHP and Composer versions.

3. Configure the Environment

Create the .env configuration file from the example:

Set the database connection to sqlite.

Specify the full path to your SQLite database file.

Ensure the SQLite database file exists.

Warning: If the database file does not exist, the application will fail to run migrations.

4. Generate the Application Key

Laravel requires an application key for encryption and session security.

Tip: Always generate a new key when setting up a fresh environment.

5. Run Database Migrations

Migrations create all required database tables.

Tip: If you make changes to your models or migrations, run migrate:fresh to reset the database.
Warning: This will delete all existing data in the database.

6. Seed the Database (Optional)

Populate the database with initial or sample data using seeders.

Note: Seeders are useful for testing and development, but may not be needed in production.

7. Start the Application

Run the Laravel development server. By default, it will be available at http://127.0.0.1:8000
.

Tip: Keep the server running in a terminal window for continuous development.

📑 API Documentation

This project uses rakutentech/laravel-request-docs
 to generate API documentation.

Access documentation at http://localhost:8000/request-docs

Automatically updates based on your API routes and request definitions

Ideal for developers to explore endpoints, request parameters, and response structures

Tip: Keep your routes and request validation up to date for accurate documentation.

🐞 Debugging

Debugging is handled via barryvdh/laravel-debugbar
:

View executed queries, request data, and performance metrics

Track timeout errors, exceptions, and model usage

Debug toolbar appears in the browser when APP_DEBUG=true

Note: Disable debugbar in production for security and performance reasons.

🔧 Additional Notes

Clearing Cache: Refresh configurations, routes, and views when needed.

Environment Settings: For development, ensure APP_ENV=local and APP_DEBUG=true.

Optional Seeders: Seeders can provide useful sample data during development.

✅ Summary

Following this guide ensures your Laravel application is:

Fully operational with SQLite

Equipped with auto-generated API documentation

Integrated with a robust debugging toolbar

Ready for development, testing, and exploration