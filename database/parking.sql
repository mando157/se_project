CREATE DATABASE parking;
USE parking;

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('driver','owner','admin'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PARKING SPOTS
CREATE TABLE parking_spots (
    spot_id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,

    spot_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,

    price DECIMAL(10,2) NOT NULL,
    total_slots INT NOT NULL,
    
    status ENUM('active','inactive') DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- SLOTS
CREATE TABLE slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    spot_id INT NOT NULL,

    slot_name VARCHAR(50),
    status ENUM('active','booked','blocked') DEFAULT 'active',

    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id) ON DELETE CASCADE
);

-- BOOKINGS
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    spot_id INT NOT NULL,

    location VARCHAR(255) NOT NULL,

    date DATE,
    start_time TIME,
    end_time TIME,

    duration FLOAT,
    price_per_hour DECIMAL(10,2),
    total_cost DECIMAL(10,2),

    status ENUM('pending','active','completed','cancelled') DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id) ON DELETE CASCADE
);

-- NOTIFICATIONS
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    booking_id INT NOT NULL,

    title VARCHAR(255),
    message TEXT,
    type VARCHAR(20),
    time VARCHAR(20),

    is_read BOOLEAN DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) 
    REFERENCES users(id) 
    ON DELETE CASCADE,

    FOREIGN KEY (booking_id) 
    REFERENCES bookings(booking_id)
    ON DELETE CASCADE
);

-- FINES
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

-- REAL-TIME BOOKING
CREATE TABLE realtime_booking (
    realtime_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    booking_id INT NOT NULL,
    spot_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id) ON DELETE CASCADE
);