<?php
header('Content-Type: application/json; charset=utf-8');
// Cargamos el manejador central de excepciones
require_once __DIR__ . '/../handlers/ExceptionHandler.php';
require_once __DIR__ . '/../handlers/HttpException.php';

try {
	// Recuperamos el método y la ruta solicitada
	$method = $_SERVER['REQUEST_METHOD'];
	$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	// Cargamos las rutas y el Middleware
	$routes = require __DIR__ . '/../routes/api.php';
	require_once __DIR__ . '/../middleware/JsonMiddleware.php';
	// Buscamos una ruta exacta o dinámica
	$matchedRoute = null;
	$routeParams = [];

	foreach ($routes as $route => $handler) {
		// Convertimos {id} en un parámetro numérico
		$pattern = preg_replace('/\{id\}/', '([0-9]+)', $route);
		$pattern = '#^' . $pattern . '$#';

		if (preg_match($pattern, "$method $path", $matches)) {
			$matchedRoute = $handler;
			if (isset($matches[1])) {
				$routeParams['id'] = (int) $matches[1];
			}
			break;
		}
	}
	// Si no encontramos la ruta, generamos una excepción
	if ($matchedRoute === null) {
		throw new HttpException('Ruta no encontrada', 404);
	}
	// Ejecutamos el Middleware
	$middlewareResult = JsonMiddleware::handle();
	if ($middlewareResult['error']) {
		throw new HttpException($middlewareResult['message'], $middlewareResult['status']);
	}
	// Ejecutamos el Controller
	[$controller, $action] = $matchedRoute;
	$response = $controller::$action(
		$middlewareResult['data'],
		$routeParams['id'] ?? null
	);
} catch (Throwable $error) {
	// El manejador decide cómo responder al error
	$response = ExceptionHandler::handle($error);
}
// Enviamos la respuesta JSON
echo json_encode($response);
