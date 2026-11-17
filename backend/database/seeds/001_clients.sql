INSERT INTO clients (name, email, phone)
VALUES
    ('Carlos Ramirez', 'carlos@example.com', '5551234567'),
    ('Maria Lopez', 'maria@example.com', '5559876543'),
    ('Empresa Demo', 'empresa@example.com', '5551112233')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    phone = VALUES(phone);