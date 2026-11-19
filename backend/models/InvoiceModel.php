<?php
class InvoiceModel
{
	// Iniciar una transacción
	public static function beginTransaction(): PDO
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$pdo->beginTransaction();
		return $pdo;
	}
	// Confirmar los cambios
	public static function commit(PDO $pdo): void
	{
		$pdo->commit();
	}
	// Revertir los cambios
	public static function rollBack(PDO $pdo): void
	{
		if ($pdo->inTransaction()) {
			$pdo->rollBack();
		}
	}
}
