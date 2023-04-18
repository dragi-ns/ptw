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
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(32) UNIQUE NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO categories
    (
        id,
        name
    )
VALUES
    (
        1,
        'JavaScript'
    ),
    (
        2,
        'TypeScript'
    ),
    (
        3,
        'Angular'
    ),
    (
        4,
        'Python'
    ),
    (
        5,
        'Extension'
    ),
    (
        6,
        'Library'
    );
DROP TABLE IF EXISTS types;

CREATE TABLE types (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(32) UNIQUE NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO types
    (
        id,
        name
    )
VALUES
    (
        1,
        'Article'
    ),
    (
        2,
        'Audio'
    ),
    (
        3,
        'Video'
    );
DROP TABLE IF EXISTS resources;

CREATE TABLE resources (
    id INT UNSIGNED AUTO_INCREMENT,
    title VARCHAR(64) NOT NULL,
    description VARCHAR(512) NOT NULL,
    url VARCHAR(1024) UNIQUE NOT NULL,
    approved BOOL NOT NULL DEFAULT FALSE,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    type_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (type_id) REFERENCES types(id)
);

INSERT INTO resources
    (
        id,
        title,
        description,
        url,
        type_id
    )
VALUES
    (
        1,
        'Ranger: JS Range Syntax for Anything',
        'Ranger is a small JS library that allows you to use a range-like syntax with any object. All you need to do is to define a function that builds the required ''range'' given a starting and ending object (+ optional extra parameters if you so desire).',
        'https://dev.to/jonrandy/ranger-js-range-syntax-for-anything-4djc',
        1
    ),
    (
        2,
        'The JavaScript Playground in your Editor',
        'Quokka makes exploring, learning, and testing JavaScript / TypeScript blazingly fast. By default no config is required, simply open a new Quokka file and start experimenting. Focus on writing code instead of writing bespoke config files just to try a simple idea or learn a new language feature.',
        'https://quokkajs.com/',
        1
    ),
    (
        3,
        'How to Create and Use Virtual Environments in Python With Poetry',
        'It can be tricky when different packages in Python don''t play nice. The solution to this problem is to create an isolated, virtual environment. In this video, I’ll show you how to set up a virtual environment with poetry, a package manager for Python that simplifies dependency management and project packaging.',
        'https://www.youtube.com/watch?v=0f3moPe_bhk',
        3
    ),
    (
        4,
        'Python Again with Jason C. McDonald',
        'A second Jason joins this episode of Programming Throwdown! Jason McDonald – Python evangelist, author, and more – talks to Patrick and Jason about his experience with the programming language, how his disability helped and hindered his software career, and where its strengths and weaknesses lie.',
        'https://www.programmingthrowdown.com/2023/03/154-python-again-with-jason-c-mcdonald.html',
        2
    );
DROP TABLE IF EXISTS categories_resources;

CREATE TABLE categories_resources (
    id INT UNSIGNED AUTO_INCREMENT,
    category_id INT UNSIGNED NOT NULL,
    resource_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (resource_id) REFERENCES resources(id),
    UNIQUE (category_id, resource_id)
);

INSERT INTO categories_resources
    (
        category_id,
        resource_id
    )
VALUES
    (
        1,
        1
    ),
    (
        6,
        1
    ),
    (
        1,
        2
    ),
    (
        5,
        2
    ),
    (
        4,
        3
    ),
    (
        4,
        4
    );
DROP TABLE IF EXISTS histories;

CREATE TABLE histories (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    resource_id INT UNSIGNED NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (resource_id) REFERENCES resources(id),
    UNIQUE (user_id, resource_id)
);
