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
