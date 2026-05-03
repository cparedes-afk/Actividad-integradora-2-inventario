<?php
require_once '../controllers/ProductoController.php';
$controller = new ProductoController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $controller->eliminar($_POST['id']);
    } else {
        $controller->guardar($_POST);
    }
    header("Location: productos.php");
    exit();
}
$productos = $controller->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - VS Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Gestión de Inventario</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card card-body shadow-sm">
                    <h5 id="title">Nuevo Producto</h5>
                    <form method="POST" id="form">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-2"><input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required></div>
                        <div class="mb-2"><textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción"></textarea></div>
                        <div class="mb-2"><input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Precio" min="0.01" required></div>
                        <div class="mb-2"><input type="number" name="stock" id="stock" class="form-control" placeholder="Stock" min="0" required></div>
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                        <button type="button" onclick="resetForm()" class="btn btn-link w-100 text-muted">Limpiar</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-white shadow-sm rounded">
                    <thead class="table-dark">
                        <tr><th>Nombre</th><th>Stock</th><th>Precio</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php while($p = $productos->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $p['nombre'] ?></td>
                            <td><span class="badge bg-<?= $p['stock'] > 0 ? 'success' : 'danger' ?>"><?= $p['stock'] ?></span></td>
                            <td>$<?= number_format($p['precio'], 2) ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick='edit(<?= json_encode($p) ?>)'>Editar</button>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">X</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function edit(p) {
            document.getElementById('title').innerText = 'Editar Producto';
            document.getElementById('id').value = p.id;
            document.getElementById('nombre').value = p.nombre;
            document.getElementById('descripcion').value = p.descripcion;
            document.getElementById('precio').value = p.precio;
            document.getElementById('stock').value = p.stock;
        }
        function resetForm() {
            document.getElementById('form').reset();
            document.getElementById('id').value = '';
            document.getElementById('title').innerText = 'Nuevo Producto';
        }
    </script>
</body>
</html>