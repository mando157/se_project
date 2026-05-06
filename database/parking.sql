-- Data Base
CREATE DATABASE parking;

USE parking;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('driver','owner','admin'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE parking_spots (
    spot_id INT AUTO_INCREMENT PRIMARY KEY,
    spot_name VARCHAR(100),
    price DECIMAL(10,2)
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT,
    spot_id INT,

    date DATE,
    start_time TIME,
    end_time TIME,

    duration FLOAT,
    price_per_hour DECIMAL(10,2),
    total_cost DECIMAL(10,2),

    status VARCHAR(20),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT,

    title VARCHAR(255),
    message TEXT,
    type VARCHAR(20),
    time VARCHAR(20),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE fines (
    fine_id INT AUTO_INCREMENT PRIMARY KEY,

    booking_id INT NOT NULL,
    user_id INT NOT NULL,

    reason VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,

    status ENUM('unpaid','paid','waived') DEFAULT 'unpaid',

    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

