<script>
    function RegresoCriterios()
    {
        $("#divtiporeporte").show();
        $("#detalle").hide();
        $("#grafica").html("");
        $("#detalleseleccionado").html("");
    }
</script>
<center><button onclick="RegresoCriterios()" class="button block-shadow-info text-shadow primary big-button">Regresar a Criterios de Selección</button></center>
<br>
<table class="table shadow" data-role="datatable">
    <thead>
        <tr>
            <th colspan="6" class="fg-darkBlue">RESULTADOS</th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Horno</th>
            <th>Piezas</th>
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
            
            <tr>
                <td class="center"><?= $cont ?></td>
                <td class="center"><?= $producto->horno ?></td>
                <td class="center"><?= $producto->cuantos ?></td>
                <td class="center"><?= $producto->producto ?></td>
                <td class="center"><?= $producto->modelo ?></td>
                <td class="center"><?= $producto->color ?></td>

            </tr>
            <?php $cont++; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<br><br>
<center><button onclick="RegresoCriterios()" class="button block-shadow-info text-shadow primary big-button">Regresar a Criterios de Selección</button></center>