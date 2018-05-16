<br>
<table class="table shadow">
    <thead>
        <tr>
            <th colspan="6" class="fg-darkBlue">RESULTADOS</th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Cantidad de Carros</th>
            <th>Producto</th>
            <th>Fecha Quemado</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cont = 1;
        ?>
        <?php foreach ($productos->result() as $producto): ?>
            
            <tr>
                <td class="center"><?= $cont ?></td>
                <td class="center"><?= $producto->cuantos ?></td>
                <td class="center"><?= $producto->producto ?></td>
                <td class="center"><?= $producto->FechaQuemado ?></td>
            </tr>
            <?php $cont++; ?>
        <?php endforeach; ?>
    </tbody>
</table>

