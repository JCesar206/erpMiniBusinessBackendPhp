<?php
// Cargamos Composer y las variables del archivo .env.
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$backendPath = dirname(__DIR__);
$dotenv = Dotenv::createImmutable($backendPath);
$dotenv->load();

// Recuperamos la configuración.
$environment = $_ENV['APP_ENV'] ?? '';
$host = $_ENV['DB_HOST'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';

// Evitamos nombres de base de datos que puedan alterar el SQL.
if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
	exit("Nombre de base de datos no válido.\n");
}

// Identificamos el comando solicitado.
$command = $argv[1] ?? '';

if (!in_array($command, ['init', 'seed', 'reset'], true)) {
	exit("Uso: php database/run.php [init|seed|reset]\n");
}

// Reset es destructivo: requiere entorno local y confirmación.
if ($command === 'reset') {

	if ($environment !== 'local' || $database !== 'erp_minibusiness') {
		exit("RESET BLOQUEADO: entorno o base de datos no autorizados.\n");
	}

	echo "ATENCION: se eliminaran TODOS los datos de $database.\n";
	echo "Escribe RESET para confirmar: ";

	$confirmation = trim(fgets(STDIN));

	if ($confirmation !== 'RESET') {
		exit("Operacion cancelada.\n");
	}
}

try {

	// Conectamos al servidor MariaDB, sin seleccionar una base de datos.
	$dsn = "mysql:host=$host;port=$port;charset=utf8mb4";

	$pdo = new PDO($dsn, $username, $password, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);

	// RESET elimina y vuelve a crear la base de datos.
	if ($command === 'reset') {
		$pdo->exec("DROP DATABASE `$database`");
	}

	// INIT crea la base de datos si todavía no existe.
	if ($command === 'init' || $command === 'reset') {

		$pdo->exec(
			"CREATE DATABASE IF NOT EXISTS `$database`
             CHARACTER SET utf8mb4
             COLLATE utf8mb4_unicode_ci"
		);
		echo "Base de datos preparada.\n";
	}

	// Seleccionamos la base de datos de nuestro proyecto.
	$pdo->exec("USE `$database`");

	// Creamos el registro de migraciones aplicadas.
	$pdo->exec("
        CREATE TABLE IF NOT EXISTS schema_migrations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

	// Aplicamos las migraciones pendientes.
	if ($command === 'init' || $command === 'reset') {

		$migrationFiles = glob(__DIR__ . '/migrations/*.sql');
		sort($migrationFiles);

		foreach ($migrationFiles as $file) {

			$migration = basename($file);
			$check = $pdo->prepare(
				'SELECT COUNT(*) FROM schema_migrations WHERE migration = ?'
			);

			$check->execute([$migration]);

			if ((int) $check->fetchColumn() > 0) {
				echo "Migracion ya aplicada: $migration\n";
				continue;
			}

			// Cada archivo de migración contiene una sentencia SQL.
			$sql = file_get_contents($file);
			$pdo->exec($sql);
			$register = $pdo->prepare(
				'INSERT INTO schema_migrations (migration) VALUES (?)'
			);

			$register->execute([$migration]);
			echo "Migracion aplicada: $migration\n";
		}
	}

	// Ejecutamos los Seeds cuando se solicitan.
	if ($command === 'seed' || $command === 'reset') {

		$seedFiles = glob(__DIR__ . '/seeds/*.sql');
		sort($seedFiles);

		foreach ($seedFiles as $file) {
			$seed = basename($file);
			$sql = file_get_contents($file);
			$pdo->exec($sql);
			echo "Seed ejecutado: $seed\n";
		}
	}
	echo "Proceso terminado correctamente.\n";
} catch (Throwable $error) {

	// Mostramos el error en consola para facilitar la depuración.
	fwrite(STDERR, "Error: " . $error->getMessage() . "\n");
	exit(1);
}
