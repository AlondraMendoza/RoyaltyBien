<div class="panel info" data-role="panel" id="">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span>
        <span class="title">Productos clasificados según selección</span>
    </div>
    <div class="content">
        <table class="table datatable" data-role="datatable">
            <thead>
                <tr>
                    <th class="center">No</th>
                    <th class="center">Clave Producto</th>
                    <th class="center">Código Barras</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;
                ?>
                <?php
                foreach ($clasificaciones->result() as $p):
                    $fecha = date_format(date_create($p->FechaCaptura), 'dmY');
                    $codigo = $fecha . "-" . str_pad($p->IdProductos, 10);
                    ?>
                    <tr>
                        <td class="center"><?= $count ?></td>
                        <td class="center"><?= $p->IdProductos ?></td>
                        <td class="center"> <img src="barcodevista?text=<?= $codigo ?>"><br>
                            <?= $codigo; ?>
                        </td>
                    </tr>
                    <?php $count++; ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>