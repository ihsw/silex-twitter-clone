CREATE DATABASE silex_twitter_clone;
USE silex_twitter_clone;
CREATE TABLE Post (
	id INT NOT NULL AUTO_INCREMENT,
	occurredAt DATETIME NOT NULL,
	content VARCHAR(140) NOT NULL,
	PRIMARY KEY (id)
);