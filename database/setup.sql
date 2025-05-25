-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS meowtopia;
USE meowtopia;

-- Create the cats table
CREATE TABLE IF NOT EXISTS cats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age VARCHAR(50) NOT NULL,
    breed VARCHAR(100) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    color VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    story TEXT NOT NULL,
    personality_traits TEXT NOT NULL,
    health_care TEXT NOT NULL,
    adoption_requirements TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 