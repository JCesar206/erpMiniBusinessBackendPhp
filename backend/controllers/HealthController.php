<?php
// Cargamos el Service encargado de la lógica
require_once __DIR__ . '/../services/HealthService.php';
class HealthController
{
	// Devuelve el estado general de la API
	public static function check(?array $data = null): array
	{
		return HealthService::check();
	}
	// Comprueba la conexión con la base de datos
	public static function database(?array $data = null): array
	{
		try {
			// Delegamos la comprobación al Service
			HealthService::checkDatabase();
			return [
				'success' => true,
				'database' => 'connected'
			];
		} catch (Throwable $error) {
			// Registramos el error en la terminal del servidor
			error_log('Error en HealthService: ' . $error->getMessage());
			http_response_code(503);
			return [
				'success' => false,
				'database' => 'disconnected'
			];
		}
	}
	// Devuelve los datos recibidos después del Middleware
	public static function echoData(array $data): array
	{
		return [
			'success' => true,
			'message' => 'JSON recibido correctamente',
			'received' => $data
		];
	}
}
