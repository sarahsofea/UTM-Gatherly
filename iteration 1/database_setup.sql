-- Create the users table if it does not exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255)
);

INSERT INTO users (username, email, password) VALUES
(
    'Syuhada',                  
    'Syuhada@gmail.com',      
    'Syuhada23'                
);


