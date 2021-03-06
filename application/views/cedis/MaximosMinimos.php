<h1 class="light text-shadow">MÁXIMOS Y MÍNIMOS</h1><br>
<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">
        <?php foreach ($modelos->result() as $modelo): ?>
            <li class="active"><a href="#<?= $modelo->IdModelos ?>"><?= $modelo->Nombre ?></a></li>
        <?php endforeach; ?>
    </ul>
    <div class="frames">
        <?php foreach ($modelos->result() as $modelo): ?>  
            <div class="frame" id="<?= $modelo->IdModelos ?>">
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
                                        <th colspan="2"><?= $producto->Nombre ?></th>
                                    <?php endforeach ?>
                                </tr>
                            </thead>
                            <tr>
                                <td class="center"></td>
                                <?php foreach ($productos->result() as $producto): ?>
                                    <td class="center" style="border: 1px #000 solid"><b>A</b></td>
                                    <td class="center" style="border: 1px #000 solid"><b>B</b></td>
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
                                        $maximominimoa = $ci->modelocedis->MaximoMinimo($modelo->IdModelos, $color->IdColores, 1, $producto->IdCProductos, $proda);
                                        $maximominimob = $ci->modelocedis->MaximoMinimo($modelo->IdModelos, $color->IdColores, 2, $producto->IdCProductos, $prodb);
                                        $semaforoa = "gray";
                                        $semaforob = "gray";
                                        $maximoa = "--";
                                        $minimoa = "--";
                                        $maximob = "--";
                                        $minimob = "--";

                                        if ($maximominimoa != null) {
                                            $semaforoa = $ci->modelocedis->ColorMaximosMinimos($proda, $maximominimoa->Maximo, $maximominimoa->Minimo);
                                            $maximoa = $maximominimoa->Maximo;
                                            $minimoa = $maximominimoa->Minimo;
                                        }
                                        if ($maximominimob != null) {
                                            $semaforob = $ci->modelocedis->ColorMaximosMinimos($prodb, $maximominimob->Maximo, $maximominimob->Minimo);
                                            $maximob = $maximominimob->Maximo;
                                            $minimob = $maximominimob->Minimo;
                                        }
                                        ?>
                                        <td class="center">
                                            <button title="Mínimo:<?= $minimoa ?>  Máximo:<?= $maximoa ?>" class="button cycle-button bg-<?= $semaforoa ?>"></button><br><?= $proda ?>
                                        </td>
                                        <td class="center">
                                            <button title="Mínimo:<?= $minimob ?>  Máximo:<?= $maximob ?>" class="button cycle-button bg-<?= $semaforob ?>"></button><br><?= $prodb ?>
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
            </div>
        <?php endforeach; ?>
    </div>
</div>