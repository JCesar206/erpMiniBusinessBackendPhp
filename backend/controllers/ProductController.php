<?php
// Cargamos el Service de productos
require_once __DIR__ . '/../services/ProductService.php';
class ProductController
{
	// Consulta todos los productos
	public static function index(?array $data = null): array
	{
		$products = ProductService::getAll();
		return [
			'success' => true,
			'total' => count($products),
			'data' => $products
		];
	}

	// Registrar un producto
	public static function store(array $data): array
	{
		$id = ProductService::create($data);
		http_response_code(201);
		return [
			'success' => true,
			'message' => 'Producto registrado correctamente',
			'id' => $id
		];
	}

	// Consulta un producto por ID
	public static function show(?array $data = null, ?int $id = null): array
	{
		$product = ProductService::getById($id ?? 0);
		if ($product === null) {
			throw new HttpException('Producto no encontrado', 404);
		}
		return [
			'success' => true,
			'data' => $product
		];
	}

	// Actualizar un producto
	public static function update(array $data, ?int $id = null): array
	{
		$updated = ProductService::update($id ?? 0, $data);
		if (!$updated) {
			throw new HttpException('Producto no encontrado', 404);
		}
		return [
			'success' => true,
			'message' => 'Producto actualizado correctamente'
		];
	}

	// Eliminar un producto
	public static function destroy(?array $data = null, ?int $id = null): array
	{
		$deleted = ProductService::delete($id ?? 0);
		if (!$deleted) {
			throw new HttpException('Producto no encontrado', 404);
		}
		return [
			'success' => true,
			'message' => 'Producto eliminado correctamente'
		];
	}
}
