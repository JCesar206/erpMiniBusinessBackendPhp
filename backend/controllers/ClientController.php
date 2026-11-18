<?php
// Cargamos el service de clientes
require_once __DIR__ . '/../services/ClientService.php';
class ClientController
{
	// Consulta todos los clientes
	public static function index(?array $data = null): array
	{
		$clients = ClientService::getAll();
		return [
			'success' => true,
			'total' => count($clients),
			'data' => $clients
		];
	}

	// Registra un nuevo cliente
	public static function store(array $data): array
	{
		$id = ClientService::create($data);
		http_response_code(201);
		return [
			'success' => true,
			'message' => 'Cliente registrado correctamente',
			'id' => $id
		];
	}

	// Consulta un cliente por su ID
	public static function show(?array $data = null, ?int $id = null): array
	{
		$client = ClientService::getById($id ?? 0);
		if ($client === null) {
			throw new HttpException('Cliente no encontrado', 404);
		}
		return [
			'success' => true,
			'message' => $client
		];
	}

	// Actualiza un cliente
	public static function update(array $data, ?int $id = null): array
	{
		$updated = ClientService::update($id ?? 0, $data);
		if (!$updated) {
			throw new HttpException('Cliente no encontrado o sin cambios', 404);
		}
		return [
			'success' => true,
			'message' => 'Cliente actualizado correctamente'
		];
	}

	// Elimina un cliente
	public static function destroy(?array $data = null, ?int $id = null): array
	{
		$deleted = ClientService::delete($id ?? 0);
		if (!$deleted) {
			throw new HttpException('Cliente no encontrado', 404);
		}
		return [
			'success' => true,
			'message' => 'Cliente eliminado correctamente'
		];
	}
}
