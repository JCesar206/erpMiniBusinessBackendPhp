<?php
// Indicamos que todas las respuestas serán JSON
header('Content-Type: application/json; charset=utf-8');
// Obtenemos el método HTTP y la ruta solicitada
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Cargamos las rutas registradas
$routes = require __DIR__ . '/../routes/api.php';
// Construimos la llave para localizar la ruta
$routeKey = "$method $path";
// Comprobamos si existe la ruta solicitada
if (!isset($routes[$routeKey])) {
	http_response_code(404);
	echo json_encode([
		'succes' => false,
		'message' => 'Ruta no encontrada'
	]);
	exit;
}
// Recuperamos el controlador y su método
[$controller, $action] = $routes[$routeKey];
// Ejecuatamos la acción correspondiente
$response = $controller::$action();
// Enviamos la respuesta JSON
echo json_encode($response);
