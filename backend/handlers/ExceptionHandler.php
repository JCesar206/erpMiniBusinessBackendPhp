<?php
class ExceptionHandler
{
	public static function handle(Throwable $error): array
	{
		// Errores HTTP con código de  respuesta definido
		if ($error instanceof HttpException) {
			http_response_code($error->getStatus());
			return [
				'success' => false,
				'message' => $error->getMessage()
			];
		}

		// Error de validación: el usuario envió datos incorrectos
		if ($error instanceof InvalidArgumentException) {
			http_response_code(422);
			return [
				'success' => false,
				'message' => $error->getMessage()
			];
		}

		// Error relacionado con la base de datos
		if ($error instanceof PDOException) {
			error_log('Error de base de datos: ' . $error->getMessage());
			http_response_code(500);
			return [
				'success' => false,
				'message' => 'Error al comunicarse con la base de datos'
			];
		}

		// Cualquier otro error no contemplado
		error_log('Error interno: ' . $error->getMessage());
		http_response_code(500);
		return [
			'success' => false,
			'message' => 'Error interno del servidor'
		];
	}
}
