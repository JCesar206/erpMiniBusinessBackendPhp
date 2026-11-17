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

	// Atiende las solicitudes para registrar clientes
	public static function store(array $data): array
	{
		try {
			// Solicitamos al Service registrar el cliente
			$id = ClientService::create($data);
			http_response_code(201);
			return [
				'success' => true,
				'message' => 'Cliente registrado correctamente',
				'id' => $id
			];
		} catch (InvalidArgumentException $error) {
			http_response_code(422);
			return [
				'success' => false,
				'message' => $error->getMessage()
			];
		} catch (PDOException $error) {
			error_log('Error SQL en ClientController: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' => 'No fue posible registrar el cliente'
			];
		} catch (Throwable $error) {
			error_log('Error en ClientController: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' > 'Error interno del servidor'
			];
		}
	}

	// Atiende la consulta individual de un cliente
	public static function show(?array $data = null, ?int $id = null): array
	{
		try {
			$client = ClientService::getById($id ?? 0);
			if ($client === null) {
				http_response_code(404);
				return [
					'success' => false,
					'message' => 'Cliente no encontrado'
				];
			}
			return [
				'success' => true,
				'data' => $client
			];
		} catch (InvalidArgumentException $error) {
			http_response_code(422);
			return [
				'success' => false,
				'message' => $error->getMessage()
			];
		} catch (Throwable $error) {
			error_log('Error en ClientController: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' => 'Error interno del servidor'
			];
		}
	}

	// Atiende la actualización de un cliente.
	public static function update(array $data, ?int $id = null): array
	{
		try {
			$updated = ClientService::update($id ?? 0, $data);
			if (!$updated) {
				http_response_code(404);
				return [
					'success' => false,
					'message' => 'Cliente no encontrado o sin cambios'
				];
			}
			return [
				'success' => true,
				'message' => 'Cliente actualizado correctamente'
			];
		} catch (InvalidArgumentException $error) {
			http_response_code(422);
			return [
				'success' => false,
				'message' => $error->getMessage()
			];
		} catch (PDOException $error) {
			error_log('Error SQL en ClientController: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' => 'No fue posible actualizar el cliente'
			];
		} catch (Throwable $error) {
			error_log('Error en ClientController: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' => 'Error interno del servidor'
			];
		}
	}
}
