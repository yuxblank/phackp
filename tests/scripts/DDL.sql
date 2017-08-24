CREATE TABLE IF NOT EXISTS category
(
  id    INT AUTO_INCREMENT
    PRIMARY KEY,
  title VARCHAR(200) NULL
);
CREATE TABLE IF NOT EXISTS comment
(
  id      INT AUTO_INCREMENT
    PRIMARY KEY,
  text    VARCHAR(255) NULL,
  post_id INT          NULL
);

CREATE TABLE IF NOT EXISTS post
(
  id           INT AUTO_INCREMENT
    PRIMARY KEY,
  title        VARCHAR(200) NULL,
  content      TEXT         NULL,
  category_id  INT          NULL,
  date_created DATETIME     NULL
);

CREATE TABLE IF NOT EXISTS tag
(
  id      INT AUTO_INCREMENT
    PRIMARY KEY,
  tag     VARCHAR(200) NULL,
  post_id INT          NULL
);