<table class = "table dataTable border bordered hovered hover" id = "tablalistaproductos" data-role = "datatable">
    <thead>
        <tr>
            <th>Clave</th>
            <th>Producto</th>
            <th>Modelo</th>
            <th>Color</th>
        </tr>
    </thead>
    <?php foreach ($ListaProductos->result() as $producto):
        ?>
        <tr>
            <td><?= $producto->IdProductos ?></td>
            <td><?= $producto->producto ?></td>
            <td><?= $producto->modelo ?></td>
            <td><?= $producto->color ?></td>
        </tr>
    <?php endforeach; ?>
</table>
