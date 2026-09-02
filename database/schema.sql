-- =========================================================
-- City Care Hospital — Database Schema
-- Import this file via phpMyAdmin (Import tab) or the MySQL CLI.
-- Creates the database + all tables with proper relations
-- (foreign keys), plus starter data.
-- =========================================================

CREATE DATABASE IF NOT EXISTS city_care_hospital
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE city_care_hospital;

-- ============ DEPARTMENTS ============
CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO departments (name) VALUES
  ('Cardiology'),
  ('Neurology'),
  ('Orthopedics'),
  ('Pediatrics'),
  ('Dermatology'),
  ('Gynecology'),
  ('Ophthalmology'),
  ('Pulmonology'),
  ('General Medicine');

-- ============ DOCTORS ============
CREATE TABLE doctors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  designation VARCHAR(150) NOT NULL,        -- e.g. "MBBS, FCPS (Cardiology)"
  department_id INT UNSIGNED NOT NULL,
  email VARCHAR(150) NULL,
  phone VARCHAR(30) NULL,
  experience_years SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  bio TEXT NULL,
  photo VARCHAR(255) NULL,                  -- filename only, stored in Image/doctors/
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_doctors_department
    FOREIGN KEY (department_id) REFERENCES departments(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE INDEX idx_doctors_department ON doctors(department_id);
CREATE INDEX idx_doctors_status ON doctors(status);

-- ============ DOCTOR SCHEDULES (one doctor -> many time slots) ============
CREATE TABLE doctor_schedules (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT UNSIGNED NOT NULL,
  day_of_week ENUM('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  CONSTRAINT fk_schedule_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============ ADMINS (admin panel login) ============
CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin login:
--   username: admin
--   password: Admin@123
-- (Please log in and change this password — it's only a starter account.)
INSERT INTO admins (username, password_hash) VALUES
  ('admin', '$2b$10$Cy9Lp0mi0xMMJSwFEc1JoeofNCiDHiG2QJpyl5l6Z5hxNNFBy7cX6');

-- ============ SAMPLE DOCTORS (starter data, matches the old doctors.html) ============
INSERT INTO doctors (name, designation, department_id, email, phone, experience_years, bio, photo, status) VALUES
  ('Dr. Ahsan Kabir', 'MBBS, FCPS (Cardiology)',
    (SELECT id FROM departments WHERE name = 'Cardiology'),
    'ahsan.kabir@citycare.com', '+1 (555) 201-1001', 12,
    'Dedicated cardiologist with over a decade of experience in interventional cardiology and heart health management.',
    NULL, 'active'),
  ('Dr. Nusrat Jahan', 'MBBS, MD (Neurology)',
    (SELECT id FROM departments WHERE name = 'Neurology'),
    'nusrat.jahan@citycare.com', '+1 (555) 201-1002', 9,
    'Specializes in the diagnosis and treatment of neurological disorders with a patient-first approach.',
    NULL, 'active'),
  ('Dr. Rafiqul Islam', 'MBBS, MS (Orthopedics)',
    (SELECT id FROM departments WHERE name = 'Orthopedics'),
    'rafiqul.islam@citycare.com', '+1 (555) 201-1003', 15,
    'Experienced orthopedic surgeon focused on joint replacement and sports injury recovery.',
    NULL, 'active'),
  ('Dr. Sadia Islam', 'MBBS, DCH (Pediatrics)',
    (SELECT id FROM departments WHERE name = 'Pediatrics'),
    'sadia.islam@citycare.com', '+1 (555) 201-1004', 7,
    'Compassionate pediatrician providing comprehensive care for infants, children, and adolescents.',
    NULL, 'active');
