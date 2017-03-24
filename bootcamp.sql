CREATE USER 'helmes'@'localhost'
  IDENTIFIED BY 'bootcamp';
CREATE DATABASE bookings;
GRANT ALL ON bookings.* TO 'helmes'@'localhost';
FLUSH PRIVILEGES;

USE bookings;
CREATE TABLE booking (
  id      INT AUTO_INCREMENT NOT NULL,
  datetime VARCHAR(16)       NOT NULL,
  details VARCHAR(2000)      NOT NULL,
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
