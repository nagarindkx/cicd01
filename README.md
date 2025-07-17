Simple PHP MySQL Web Application
This repository contains a basic PHP application that connects to a MySQL database. It's designed to demonstrate a simple database interaction and serve as a foundation for a CI/CD pipeline using Jenkins, Docker, and Kubernetes.

Project Structure
.
├── src/
│   └── index.php
├── Dockerfile
├── Jenkinsfile
├── README.md

src/index.php
This file contains the PHP code responsible for connecting to the MySQL database and fetching some data.

Dockerfile
This file defines how to build the Docker image for the PHP application.

Jenkinsfile
This file defines the CI/CD pipeline for Jenkins, automating the build, push, and deployment process.

Setup and Usage
MySQL Database
Ensure you have a MySQL database running and accessible. You'll need to create a database and a table for this application to interact with.

Example SQL to create a database and table:

CREATE DATABASE IF NOT EXISTS my_database;

USE my_database;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

INSERT INTO users (name, email) VALUES ('John Doe', 'john.doe@example.com');
INSERT INTO users (name, email) VALUES ('Jane Smith', 'jane.smith@example.com');

Update the database connection details in src/index.php (or via environment variables in a production setup) to match your MySQL configuration.

Running the PHP Application Locally (without Docker)
Prerequisites: PHP (with MySQLi extension) and a web server (e.g., Apache, Nginx).

Configure: Update src/index.php with your MySQL credentials.

Serve: Place the src directory in your web server's document root.

Docker
The Dockerfile will allow you to containerize this application.

Jenkins
The Jenkinsfile outlines the steps for building the Docker image, pushing it to a Harbor registry, and deploying it to a Kubernetes cluster.

Harbor
A self-hosted Harbor registry will be used to store the Docker images.

Kubernetes
The application will be deployed to a local Kubernetes cluster. Kubernetes credentials will be managed securely.
