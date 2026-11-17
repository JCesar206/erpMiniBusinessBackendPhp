<?php
// Cargamos el Service de clientes
require_once __DIR__ . '/../services/ClientService.php';
class ClientController
{
	// Atiende la solicitud para consultar clientes
	public static function index(?array $data = null): array
	{
		try {
			// Solicitamos los clientes al Service
			$clients = ClientService::getAll();
			return [
				'success' => true,
				'total' => count($clients),
				'data' => $clients
			];
		} catch (Throwable $error) {
			error_log('Error en ClientController: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' => 'Error al consultar los clientes'
			];
		}
	}
}
