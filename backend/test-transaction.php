<?php
require_once __DIR__ . '/models/InvoiceModel.php';
try {
	// Iniciamos la transacción
	$pdo = InvoiceModel::beginTransaction();
	// Insertamos un cliente de prueba
	$query = $pdo->prepare("INSERT INTO clients (name, email, phone) VALUES (:name, :email, :phone)");
	$query->execute([
		'name' => 'Cliente Transaccion',
		'email' => 'transaccion@test.com',
		'phone' => '5551234567'
	]);
	$id = $pdo->lastInsertId();
	// Deshacemos todos los cambios
	InvoiceModel::rollBack($pdo);
	echo "Transaccion revertida correctamente. ID temporal: " . $id;
} catch (Throwable $error) {
	if (isset($pdo)) {
		InvoiceModel::rollBack($pdo);
	}
	echo "Error: " . $error->getMessage();
}
