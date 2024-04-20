CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE,
    passwd VARCHAR(60) NOT NULL,
    registration_date DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    administrator_rights BOOLEAN NOT NULL
);

CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    author BIGINT UNSIGNED NOT NULL,
    title TINYTEXT NOT NULL,
    file_name VARCHAR(100) NOT NULL,
    post_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author) REFERENCES users(id)
);

CREATE TABLE reactions (
    id SERIAL PRIMARY KEY,
    post BIGINT UNSIGNED NOT NULL,
    user BIGINT UNSIGNED NOT NULL,
    value TINYINT(1) NOT NULL,
    FOREIGN KEY (post) REFERENCES posts(id),
    FOREIGN KEY (user) REFERENCES users(id),
    CONSTRAINT unique_user_and_post_constraint UNIQUE(post, user)
);