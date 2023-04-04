CREATE DATABASE customers_api;

USE customers_api;

CREATE TABLE customers
(
    id INT unsigned NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(150) NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO customers ( name, email, phone) VALUES
                                                ( 'test1', 'test1@mail.com', '123456789' ),
                                                ( 'test2', 'test2@mail.com', '234567890' );
