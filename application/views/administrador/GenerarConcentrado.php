
<table class="table">
    <thead>
        <tr>
            <th>Filtro</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos->result() as $producto): ?>
            <?php
            $texto = "";
            if ($por == "Clasificacion(p.IdProductos)") {
                $ci = &get_instance();
                $ci->load->model("modeloadministrador");
                $clasificacion = $ci->modeloadministrador->ClasificacionObj($producto->Nombre);
                $texto = "Sin Clasificar";
                if ($clasificacion != "") {
                    $texto = $clasificacion->Letra;
                }
            } else {
                $texto = $producto->Nombre;
            }
            ?>
            <tr>
                <td class="center"><?= $texto ?></td>
                <td class="center"><?= $producto->cuantos ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>