-- Store student records
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_roll VARCHAR(30) UNIQUE NOT NULL,
    college_roll VARCHAR(30) UNIQUE NOT NULL,     
    name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    address TEXT,
    gender VARCHAR(10),
    department VARCHAR(50),
    batch_year YEAR,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Login table --
CREATE TABLE login (
    user_id VARCHAR(30) PRIMARY KEY,  
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin', 'faculty') DEFAULT 'student',  -- using ENUM for roles
    is_active ENUM('active', 'inactive') DEFAULT 'active',  -- using ENUM for status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Internal Tanle to keep track of offered courses
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    duration INT COMMENT 'Duration in years'
);

-- All subjects which are part of a course
CREATE TABLE subjects (
    subject_id INT PRIMARY KEY,
    course_id INT,
    subject_name VARCHAR(100),
    semester INT,
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);


--D
CREATE TABLE faculty (
    user_id INT NOT NULL PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    department VARCHAR(100),
    designation VARCHAR(50),
    subject_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);

--D
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject_id INT,
    date DATE,
    status ENUM('present', 'absent'),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);

--D
CREATE TABLE marks (
    mark_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    semester INT NOT NULL,
    test_type ENUM('CA1', 'CA2', 'CA3', 'CA4', 'PCA1', 'PCA2', 'FINAL') NOT NULL,
    marks_obtained DECIMAL(5,2) NOT NULL,
    max_marks DECIMAL(5,2) NOT NULL,
    exam_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    UNIQUE (user_id, subject_id, semester, test_type)
);
