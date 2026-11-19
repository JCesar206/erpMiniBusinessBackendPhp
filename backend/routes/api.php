<?php
// Cargamos el controlador que atenderá estas rutas
require_once __DIR__ . '/../controllers/HealthController.php';
require_once __DIR__ . '/../controllers/ClientController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
// Registramos método HTTP, ruta y acción correspondiente
return [
	'GET /api/health' => [HealthController::class, 'check'],
	'GET /api/health/db' => [HealthController::class, 'database'],
	'POST /api/health/echo' => [HealthController::class, 'echoData'],
	'GET /api/clients' => [ClientController::class, 'index'],
	'POST /api/clients' => [ClientController::class, 'store'],
	'GET /api/clients/{id}' => [ClientController::class, 'show'],
	'PUT /api/clients/{id}' => [ClientController::class, 'update'],
	'DELETE /api/clients/{id}' => [ClientController::class, 'destroy'],
	'GET /api/products' => [ProductController::class, 'index'],
	'POST /api/products' => [ProductController::class, 'store'],
	'GET /api/products/{id}' => [ProductController::class, 'show'],
	'PUT /api/products/{id}' => [ProductController::class, 'update'],
	'DELETE /api/products/{id}' => [ProductController::class, 'destroy'],
];
