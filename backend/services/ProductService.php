<?php
// Cargamos el Model de productos
require_once __DIR__ . '/../models/ProductModel.php';
class ProductService
{
	// Consulta todos los productos
	public static function getAll(): array
	{
		return ProductModel::getAll();
	}
	// Registra un producto
	public static function create(array $data): int
	{
		$data = self::validateData($data);
		return ProductModel::create($data);
	}

	// Consulta un producto por ID
	public static function getById(int $id): ?array
	{
		if ($id <= 0) {
			throw new InvalidArgumentException('ID no válido');
		}
		return ProductModel::getById($id);
	}

	// Actualiza un producto
	public static function update(int $id, array $data): bool
	{
		if ($id <= 0) {
			throw new InvalidArgumentException('ID no válido');
		}
		$data = self::validateData($data);
		// Comprobamos que el producto exista
		$product = ProductModel::getById($id);
		if ($product === null) {
			return false;
		}

		// Actualizamos incluso si los datos son idéntico
		ProductModel::update($id, $data);
		return true;
	}

	// Elimina un producto
	public static function delete(int $id): bool
	{
		if ($id <= 0) {
			throw new InvalidArgumentException('ID no válido');
		}
		return ProductModel::delete($id);
	}

	// Válida y normaliza los datos recibidos
	private static function validateData(array $data): array
	{
		// Validamos el nombre
		$name = trim($data['name'] ?? '');
		if ($name === '') {
			throw new InvalidArgumentException('El nombre es obligatorio');
		}

		// Validamos el precio
		$price = $data['price'] ?? null;
		if (!is_numeric($price) || (float) $price < 0) {
			throw new InvalidArgumentException('El precio debe ser un número igual o mayor a cero');
		}

		// Validamos las existencias
		$stock = filter_var($data['stock'] ?? 0, FILTER_VALIDATE_INT);
		if ($stock === false || $stock < 0) {
			throw new InvalidArgumentException('Las existencias deben ser un número entero igual o mayor a cero');
		}

		// Validamos la descripción
		$description = $data['description'] ?? null;
		if ($description !== null && !is_string($description)) {
			throw new InvalidArgumentException('La descripción debe ser texto');
		}

		// Devolvemos los datos normalizados
		return [
			'name' => $name,
			'description' => $description !== null
				? trim($description) ?: null
				: null,
			'price' => (float) $price,
			'stock' => $stock
		];
	}
}
