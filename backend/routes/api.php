<?php
// Cargamos el controlador que atenderá estas rutas
require_once __DIR__ . '/../controllers/HealthController.php';
// Registramos método HTTP, ruta y acción correspondiente
return [
	'GET /api/health' => [HealthController::class, 'check'],
	'GET /api/health/db' => [HealthController::class, 'database'],
	'POST /api/health/echo' => [HealthController::class, 'echoData']
];
