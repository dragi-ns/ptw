DROP TABLE IF EXISTS users;

CREATE TABLE users (
   id INT UNSIGNED AUTO_INCREMENT,
   username VARCHAR(32) UNIQUE NOT NULL,
   password VARCHAR(255) NOT NULL,
   email VARCHAR(255) UNIQUE NOT NULL,
   role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
   approved BOOL NOT NULL DEFAULT FALSE,
   created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (id)
);

INSERT INTO users
(
    id,
    username,
    password,
    email,
    role,
    approved
)
VALUES
    (
        1,
        'admin',
        '$2a$10$EorgXYIB2.s643mzg.ROQux89ZgCcnAgVXdj7XTSkwoLFnilxc63.',
        'admin@pm.me',
        'admin',
        TRUE
    ),
    (
        2,
        'user',
        '$2a$10$fuIqBScxDX8WB5JdTFYPa.bqdqDMEjiB/wp7AWLYT3tg3ZNSFbYCa',
        'user@pm.me',
        'user',
        TRUE
    ),
    (
        3,
        'user2',
        '$2a$10$fuIqBScxDX8WB5JdTFYPa.bqdqDMEjiB/wp7AWLYT3tg3ZNSFbYCa',
        'user2@pm.me',
        'user',
        TRUE
    ),
    (
        4,
        'user3',
        '$2a$10$fuIqBScxDX8WB5JdTFYPa.bqdqDMEjiB/wp7AWLYT3tg3ZNSFbYCa',
        'user3@pm.me',
        'user',
        FALSE
    ),
    (
        5,
        'user4',
        '$2a$10$fuIqBScxDX8WB5JdTFYPa.bqdqDMEjiB/wp7AWLYT3tg3ZNSFbYCa',
        'user4@pm.me',
        'user',
        TRUE
    ),
    (
        6,
        'user5',
        '$2a$10$fuIqBScxDX8WB5JdTFYPa.bqdqDMEjiB/wp7AWLYT3tg3ZNSFbYCa',
        'user5@pm.me',
        'user',
        TRUE
    ),
    (
        7,
        'user6',
        '$2a$10$fuIqBScxDX8WB5JdTFYPa.bqdqDMEjiB/wp7AWLYT3tg3ZNSFbYCa',
        'user6@pm.me',
        'user',
        FALSE
    );
