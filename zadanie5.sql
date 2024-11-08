CREATE TABLE Users (
                       user_id INT PRIMARY KEY,
                       name VARCHAR(100),
                       role ENUM('Pracownik', 'Kierownik', 'Dyrektor', 'Zastępca Kierownika', 'Zastępca Dyrektora'),
                       is_on_leave BOOLEAN DEFAULT FALSE
);

CREATE TABLE Documents (
                           document_id INT PRIMARY KEY,
                           title VARCHAR(255),
                           content TEXT,
                           prepared_by INT,
                           approved_by INT,
                           approved_date DATETIME,
                           approved_substitute INT,
                           confirmed_by INT,
                           confirmed_date DATETIME,
                           confirmed_substitute INT,
                           FOREIGN KEY (prepared_by) REFERENCES Users(user_id),
                           FOREIGN KEY (approved_by) REFERENCES Users(user_id),
                           FOREIGN KEY (approved_substitute) REFERENCES Users(user_id),
                           FOREIGN KEY (confirmed_by) REFERENCES Users(user_id),
                           FOREIGN KEY (confirmed_substitute) REFERENCES Users(user_id)
);
