<script>
    var etiquetas = new Array();
    var valores = new Array();
    $(document).ready(function () {
    });
</script>
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