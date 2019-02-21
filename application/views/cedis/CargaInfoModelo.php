<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Información de modelo: <?= $modelo->Nombre ?></span>
    </div>
    <div class="content" id="Inicio">
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2">Color</th>
                    <?php
                    $ci = &get_instance();
                    $ci->load->model("modelocedis");
                    $productos = $ci->modelocedis->ListaProductos($modelo->IdModelos);
                    foreach ($productos->result() as $producto):
                        ?>
                        <th colspan="2" style="border-left:1px #000 solid"><?= $producto->Nombre ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tr>
                <td class="center"></td>
                <?php foreach ($productos->result() as $producto): ?>
                    <td class="center" style="border: 1px #000 solid"><b>A<br><span class="text-small"> (CEDIS-Pedidos)</span></b></td>
                    <td class="center" style="border: 1px #000 solid"><b>B<br><span class="text-small">(CEDIS-Pedidos)</span></b></td>
                <?php endforeach ?>
            </tr>
            <?php
            $colores = $ci->modelocedis->ListaColores($modelo->IdModelos);
            foreach ($colores->result() as $color):
                ?>
                <tr>
                    <td>
                        <?= $color->Nombre ?>
                    </td>
                    <?php
                    foreach ($productos->result() as $producto):
                        $proda = $ci->modelocedis->ProductosCedis($modelo->IdModelos, $color->IdColores, 1, $producto->IdCProductos);
                        $prodb = $ci->modelocedis->ProductosCedis($modelo->IdModelos, $color->IdColores, 2, $producto->IdCProductos);
                        $pedia = $ci->modelocedis->ProductosPedidosVentas($modelo->IdModelos, $color->IdColores, 1, $producto->IdCProductos);
                        $pedib = $ci->modelocedis->ProductosPedidosVentas($modelo->IdModelos, $color->IdColores, 2, $producto->IdCProductos);
                        ?>
                        <td class="center" style="border-left: #0067cb solid 1px;">
                            <?= $proda ?>-<?= $pedia ?>=<b class="fg-darkGreen"><?= $proda - $pedia ?></b>

                        </td>
                        <td class="center">
                            <?= $prodb ?>-<?= $pedib ?>=<b class="fg-darkGreen"><?= $prodb - $pedib ?></b>

                        </td>
                    <?php endforeach ?>
                    <td class="center">
                        <?php ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>