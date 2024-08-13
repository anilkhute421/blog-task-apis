Blog API Setup & Usage Guide

This document provides step-by-step instructions to set up and run the Blog RESTful API using XAMPP. It also includes details on how the code is structured and how to test the API endpoints.

Prerequisites
XAMPP: Ensure you have XAMPP installed on your machine. You can download it from Apache Friends.
PHP: The API is written in PHP, which comes bundled with XAMPP.
MySQL: The API uses MySQL as the database, also included with XAMPP.
Postman (optional): Useful for testing API endpoints, though cURL commands can also be used.

Database Setup
Start XAMPP:

Open the XAMPP control panel.
Start Apache and MySQL.
Create the Database:

Open phpMyAdmin by navigating to http://localhost/phpmyadmin in your browser.
Create a new database named blog.
Run the following SQL script in the SQL tab to create the posts table:

CREATE DATABASE blog;

USE blog;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


Project Setup
Download the Project:

Download the code from the provided ZIP file or clone the below repository url.
Move the Project to XAMPP:

Place the project folder (e.g., blog-api) into the htdocs directory of your XAMPP installation. This is typically located at C:\xampp\htdocs\ on Windows or /Applications/XAMPP/htdocs/ on macOS.
Access the API:

Open your browser and navigate to http://localhost/blog-api/index.php/api/v1/posts. You should see the JSON response from the API, indicating that it's set up correctly.

API Endpoints
The API supports the following operations on blog posts:

Create a Post

Method: POST
Endpoint: /api/v1/posts
Request Body:

{
    "title": "Post Title",
    "content": "Post content goes here.",
    "author": "Author Name"
}

Response: The newly created post with its ID.


Get All Posts

Method: GET
Endpoint: /api/v1/posts
Response: A JSON array of all posts.

Get a Single Post

Method: GET
Endpoint: /api/v1/posts/{id}
Response: The post with the specified ID.

Update a Post

Method: PUT
Endpoint: /api/v1/posts/{id}
Request Body: JSON object containing any fields to be updated.
Response: The updated post.

Delete a Post

Method: DELETE
Endpoint: /api/v1/posts/{id}
Response: A confirmation message.

Code Structure Overview
index.php: The main entry point for the API, handling routing based on the request method and path.

db.php: Manages database connections using PDO.

Post.php: Defines the Post class, encapsulating all CRUD operations on the posts table.

.htaccess (optional): Used for routing if you're implementing clean URLs.


Note: I am also including my local database file (blog.sql) for your convenience. You can use this database to easily perform CRUD operations without needing to manually set up the database from scratch.


This document should provide you with a solid foundation to set up, understand, and extend the Blog RESTful API. If you have any questions or run into issues, refer to this guide or reach out to me at anilkhute421@gmail.com or 9039989441.

