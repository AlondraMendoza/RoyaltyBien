<?php
$cont = 1;
?>
<?php foreach ($productos->result() as $producto): ?>
    <?php
    $oculto = "";
    if ($cont > 1) {
        $oculto = "display:none";
    }
    ?>
    <div class="panel primary tablasproductos" data-role="panel" style="font-size: 1.2em;<?= $oculto ?>" id="tabla<?= $cont ?>">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title"> Producto 
                <?= $cont ?> de
                <?= $productos->num_rows() ?>
                según filtros seleccionados.</span>
        </div>
        <div class="content">
            <div class=""  >
                <table class="table hovered border bordered" >
                    <tr class="center primary" style="font-size: 1.2em">
                        <td class="center" style="text-align: center"><span onclick="CargarColores(<?= $mod . ',' . $cprod ?>)" style="font-size: 3em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de Colores"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="center" style="text-align: center"><b>Clave producto:</b><br> <?= $producto->IdProductos ?></td>
                        <td class="center" style="text-align: center"><b>Nombre producto:</b><br> <?= $producto->NombreProducto ?></td>
                        <td class="center" style="text-align: center"><b>Modelo:</b><br> <?= $producto->NombreModelo ?></td>
                        <td class="center" style="text-align: center"><b>Color:</b><br> <?= $producto->NombreColor ?></td>
                    </tr>

                </table>
                <table class="table bordered hovered border" style="font-size: 1.2em">
                    <tr>
                        <td class="center" colspan="4">
                            <b>Clasificacion</b><br><br>
                            <?php foreach ($clasificaciones->result() as $clasificacion): ?>
                                <button  id="botonclasificacion<?= $clasificacion->IdClasificaciones ?>-<?= $producto->IdProductos ?>" class="botonesclasificacion button cycle-button <?= $clasificacion->Color ?>" style="width: 100px;height:100px;font-size: 3em" onclick="SeleccionarClasificacion(<?= $clasificacion->IdClasificaciones ?>,<?= $producto->IdProductos ?>)"><?= $clasificacion->Letra ?></button>
                                <input type="hidden" id="clasel<?= $producto->IdProductos ?>">
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 45%">
                    <center><strong><u>DEFECTO 1</u></strong><br><br></center>
                    <b>Categoría defectos</b><br>
                    <div id="" class="input-control select full-size" style="height: 80px;">
                        <select onchange="CargarDefectos(<?= $producto->IdProductos ?>, 1)" id="catdefectos1<?= $producto->IdProductos ?>">
                            <option value="0">Selecciona categoría</option>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modeloclasificador");
                            $catdefectos = $ci->modeloclasificador->CategoriasDefectos();
                            ?>
                            <?php foreach ($catdefectos->result() as $categoria): ?>
                                <option value="<?= $categoria->IdCatDefectos ?>"><?= $categoria->Nombre ?></option>
                            <?php endforeach; ?>  
                        </select>
                    </div>
                    <div id="divdefecto1<?= $producto->IdProductos ?>">
                        <b>Defecto</b><br>
                        <div id="" class="input-control select full-size" style="height: 80px;">
                            <select>
                                <option>Selecciona primero una categoría</option>
                            </select>
                        </div>
                    </div>
                    <b>Clave de empleado</b><br>
                    <input type="text" class="input-control text full-size" id="claveempleadodef1<?= $producto->IdProductos ?>" style="height: 80px;font-size: 1.6em">
                    </td>
                    <td style="width: 45%">
                    <center><strong><u>DEFECTO 2</u></strong><br><br></center>
                    <b>Categoría defectos</b><br>
                    <div id="" class="input-control select full-size" style="height: 80px;">
                        <select onchange="CargarDefectos(<?= $producto->IdProductos ?>, 2)" id="catdefectos2<?= $producto->IdProductos ?>">
                            <option value="0">Selecciona categoría</option>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modeloclasificador");
                            $catdefectos = $ci->modeloclasificador->CategoriasDefectos();
                            ?>
                            <?php foreach ($catdefectos->result() as $categoria): ?>
                                <option value="<?= $categoria->IdCatDefectos ?>"><?= $categoria->Nombre ?></option>
                            <?php endforeach; ?>  
                        </select>
                    </div>
                    <div id="divdefecto2<?= $producto->IdProductos ?>">
                        <b>Defecto</b><br>
                        <div id="" class="input-control select full-size" style="height: 80px;">
                            <select>
                                <option>Selecciona primero una categoría</option>
                            </select>
                        </div>
                    </div>
                    <b>Clave de empleado</b><br>
                    <input type="text" class="input-control text full-size" id="claveempleadodef2<?= $producto->IdProductos ?>" style="height: 80px;font-size: 1.6em">
                    </td>

                    </tr>
                    <tr><td colspan="2">
                            <p style="text-align: right;margin-right: 10px;">
                                <button id="botonsiguiente<?= $producto->IdProductos ?>" style="height: 80px;" class="button block-shadow-success text-shadow success big-button botonessiguiente" onclick="Siguiente(<?= $producto->IdProductos ?>)"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> 
                                    <?php
                                    if ($cont == $productos->num_rows()) {
                                        echo ("<span id='spanbotonsiguiente" . $producto->IdProductos . "'>Finalizar clasificación</span>");
                                    } else {
                                        echo("<span id='spanbotonsiguiente" . $producto->IdProductos . "'>Siguiente</span>");
                                    }
                                    ?>
                                </button>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
    $cont++;
    ?>

<?php endforeach; ?>    

<script>
    function CargarDefectos(idprod, ndef)
    {

        var idcat = $("#catdefectos" + ndef + idprod).val();
        if (idcat != 0)
        {
            $("#divdefecto" + ndef + idprod).load("CargarDefectos", {"cat_id": idcat, "ndef": ndef, "idprod": idprod});
        }
    }
    var cont = 2;
    var ultimo = <?php echo $cont - 1; ?>;
    function Siguiente(idprod)
    {
        /*Guardar clasificacion*/
        var idclasi = $("#clasel" + idprod).val();
        $.post("GuardarClasificacion", {"idclasi": idclasi, "idprod": idprod}, function (data) {

        });
        /*Fin guardado*/
        $(".tablasproductos").hide();
        $("#tabla" + cont).fadeIn();
        alert($("#spanbotonsiguiente" + idprod).html());
        if ($("#spanbotonsiguiente" + idprod).html() == "Finalizar clasificación")
        {
            var d = $("#fecha").val();
            CargarHornos(d);
        }


        cont++;
    }
    function SeleccionarClasificacion(clasificacion, producto)
    {
        $(".botonesclasificacion").css("border", "1px");
        $("#clasel" + producto).val(clasificacion);
        //alert(clasificacion);
        $("#botonclasificacion" + clasificacion + "-" + producto).css("border", "3px solid black");
    }
</script>