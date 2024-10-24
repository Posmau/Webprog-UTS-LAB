CREATE DATABASE todo_app;

USE todo_app;

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- To-Do Lists Table
CREATE TABLE to_do_lists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    status ENUM('incomplete', 'complete') DEFAULT 'incomplete',
    FOREIGN KEY (user_id) REFERENCES users(id)
);