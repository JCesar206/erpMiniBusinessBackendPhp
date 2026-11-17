<?php
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
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

if ($matchedRoute === null) {
	http_response_code(404);
	echo json_encode([
		'success' => false,
		'message' => 'Ruta no encontrada'
	]);
	exit;
}

// Ejecutamos el Middleware
$middlewareResult = JsonMiddleware::handle();
if ($middlewareResult['error']) {
	http_response_code($middlewareResult['status']);
	echo json_encode([
		'success' => false,
		'message' => $middlewareResult['message']
	]);
	exit;
}

// Ejecutamos el Controller
[$controller, $action]  = $matchedRoute;
$response = $controller::$action(
	$middlewareResult['data'],
	$routeParams['id'] ?? null
);

echo json_encode($response);
