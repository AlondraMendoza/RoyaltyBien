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
<table class="table shadow" data-role="datatable" >
    <thead>
        <tr>
            <th colspan="6" class="fg-darkBlue">RESULTADOS</th>
        </tr>
        <tr>
            <th style="text-align:center;">No.</th>
            <th style="text-align:center;">Producto</th>
            <th style="text-align:center;">Modelo</th>
            <th style="text-align:center;">Color</th>
            <th style="text-align:center;">Fecha Entrada</th>
            <th style="text-align:center;">Usuario</th>
            <th style="text-align:center;">Destruido</th>
            <th style="text-align:center;">Fecha Dest.</th>
            <th style="text-align:center;">Usuario Dest.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cont = 1;
        ?>
        <?php foreach ($productos->result() as $producto): ?>
            
            <tr>
                <td class="center"><?= $cont ?></td>
                <td class="center"><?= $producto->producto ?></td>
                <td class="center"><?= $producto->modelo ?></td>
                <td class="center"><?= $producto->color ?></td>
                <td class="center"><?= $producto->FechaEntrada ?></td>
                <td class="center"><?= $producto->UsuariosId ?></td>
                <td class="center"><?php if ($producto->Destruido == 0){ echo 'No';}else{echo 'Si';} ?></td>
                <td class="center"><?= $producto->FechaDestruccion ?></td>
                <td class="center"><?= $producto->Usuariodestruccion ?></td>
            </tr>
            <?php $cont++; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<br><br>
<center><button onclick="RegresoCriterios()" class="button block-shadow-info text-shadow primary big-button">Regresar a Criterios de Selección</button></center>