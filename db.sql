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

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    post BIGINT UNSIGNED NOT NULL,
    author BIGINT UNSIGNED NOT NULL,
    reply_to BIGINT UNSIGNED,
    anchor BIGINT UNSIGNED,
    content VARCHAR(1000) NOT NULL,
    post_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post) REFERENCES posts(id),
    FOREIGN KEY (author) REFERENCES users(id),
    FOREIGN KEY (reply_to) REFERENCES comments(id),
    FOREIGN KEY (anchor) REFERENCES comments(id)
);

CREATE TABLE comment_reactions (
    id SERIAL PRIMARY KEY,
    comment BIGINT UNSIGNED NOT NULL,
    user BIGINT UNSIGNED NOT NULL,
    value TINYINT(1) NOT NULL,
    FOREIGN KEY (comment) REFERENCES comments(id),
    FOREIGN KEY (user) REFERENCES users(id),
    CONSTRAINT unique_user_and_comment_constraint UNIQUE(comment, user)
);

CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20),
    CONSTRAINT unique_name UNIQUE(name)
);

CREATE TABLE post_tag (
    id SERIAL PRIMARY KEY,
    post BIGINT UNSIGNED NOT NULL,
    tag BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (post) REFERENCES posts(id),
    FOREIGN KEY (tag) REFERENCES tags(id),
    CONSTRAINT unique_post_and_tag_constraint UNIQUE(post, tag)
);