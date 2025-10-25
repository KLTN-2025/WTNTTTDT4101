-- ================================================
--  ĐỒ ÁN: WEBSITE TRẮC NGHIỆM TRỰC TUYẾN
--  CHỨC NĂNG: TỰ ĐỘNG TẠO CÂU HỎI & PHÂN TÍCH NĂNG LỰC HỌC VIÊN
--  NGÔN NGỮ: MySQL
--  ENGINE: InnoDB (hỗ trợ khóa ngoại, giao dịch)
-- ================================================

-- Xóa database cũ nếu tồn tại để tránh lỗi khi chạy lại
DROP DATABASE IF EXISTS online_exam_system;

-- Tạo mới database
CREATE DATABASE online_exam_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_exam_system;

-- ================================================
-- BẢNG 1: NGƯỜI DÙNG (USERS)
-- Lưu thông tin tài khoản của học viên và giảng viên
-- ================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150),
    email VARCHAR(150) UNIQUE,
    role ENUM('STUDENT', 'TEACHER', 'ADMIN') DEFAULT 'STUDENT',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 2: MÔN HỌC (SUBJECTS)
-- Mỗi môn có thể có nhiều chủ đề (topic)
-- ================================================
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 3: CHỦ ĐỀ (TOPICS)
-- Liên kết với môn học, để chia nhỏ nội dung
-- ================================================
CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 4: CÂU HỎI (QUESTIONS)
-- Lưu ngân hàng câu hỏi, có phân loại độ khó
-- ================================================
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    content TEXT NOT NULL,
    difficulty ENUM('EASY', 'MEDIUM', 'HARD') DEFAULT 'MEDIUM',
    created_by INT,
    FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 5: CÂU TRẢ LỜI (ANSWERS)
-- Mỗi câu hỏi có nhiều câu trả lời, chỉ một câu đúng
-- ================================================
CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    content TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 6: ĐỀ THI (EXAMS)
-- Lưu thông tin các bài kiểm tra / trắc nghiệm
-- ================================================
CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    subject_id INT NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 7: CÂU HỎI TRONG ĐỀ (EXAM_QUESTIONS)
-- Dùng để gắn nhiều câu hỏi vào một đề thi
-- ================================================
CREATE TABLE exam_questions (
    exam_id INT,
    question_id INT,
    PRIMARY KEY (exam_id, question_id),
    FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 8: KẾT QUẢ LÀM BÀI (EXAM_RESULTS)
-- Lưu thông tin tổng quan mỗi lần học viên làm bài
-- ================================================
CREATE TABLE exam_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    user_id INT NOT NULL,
    score DECIMAL(5,2),
    start_time DATETIME,
    end_time DATETIME,
    FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 9: CHI TIẾT CÂU TRẢ LỜI (ANSWER_RESULTS)
-- Lưu từng câu hỏi mà học viên đã chọn trong bài làm
-- ================================================
CREATE TABLE answer_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_result_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_id INT,
    is_correct BOOLEAN,
    FOREIGN KEY (exam_result_id) REFERENCES exam_results(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (answer_id) REFERENCES answers(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ================================================
-- BẢNG 10: PHÂN TÍCH NĂNG LỰC (ABILITY_ANALYSIS)
-- Dùng để đánh giá và lưu năng lực của học viên theo từng chủ đề
-- ================================================
CREATE TABLE ability_analysis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    topic_id INT NOT NULL,
    avg_score DECIMAL(5,2) DEFAULT 0,
    total_attempts INT DEFAULT 0,
    accuracy_rate DECIMAL(5,2) DEFAULT 0,
    ability_level ENUM('LOW', 'MEDIUM', 'HIGH') DEFAULT 'LOW',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

