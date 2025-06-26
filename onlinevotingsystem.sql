-- Create database
CREATE DATABASE IF NOT EXISTS onlinevotingsystem;
USE onlinevotingsystem;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    contact_no VARCHAR(20),
    password VARCHAR(255),
    user_role VARCHAR(20) DEFAULT 'voter'
);

INSERT INTO users (id, username, contact_no, password, user_role) VALUES
(10461, 'merin', '454545465', '7f550a9f4c44173a37664d938f1355f0f92a47a7', 'voter');

-- Table: elections
CREATE TABLE IF NOT EXISTS elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_topic VARCHAR(100),
    no_of_candidates INT,
    starting_date DATE,
    ending_date DATE,
    status VARCHAR(20),
    inserted_by VARCHAR(50),
    inserted_on DATE
);



-- Table: candidates
CREATE TABLE IF NOT EXISTS candidate_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    election_id INT,
    candidate_name VARCHAR(100),
    candidate_details VARCHAR(255),
    candidate_photo VARCHAR(255),
    inserted_by VARCHAR(50),
    inserted_on DATE
);


-- Table: votes
CREATE TABLE IF NOT EXISTS votings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    election_id INT,
    voters_id INT,
    candidate_id INT,
    vote_date DATE,
    vote_time TIME
);


