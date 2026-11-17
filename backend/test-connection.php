<?php
// Cargamos la conexción que acabamos de configurar.
try {
	$pdo = require __DIR__ . '/config/database.php';
	// Comprobamos que PDO recibió una conexión válida
	echo "Conexión con MariaDB exitosa";
} catch (Throwable $error) {
	// Mostramos el detalle únicamente durante esta prueba local
	echo "Error de conexión: " . $error->getMessage();
}
