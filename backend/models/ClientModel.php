<?php
class ClientModel
{
	// Recupera todos los clientes de la base de datos
	public static function getAll(): array
	{
		// Solicitamos la conexión PDO
		$pdo = require __DIR__ . '/../config/database.php';
		// Preparamos la consulta SQL
		$query = $pdo->query("SELECT id, name, email, phone, created_at FROM clients ORDER BY id DESC");
		// Convertimos el resultado en un array PHP
		return $query->fetchAll();
	}

	// Registra un cliente nuevo en MariaDB
	public static function create(array $data): int
	{
		// Recuperamos la conexción PDO
		$pdo = require __DIR__ . '/../config/database.php';
		// Preparamos la consulta para insertar el cliente
		$query = $pdo->prepare("INSERT INTO clients (name, email, phone) VALUES (:name, :email, :phone)");
		// Enviamos los valores de forma segura
		$query->execute([
			'name' => $data['name'],
			'email' => $data['email'] ?? null,
			'phone' => $data['phone'] ?? null
		]);
		// Devolvemos el ID del cliente recién creado
		return (int) $pdo->lastInsertId();
	}
}
