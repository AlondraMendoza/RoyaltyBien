<script>
    function Notificacion(titulo, texto, icono, color)
    {
        $.Notify({
            caption: titulo,
            content: texto,
            icon: "<span class = 'mif-" + icono + "'></span>",
            type: color
        });
    }
    function GuardarMaximo(valor, cproducto, modelo, color, clasificacion)
    {
        $.post("GuardarMaximo", {"valor": valor, "cproducto": cproducto, "modelo": modelo, "color": color, "clasificacion": clasificacion}, function (data) {
            if (data == "correcto") {
                Notificacion("Correcto", "El valor se guardó correctamente", "check", "success");
            } else
            {
                Notificacion("Error", "Ocurrió un error al guardar el valor", "cancel", "alert");
            }

        });
    }
    function GuardarMinimo(valor, cproducto, modelo, color, clasificacion)
    {
        $.post("GuardarMinimo", {"valor": valor, "cproducto": cproducto, "modelo": modelo, "color": color, "clasificacion": clasificacion}, function (data) {
            if (data == "correcto") {
                Notificacion("Correcto", "El valor se guardó correctamente", "check", "success");
            } else
            {
                Notificacion("Error", "Ocurrió un error al guardar el valor", "cancel", "alert");
            }
        });
    }
</script>
<h1 class="light text-shadow">CONFIGURACIÓN MÁXIMOS Y MÍNIMOS</h1><br>

<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">

        <?php
        $cont = 1;
        foreach ($modelos->result() as $modelo):
            $activo = "";
            if ($cont == 1) {
                $activo = "active";
            }
            ?>
            <li class="<?= $activo ?>"><a href="#<?= $modelo->IdModelos ?>"><?= $modelo->Nombre ?></a></li>
            <?php
            $cont++;
        endforeach;
        ?>
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
                                        <th colspan="2" style="border-left:1px #000 solid"><?= $producto->Nombre ?></th>
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
                                        $maximoa = "--";
                                        $minimoa = "--";
                                        $maximob = "--";
                                        $minimob = "--";

                                        if ($maximominimoa != null) {
                                            $maximoa = $maximominimoa->Maximo;
                                            $minimoa = $maximominimoa->Minimo;
                                        }
                                        if ($maximominimob != null) {

                                            $maximob = $maximominimob->Maximo;
                                            $minimob = $maximominimob->Minimo;
                                        }
                                        ?>
                                        <td class="center">
                                            <div class="input-control select full-size" ><input title="Máximo" onchange="GuardarMaximo(this.value,<?= $producto->IdCProductos ?>,<?= $modelo->IdModelos ?>,<?= $color->IdColores ?>, 1)" type="text" value="<?= $maximoa ?>"></div><hr>
                                            <div class="input-control select full-size" ><input title="Mínimo" onchange="GuardarMinimo(this.value,<?= $producto->IdCProductos ?>,<?= $modelo->IdModelos ?>,<?= $color->IdColores ?>, 1)" type="text" value="<?= $minimoa ?>"></div>
                                        </td>
                                        <td class="center">
                                            <div class="input-control select full-size" ><input title="Máximo" onchange ="GuardarMaximo(this.value,<?= $producto->IdCProductos ?>,<?= $modelo->IdModelos ?>,<?= $color->IdColores ?>, 2)" type="text" value="<?= $maximob ?>"></div><hr>
                                            <div class="input-control select full-size" ><input title="Mínimo" onchange ="GuardarMinimo(this.value,<?= $producto->IdCProductos ?>,<?= $modelo->IdModelos ?>,<?= $color->IdColores ?>, 2)" type="text" value="<?= $minimob ?>"></div>
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