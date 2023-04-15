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
    )

