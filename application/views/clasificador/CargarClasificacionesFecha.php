<div class="panel success" data-role="panel" id="paso1">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkGreen"></span>
        <span class="title">Clasificaciones encontradas</span>
    </div>
    <div class="content">
        <table class="table datatable hovered" data-role="datatable">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Clasificación</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clasificaciones->result() as $p): ?>
                    <tr onclick="ConsultarDetalle(<?= $p->IdCProductos ?>,<?= $p->IdModelos ?>,<?= $p->IdColores ?>,<?= $p->IdClasificaciones ?>)" style="cursor: pointer">
                        <td><?= $p->NombreProducto ?></td>
                        <td><?= $p->NombreModelo ?></td>
                        <td><?= $p->NombreColor ?></td>
                        <td><?= $p->Letra ?></td>
                        <td><?= $p->cuantos ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>