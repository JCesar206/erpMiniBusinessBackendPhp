<?php
class ProductModel
{
	// Consultar todos los productos
	public static function getAll(): array
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->query("SELECT id, name, description, price, stock, created_at FROM products ORDER By id DESC");
		return $query->fetchAll();
	}

	// Registrar un producto
	public static function create(array $data): int
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (:name, :description, :price, :stock");
		$query->execute([
			'name' => $data['name'],
			'description' => $data['description'],
			'price' => $data['price'],
			'stock' => $data['stock']
		]);
		return (int) $pdo->lastInsertId();
	}

	// Consultar un producto por ID
	public static function getById(int $id): ?array
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("SELECT id, name, description, price, stock, created_at FROM products WHERE id=:id");
		$query->execute(['id' => $id]);
		return $query->fetch() ?: null;
	}

	// Actualizamos el producto
	public static function update(int $id, array $data): bool
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("UPDATE products SET name=:name, description=:description, price=:price, stock=:stock WHERE id=:id");
		$query->execute([
			'id' => $id,
			'name' => $data['name'],
			'description' => $data['description'],
			'price' => $data['price'],
			'stock' => $data['stock']
		]);
		return $query->rowCount() > 0;
	}

	// Eliminamos un producto
	public static function delete(int $id): bool
	{
		$pdo = require __DIR__ . '/../config/database.php';
		$query = $pdo->prepare("DELETE FROM products WHERE id=:id");
		$query->execute(['id' => $id]);
		return $query->rowCount() > 0;
	}
}
