<?php
// Cargamos el controlador que atenderá estas rutas
require_once __DIR__ . '/../controllers/HealthController.php';
require_once __DIR__ . '/../controllers/ClientController.php';
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
];
