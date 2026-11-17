<?php
// Cargamos el Model encargado de consultar los clientes
require_once __DIR__ . '/../models/ClientModel.php';

class ClientService
{
	// Obtiene la lista de clientes
	public static function getAll(): array
	{
		// Solicitamos los datos al Model
		return ClientModel::getAll();
	}

	// Valida y registra un cliente
	public static function create(array $data): int
	{
		// Validamos el nombre
		$name = trim($data['name'] ?? '');
		if ($name === '') {
			throw new InvalidArgumentException(('El nombre es obligatorio'));
		}
		// Validamos el correo si fue proporcionado
		$email = trim($data['email'] ?? '');
		if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException(('El correo electrónico no es válido'));
		}
		// Normalizamos los datos
		$data['name'] = $name;
		$data['email'] = $email !== '' ? $email : null;
		$data['phone'] = trim($data['phone'] ?? '') ?: null;
		// Delegamos el registro al Model
		return ClientModel::create($data);
	}
}
