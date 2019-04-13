/*
DROP TABLE subscriptions;
DROP TABLE articles;
DROP TABLE feeds;
DROP TABLE users;
*/

-- user table
CREATE TABLE users (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(127) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	created DATETIME DEFAULT CURRENT_TIMESTAMP,
	seen DATETIME
);

-- link of the feed
CREATE TABLE feeds (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(64) NOT NULL UNIQUE,
	summary VARCHAR(512),
  link varchar(128) NOT NULL UNIQUE,
	updated DATETIME DEFAULT NOW()
);

-- article table
CREATE TABLE articles (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	feed_id INT NOT NULL,
	title VARCHAR (255) NOT NULL,
	link VARCHAR (255) NOT NULL,
	pubDate DATETIME NOT NULL,
	summary VARCHAR (511),
	CONSTRAINT articles_fk_feed FOREIGN KEY (feed_id) REFERENCES feeds (id)
);

-- subscriptions table
CREATE TABLE subscriptions (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INT NOT NULL,
	feed_id INT NOT NULL,
	CONSTRAINT subscription_fk_user FOREIGN KEY (user_id) REFERENCES users (id),
	CONSTRAINT subscription_fk_feed FOREIGN KEY (feed_id) REFERENCES feeds (id)
);

-- entries of read articles by the user
-- should probably make this an unread tracker to reduce overhead
/*CREATE TABLE read (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INT NOT NULL,
	article_id INT NOT NULL,
	CONSTRAINT read_fk_user FOREIGN KEY (user_id) REFERENCES users (id),
	CONSTRAINT read_fk_article FOREIGN KEY (article_id) REFERENCES article (id)
);*/
