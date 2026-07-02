CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE مقررات (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE طلاب (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE مدرسون (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE user_moderators (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  moderator_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (moderator_id),
  CONSTRAINT fk_user_moderators_users FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_moderators_moderators FOREIGN KEY (moderator_id) REFERENCES مدرسون (id)
);

CREATE TABLE user_students (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  student_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (student_id),
  CONSTRAINT fk_user_students_users FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_students_students FOREIGN KEY (student_id) REFERENCES طلاب (id)
);

CREATE TABLE user_courses (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (course_id),
  CONSTRAINT fk_user_courses_users FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_courses_courses FOREIGN KEY (course_id) REFERENCES مقررات (id)
);

INSERT INTO users (id, username, email, password, role)
VALUES
(1, 'admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO مقررات (id, name, description)
VALUES
(1, 'Course 1', 'This is course 1'),
(2, 'Course 2', 'This is course 2');

INSERT INTO طلاب (id, name, email, password)
VALUES
(1, 'Student 1', 'student1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'),
(2, 'Student 2', 'student2@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');

INSERT INTO مدرسون (id, name, email, password)
VALUES
(1, 'Teacher 1', 'teacher1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'),
(2, 'Teacher 2', 'teacher2@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');

INSERT INTO user_moderators (id, user_id, moderator_id)
VALUES
(1, 1, 1),
(2, 1, 2);

INSERT INTO user_students (id, user_id, student_id)
VALUES
(1, 1, 1),
(2, 1, 2);

INSERT INTO user_courses (id, user_id, course_id)
VALUES
(1, 1, 1),
(2, 1, 2);