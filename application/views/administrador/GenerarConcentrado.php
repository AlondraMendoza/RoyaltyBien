<script>
    var etiquetas = new Array();
    var valores = new Array();
    $(document).ready(function () {
    });
</script>
<table class="table">
    <thead>
        <tr>
            <th colspan="2">RESULTADOS</th>
        </tr>
        <tr>
            <th>Filtro</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cont = 0;
        ?>
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
        <script>
            etiquetas[<?= $cont ?>] = "<?= $texto ?>";
            valores[<?= $cont ?>] = <?= $producto->cuantos ?>;
        </script>
        <?php
        $cont++;
        ?>
    <?php endforeach; ?>
    <script>
        Grafica(etiquetas, valores);
    </script>
</tbody>
</table>