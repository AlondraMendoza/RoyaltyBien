<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Código</th>
            <th>Clasificación</th>
            <th>Producto</th>
            <th>Modelo</th>
            <th>Color</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cont = 1;
        ?>
        <?php foreach ($productos->result() as $producto): ?>
            <?php
            $ci = &get_instance();
            $ci->load->model("modeloadministrador");
            $clasificacion = $ci->modeloadministrador->Clasificacion($producto->IdProductos);
            $letra = "Sin Clasificar";
            if ($clasificacion != "") {
                $letra = $clasificacion->Letra;
            }
            ?>
            <tr>
                <td class="center"><?= $cont ?></td>
                <td class="center"><?= $producto->IdProductos ?></td>
                <td class="center"><?= $letra ?></td>
                <td class="center"><?= $producto->producto ?></td>
                <td class="center"><?= $producto->modelo ?></td>
                <td class="center"><?= $producto->color ?></td>

            </tr>
            <?php $cont++; ?>
        <?php endforeach; ?>
    </tbody>
</table>

