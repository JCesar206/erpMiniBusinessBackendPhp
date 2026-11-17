<?php
// Cargamos el sistema de autoload de Composer.
require_once dirname(__DIR__) . '/vendor/autoload.php';
// Importamos la biblioteca para leer archivos .env
use Dotenv\Dotenv;
// Localizamos la carpeta backend
$backendPath = dirname(__DIR__);
// Cargamos las variables del archivo .env
$dotenv = Dotenv::createImmutable($backendPath);
$dotenv->load();
// Recuperamos los datos de conexión.
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
// Construimos la dirección de conexión
$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
// Definimos las opciones de PDO
$options = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false,
];
// Devolvemos la conexió para que otros archivos puedan utilizarla
return new PDO($dsn, $username, $password, $options);
