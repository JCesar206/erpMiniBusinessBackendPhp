
<?php
class JsonMiddleware
{
	public static function handle(): array
	{
		// Identificamos el método HTTP.
		$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
		// Solo validamos el cuerpo de solicitudes que envían datos.
		if (!in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
			return [
				'error' => false,
				'data' => []
			];
		}

		// Comprobamos que el cliente envíe contenido JSON.
		$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

		if (stripos($contentType, 'application/json') !== 0) {
			return [
				'error' => true,
				'status' => 415,
				'message' => 'Se requiere Content-Type application/json'
			];
		}

		// Leemos el cuerpo original de la solicitud.
		$rawBody = file_get_contents('php://input');
		// Convertimos el JSON recibido en un array PHP.
		$data = json_decode($rawBody, true);
		// Comprobamos que el JSON sea válido y tenga estructura de objeto o array.
		if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
			return [
				'error' => true,
				'status' => 400,
				'message' => 'El JSON enviado no es válido'
			];
		}
		// Entregamos los datos ya validados.
		return [
			'error' => false,
			'data' => $data
		];
	}
}
