-- sql/database.sql - полное создание базы данных InvokerDoto
DROP DATABASE IF EXISTS InvokerDoto;
CREATE DATABASE InvokerDoto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE InvokerDoto;

-- Таблица заклинаний
CREATE TABLE spells (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    combination VARCHAR(10) NOT NULL,
    description TEXT NOT NULL,
    damage_type VARCHAR(50) NOT NULL,
    mana_cost INT DEFAULT 0,
    cooldown INT DEFAULT 0,
    spell_type ENUM('Basic', 'Advanced', 'Ultimate') DEFAULT 'Basic',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица сборок предметов
CREATE TABLE builds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    items TEXT NOT NULL,
    playstyle ENUM('Quas-Wex', 'Quas-Exort', 'Wex-Exort', 'Hybrid') DEFAULT 'Hybrid',
    difficulty ENUM('Easy', 'Medium', 'Hard') DEFAULT 'Medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица статистики
CREATE TABLE stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attribute_name VARCHAR(255) NOT NULL,
    attribute_value VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL
);