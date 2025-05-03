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

--Students Documents Stroing Table--
CREATE TABLE student_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100),
    roll_no VARCHAR(20),
    reg_no VARCHAR(20),
    document_name VARCHAR(100),
    stream VARCHAR(10),
    batch VARCHAR(20),
    document_url VARCHAR(255),
    semester INT,
    status ENUM('Verified', 'Unverified') DEFAULT 'Unverified'
);

--Inserting Value in the students_documents Table--
INSERT INTO student_documents 
(student_name, roll_no, reg_no, document_name, document_url, stream, batch, semester, status) 
VALUES
-- CSE
('Ananya Sen', 'CSE2001', 'REG2001', 'Semester 1 Marksheet', 'uploads/sem1_cse2001.pdf', 'CSE', '2020-24', 1, 'Unverified'),
('Raj Gupta', 'CSE2002', 'REG2002', 'Semester 2 Marksheet', 'uploads/sem2_cse2002.pdf', 'CSE', '2020-24', 2, 'Verified'),
('Priya Das', 'CSE2101', 'REG2101', 'Semester 3 Marksheet', 'uploads/sem3_cse2101.pdf', 'CSE', '2021-25', 3, 'Unverified'),
('Soham Roy', 'CSE2201', 'REG2201', 'Semester 4 Marksheet', 'uploads/sem4_cse2201.pdf', 'CSE', '2022-26', 4, 'Verified'),

-- IT
('Ritika Paul', 'IT2001', 'REG3001', 'Semester 1 Marksheet', 'uploads/sem1_it2001.pdf', 'IT', '2020-24', 1, 'Unverified'),
('Abhishek Das', 'IT2002', 'REG3002', 'Semester 5 Marksheet', 'uploads/sem5_it2002.pdf', 'IT', '2020-24', 5, 'Verified'),
('Manish Sharma', 'IT2101', 'REG3101', 'Semester 6 Marksheet', 'uploads/sem6_it2101.pdf', 'IT', '2021-25', 6, 'Unverified'),
('Tania Dey', 'IT2201', 'REG3201', 'Semester 7 Marksheet', 'uploads/sem7_it2201.pdf', 'IT', '2022-26', 7, 'Verified'),

-- ECE
('Ankita Ghosh', 'ECE2001', 'REG4001', 'Semester 2 Marksheet', 'uploads/sem2_ece2001.pdf', 'ECE', '2020-24', 2, 'Unverified'),
('Sayan Das', 'ECE2002', 'REG4002', 'Semester 3 Marksheet', 'uploads/sem3_ece2002.pdf', 'ECE', '2020-24', 3, 'Verified'),
('Sneha Roy', 'ECE2101', 'REG4101', 'Semester 4 Marksheet', 'uploads/sem4_ece2101.pdf', 'ECE', '2021-25', 4, 'Unverified'),
('Arnab Bose', 'ECE2201', 'REG4201', 'Semester 8 Marksheet', 'uploads/sem8_ece2201.pdf', 'ECE', '2022-26', 8, 'Verified'),

-- EE
('Debanjan Pal', 'EE2001', 'REG5001', 'Semester 1 Marksheet', 'uploads/sem1_ee2001.pdf', 'EE', '2020-24', 1, 'Verified'),
('Poulami Sen', 'EE2002', 'REG5002', 'Semester 2 Marksheet', 'uploads/sem2_ee2002.pdf', 'EE', '2020-24', 2, 'Unverified'),
('Kunal Kar', 'EE2101', 'REG5101', 'Semester 5 Marksheet', 'uploads/sem5_ee2101.pdf', 'EE', '2021-25', 5, 'Verified'),
('Ishita Paul', 'EE2201', 'REG5201', 'Semester 6 Marksheet', 'uploads/sem6_ee2201.pdf', 'EE', '2022-26', 6, 'Unverified'),

-- More entries for diversity
('Aritra Das', 'CSE2202', 'REG2202', 'Project Report', 'uploads/project_cse2202.pdf', 'CSE', '2022-26', 6, 'Unverified'),
('Moumita Roy', 'IT2102', 'REG3102', 'Internship Certificate', 'uploads/intern_it2102.pdf', 'IT', '2021-25', 5, 'Verified'),
('Sumit Kumar', 'ECE2202', 'REG4202', 'Lab Report', 'uploads/lab_ece2202.pdf', 'ECE', '2022-26', 7, 'Unverified'),
('Priyanka Dey', 'EE2102', 'REG5102', 'Workshop Certificate', 'uploads/workshop_ee2102.pdf', 'EE', '2021-25', 4, 'Verified'),
('Arunava Mitra', 'IT2203', 'REG3203', 'Seminar Report', 'uploads/seminar_it2203.pdf', 'IT', '2022-26', 8, 'Unverified'),
('Tushar Sinha', 'CSE2103', 'REG2103', 'Semester 4 Marksheet', 'uploads/sem4_cse2103.pdf', 'CSE', '2021-25', 4, 'Verified'),
('Payel Nandi', 'ECE2102', 'REG4102', 'Semester 6 Marksheet', 'uploads/sem6_ece2102.pdf', 'ECE', '2021-25', 6, 'Unverified');


--Stores faculty details--
CREATE TABLE faculty (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    department VARCHAR(50),
    email VARCHAR(100),
    phone VARCHAR(20),
    category VARCHAR(50),
    subjects TEXT
);


