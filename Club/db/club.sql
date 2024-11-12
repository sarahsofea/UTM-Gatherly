CREATE DATABASE club_directory;

USE club_directory;

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing club events
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE,
    time TIME,
    location VARCHAR(255),
    organizer VARCHAR(255),
    image VARCHAR(255),
    club_id INT,
    FOREIGN KEY (club_id) REFERENCES clubs(club_id)
);

-- Table for storing user registrations
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    matric VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    faculty VARCHAR(100)
);

-- Create the clubs table
CREATE TABLE IF NOT EXISTS clubs (
    club_id INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(100) NOT NULL,
    description TEXT,
    faculty VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone_number VARCHAR(15),
    established_year YEAR,
    website_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE event_managers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    matric_number VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Sample data for clubs in UTM (if you want to populate the table initially)
INSERT INTO clubs (club_name, description, faculty, email, phone_number, established_year, website_url) VALUES
('Engineering Society', 'A club dedicated to promoting engineering excellence.', 'Engineering', 'engsoc@utm.edu', '1234567890', 2005, 'https://engsoc.utm.edu'),
('Computer Science Club', 'A community for students interested in computer science and programming.', 'Computer Science', 'csclub@utm.edu', '0987654321', 2010, 'https://csclub.utm.edu'),
('Robotics Club', 'Focused on robotics and automation projects.', 'Engineering', 'robotics@utm.edu', '1231231234', 2012, 'https://robotics.utm.edu');
