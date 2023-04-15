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
