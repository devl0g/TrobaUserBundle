--
-- Table structure for table roles
--

CREATE TABLE IF NOT EXISTS roles (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  created_at datetime NOT NULL,
  modified_at datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table roles_users
--

CREATE TABLE IF NOT EXISTS roles_users (
  user_id int(11) NOT NULL,
  role_id int(11) NOT NULL,
  KEY user_id (user_id),
  KEY role_id (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table users
--

CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  salt varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  last_login datetime DEFAULT NULL,
  created_at datetime NOT NULL,
  modified_at datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY unique_username (username),
  UNIQUE KEY unique_email (email)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table roles_users
--
ALTER TABLE roles_users
ADD CONSTRAINT FOREIGN KEY (role_id) REFERENCES roles (id),
ADD CONSTRAINT FOREIGN KEY (user_id) REFERENCES users (id);