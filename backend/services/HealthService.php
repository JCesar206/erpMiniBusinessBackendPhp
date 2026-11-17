<?php
class HealthService
{
	// Comprueba el estado general de la API
	public static function check(): array
	{
		return [
			'success' => true,
			'message' => 'API funcionando'
		];
	}
	// Comprueba que podemos comunicarnos con MariaDB
	public static function checkDatabase(): bool
	{
		// Recuperamos la conexión PDO
		$pdo = require __DIR__ . '/../config/database.php';
		// Comprobamos qué recibimos
		if (!$pdo instanceof PDO) {
			throw new RuntimeException('La configuración no devolvió un objeto PDO');
		}
		// Ejecutamos una consulta sencilla
		$pdo->query('SELECT 1');
		return true;
	}
}
