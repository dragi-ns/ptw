DROP TABLE IF EXISTS resources;

CREATE TABLE resources (
    id INT UNSIGNED AUTO_INCREMENT,
    title VARCHAR(64) NOT NULL,
    description VARCHAR(512) NOT NULL,
    url VARCHAR(1024) UNIQUE NOT NULL,
    approved BOOL NOT NULL DEFAULT TRUE,
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
    ),
    (
        5,
        'Ways to Create an Enum in JavaScript',
        'An enum is a set of named constants. Plain objects, frozen objects, proxied objects or a class-based approach provide some ways to create enums in JavaScript.',
        'https://dmitripavlutin.com/javascript-enum/',
        1
    ),
    (
        6,
        'Recreating a "Mouse-Aware" Text Effect',
        'The author was “so impressed with the design” of the Creative South 2023 site that she wanted to recreate the neat ‘3D’ effect seen on the title text. Here’s a quick look at how she did it, complete with code and a demo.',
        'https://rachsmith.com/recreating-creative-south-title/',
        1
    );
