<?php
class HealthController
{
	// Comprueba que la API está funcionando
	public static function check(?array $data = null): array
	{
		return [
			'success' => true,
			'message' => 'API funcionando'
		];
	}
	// Comprueba que PHP puede consultar MariaDB
	public static function database(?array $data = null): array
	{
		try {
			$pdo = require __DIR__ . '/../config/database.php';
			$pdo->query('SELECT 1');
			return [
				'success' => true,
				'database' => 'connected'
			];
		} catch (Throwable $error) {
			http_response_code(503);
			return [
				'success' => false,
				'database' => 'disconnected'
			];
		}
	}

	// Devuelve los datos después de pasar por el Middleware
	public static function echoData(array $data): array
	{
		return [
			'success' => true,
			'message' => 'JSON recibido corretamente',
			'received' => $data
		];
	}
}
