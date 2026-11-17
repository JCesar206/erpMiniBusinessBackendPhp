<?php
// Definimos el formato de nuestras respuestas.
header('Content-Type: application/json; charset=utf-8');
// Obtenemos el método y la ruta solicitada
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Cargamos las rutas y el Middleware
$routes = require __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../middleware/JsonMiddleware.php';
// Construimos la identificación de la ruta
$routeKey = "$method $path";
// Comprobamos si la ruta existe
if (!isset($routes[$routeKey])) {
	http_response_code(404);
	echo json_encode([
		'success' => false,
		'message' => 'Ruta no encontrada'
	]);
	exit;
}
// Ejecutamos Middleware antes de llegar al Controller
$middlewareResult = JsonMiddleware::handle();
// Si Middleware detecta un error, detenemos la solicitud.
if ($middlewareResult['error']) {
	http_response_code($middlewareResult['status']);
	echo json_encode([
		'success' => false,
		'message' => $middlewareResult['message']
	]);
	exit;
}
// Recuperamos el Controller y su método
[$controller, $action] = $routes[$routeKey];
// Entregamos al Controller los datos validados
$requestData = $middlewareResult['data'];
$response = $controller::$action($requestData);
// Enviamos la respuesta
echo json_encode($response);
