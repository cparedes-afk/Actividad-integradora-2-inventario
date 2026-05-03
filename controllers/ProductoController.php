<?php
require_once '../config/database.php';
require_once '../models/Producto.php';

class ProductoController {
    private $db;
    private $producto;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
    }

    public function listar() {
        return $this->producto->read();
    }

    public function guardar($data) {
        // Validaciones obligatorias
        if(empty($data['nombre']) || $data['precio'] <= 0 || $data['stock'] < 0) {
            return false;
        }

        $this->producto->nombre = htmlspecialchars(strip_tags($data['nombre']));
        $this->producto->descripcion = htmlspecialchars(strip_tags($data['descripcion']));
        $this->producto->precio = $data['precio'];
        $this->producto->stock = $data['stock'];

        if(!empty($data['id'])) {
            $this->producto->id = $data['id'];
            return $this->producto->update();
        }
        return $this->producto->create();
    }

    public function eliminar($id) {
        $this->producto->id = $id;
        return $this->producto->delete();
    }
}
?>