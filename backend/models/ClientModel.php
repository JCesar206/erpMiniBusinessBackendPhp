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

	// Busca un cliente por su identificador
	public static function getById(int $id): ?array
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("SELECT id, name, email, phone, created_at FROM clients WHERE id=:id");
		$query->execute(['id' => $id]);
		// Devuelve el cliente o null si no existe
		return $query->fetch() ?: null;
	}

	// Actualiza los datos de un cliente
	public static function update(int $id, array $data): bool
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("UPDATE clients SET name=:name, email=:email, phone=:phone WHERE id=:id");
		$query->execute([
			'id' => $id,
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => $data['phone']
		]);
		// Indica si se modificó alguna fila
		return $query->rowCount() > 0;
	}

	//  Elimina un cliente por su ID
	public static function delete(int $id): bool
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("DELETE FROM clients WHERE id=:id");
		$query->execute(['id' => $id]);
		return $query->rowCount() > 0;
	}
}
