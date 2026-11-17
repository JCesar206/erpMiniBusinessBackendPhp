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
}
