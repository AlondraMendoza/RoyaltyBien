<script>
    var etiquetas = new Array();
    var valores = new Array();
    $(document).ready(function () {
    });
    function RegresoCriterios()
    {
        $("#divtiporeporte").show();
        $("#detalle").hide();
        $("#grafica").html("");
        $("#detalleseleccionado").html("");
    }
</script>
<br>
<center><button onclick="RegresoCriterios()" class="button block-shadow-info text-shadow primary big-button">Regresar a Criterios de Selección</button></center>
<br>
<table class="table">
    <thead>
        <tr>
            <th colspan="2" class="fg-darkBlue">RESULTADOS</th>
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
            
                $texto = $producto->Nombre;
            
            ?>
            <tr>
                <td class="center"><?= $texto ?></td>
                <td class="center" onclick="DetalleSeleccionado('<?= $texto ?>')"><?= $producto->cuantos ?></td>
            </tr>
        <script>
            etiquetas[<?= $cont ?>] = "<?= $texto ?>";
            valores[<?= $cont ?>] = <?= $producto->cuantos ?>;
        </script>
        <?php
        $cont++;
        ?>
    <?php endforeach; ?>
</tbody>
</table>
<table class="table">
    <thead>
        <tr>
            <th colspan="2" class="fg-darkBlue">GRÁFICA</th>
        </tr>
    </thead>
</table>
<script>
    Grafica(etiquetas, valores);
</script>
<br><br>

