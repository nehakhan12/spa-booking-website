-- Create the database
CREATE DATABASE IF NOT EXISTS booking_system;

-- Use the database
USE booking_system;

-- Create the bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    treatment VARCHAR(255) NOT NULL,
    service VARCHAR(255) NOT NULL,
    time TIME NOT NULL,
    num_visitors INT NOT NULL,
    accommodations TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create a table for available time slots
CREATE TABLE IF NOT EXISTS available_times (
    id INT AUTO_INCREMENT PRIMARY KEY,
    treatment VARCHAR(255) NOT NULL,
    service VARCHAR(255) NOT NULL,
    time TIME NOT NULL,
    is_booked BOOLEAN DEFAULT FALSE
);
