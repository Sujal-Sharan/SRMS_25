-- Store student records
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_roll VARCHAR(30) UNIQUE NOT NULL,
    college_roll VARCHAR(30) UNIQUE NOT NULL,     
    name VARCHAR(50) NOT NULL,
    current_semester INT NOT NULL DEFAULT '1',
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

-- Stores different subject data -- 
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each subject
    subject_code VARCHAR(20) UNIQUE NOT NULL,   -- Unique subject code (e.g., MATH101, PHY102)
    subject_name VARCHAR(50) NOT NULL,          -- Name of the subject (e.g., "Mathematics", "Physics")
    subject_credit INT NOT NULL
);

-- Courses -- 
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique ID for each course
    course_code VARCHAR(20) UNIQUE NOT NULL,     -- Unique course code (e.g., BSC-MATHS, BTECH-CS)
    course_name VARCHAR(50) NOT NULL            -- Name of the course (e.g., "B.Sc. Mathematics", "B.Tech Computer Science")
);

-- Joining course_subject_semester
CREATE TABLE course_subject_semester (
    course_id INT,                             -- Foreign key from Courses table
    subject_id INT,                            -- Foreign key from Subjects table
    semester INT,
    PRIMARY KEY (course_id, subject_id, semester),  -- Composite primary key
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);

-- Stores internal and external marks --
CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) NOT NULL,
    subject_id INT NOT NULL,
    semester INT NOT NULL,
    test_type ENUM('CA1', 'CA2', 'CA3', 'CA4', 'PCA1', 'PCA2', 'FINAL') NOT NULL,
    marks_obtained DECIMAL(5,2),
    max_marks DECIMAL(5,2),
    exam_date DATE,
    is_absent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(college_roll) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    UNIQUE (student_id, subject_id, semester, test_type)
);

-- Stores attendance records --  
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) NOT NULL,             -- Reference to student
    subject_id INT NOT NULL,             -- Reference to subject
    semester INT NOT NULL,               -- For which semester
    attendance_date DATE NOT NULL,       -- Date of the class
    status ENUM('PRESENT', 'ABSENT', 'LATE', 'EXCUSED') NOT NULL,  -- Attendance status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id) REFERENCES students(college_roll) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    
    UNIQUE (student_id, subject_id, attendance_date)  --  Prevent duplicate entries for same student, subject, date
);


