# Laravel Project Setup

## Introduction

This README provides detailed instructions on setting up and running the Laravel project.

## Requirements

- PHP >= 7.4
- Composer
- MySQL
- Node.js
- NPM/Yarn
- XAMPP (for local development)

## Setup Instructions

### Step 1: Download Project File

Download the project file from the source provided by your colleague.

### Step 2: Extract and Copy to htdocs

Extract the downloaded project file and copy the contents to the `htdocs` folder in your XAMPP directory.

### Step 3: Install Composer Dependencies

Open a command prompt and navigate to the project directory (e.g., `C:\xampp\htdocs\your-project`). Run the following command to install the required Composer dependencies:

```sh
composer install

### Step 4: Install NPM Dependencies
npm install

### Step 5: Create Database
Open phpMyAdmin and create a new database with a proper name.

### Step 6: Import Database File
The database file is included in root of project, Import the provided database file using phpMyAdmin.

### PS Note: Collect .env file from developer
The project contains multiple keys and secrets pre-configured so collect .env and add it to the root of project.


### Step 7: Run Artisan Commands
Open two separate command prompts and navigate to the project directory in both. Run the following commands in each terminal respectively:
-----Terminal 1:
               php artisan websocket:serve
-----Terminal 2:
               php artisan serve
### Step 8: Access the Project
Once the servers are running, you can access the project at:
http://127.0.0.1:8000


