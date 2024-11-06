CREATE TABLE users (
                       id INT PRIMARY KEY AUTO_INCREMENT,
                       user_type ENUM('person', 'company') NOT NULL,
                       first_name VARCHAR(50) DEFAULT NULL,
                       birth_date DATE DEFAULT NULL,
                       company_name VARCHAR(100) DEFAULT NULL,
                       email VARCHAR(100) NOT NULL UNIQUE,
                       nip VARCHAR(10) DEFAULT NULL UNIQUE,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                       CHECK (
                           (user_type = 'person' AND first_name IS NOT NULL AND birth_date IS NOT NULL AND company_name IS NULL AND nip IS NULL)
                               OR
                           (user_type = 'company' AND first_name IS NULL AND birth_date IS NULL AND company_name IS NOT NULL AND nip IS NOT NULL)
                           )
);

CREATE UNIQUE INDEX unique_email ON users(email);
CREATE UNIQUE INDEX unique_nip ON users(nip);

DELIMITER //
CREATE TRIGGER set_created_at
    BEFORE INSERT ON users
    FOR EACH ROW
BEGIN
    SET NEW.created_at = CURRENT_TIMESTAMP;
END;
//
DELIMITER ;
