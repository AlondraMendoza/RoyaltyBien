<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Información de modelo: <?= $modelo->Nombre ?></span>
    </div>
    <div class="content" id="Inicio">
        <table class="table">
            <thead>
                <tr>
                    <th>Color</th>
                    <?php
                    $ci = &get_instance();
                    $ci->load->model("modelocedis");
                    $productos = $ci->modelocedis->ListaProductos($modelo->IdModelos);
                    foreach ($productos->result() as $producto):
                        ?>
                        <th style="border-left:1px #000 solid"><?= $producto->Nombre ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
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
                        $proda = $ci->modelocedis->ProductosSinClasificar($modelo->IdModelos, $color->IdColores, $producto->IdCProductos);
                        ?>
                        <td class="center" style="border-left: #0067cb solid 1px;">
                            <?= $proda ?>
                        </td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>