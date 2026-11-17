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
}
