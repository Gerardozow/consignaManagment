CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Usuario de prueba
INSERT INTO usuarios (usuario, password) VALUES (
    'admin',
    -- Password: admin123
    '$2y$10$rDN4upjG8GbB3.t/sQRMzOyPjE1Fgi3fUeS.g1nXe/jAAsxqlnQy6'
);
