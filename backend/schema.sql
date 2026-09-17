-- Short Circuit Company — NFC Profile Card
-- MySQL schema for the future live backend.
-- Not required on GitHub Pages — run this only once you deploy to a
-- PHP + MySQL host and uncomment the DB code in backend/config.php
-- and backend/api/*.php.

CREATE DATABASE IF NOT EXISTS sc_profile CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sc_profile;

CREATE TABLE IF NOT EXISTS person (
    id       INT PRIMARY KEY AUTO_INCREMENT,
    name     VARCHAR(120) NOT NULL,
    title    VARCHAR(120) NOT NULL,
    company  VARCHAR(120) NOT NULL,
    bio      TEXT,
    photo    VARCHAR(255),
    location VARCHAR(120)
);

CREATE TABLE IF NOT EXISTS contact (
    id       INT PRIMARY KEY AUTO_INCREMENT,
    phone    VARCHAR(40),
    whatsapp VARCHAR(40),
    email    VARCHAR(160)
);

CREATE TABLE IF NOT EXISTS links (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    label      VARCHAR(80) NOT NULL,
    url        VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS portfolio (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    title       VARCHAR(160) NOT NULL,
    description TEXT,
    image       VARCHAR(255),
    link        VARCHAR(255),
    sort_order  INT DEFAULT 0
);

-- Seed data matching the current data/profile.json, so the two stay in sync
-- the day this switches over.
INSERT INTO person (name, title, company, bio, photo, location) VALUES
('Maha Monir', 'Technical Office Manager', 'Short Circuit Company',
 'Leads the technical office at Short Circuit Company, overseeing lighting system documentation, project coordination, and engineering delivery across the company''s DMX and LED installation work.',
 'assets/img/photo-placeholder.svg', 'Egypt');

INSERT INTO contact (phone, whatsapp, email) VALUES
('+20 000 000 0000', '+20 000 000 0000', 'maha.monir@shortcircuit.company');

INSERT INTO links (label, url, sort_order) VALUES
('LinkedIn', 'https://www.linkedin.com/in/maha-monir-9b3117204/', 0),
('Company Website', 'https://shortcircuit.company', 1);

INSERT INTO portfolio (title, description, image, link, sort_order) VALUES
('DMX Hall Lighting Installation', 'Technical documentation and system design for a large-scale DMX lighting control installation.', 'assets/img/project-placeholder.svg', '', 0),
('Synchronized LED Installation', 'Technical report and coordination for a synchronized large-scale LED installation.', 'assets/img/project-placeholder.svg', '', 1);
