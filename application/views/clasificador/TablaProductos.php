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
                    <div id="masdefecto1<?= $producto->IdProductos ?>" class="center">
                        <span class="mif-plus fg-darkBlue" style="font-size: 5em" onclick="ApareceFormulario(1,<?= $producto->IdProductos ?>)"></span>
                    </div>
                    <div id="formulariodefecto1<?= $producto->IdProductos ?>" style="display:none">
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
                                <select id="defecto1<?= $producto->IdProductos ?>">
                                    <option value="0">Selecciona primero una categoría</option>
                                </select>
                            </div>
                        </div>
                        <b>Clave de empleado</b><br>
                        <input type="text" class="input-control text full-size" id="claveempleadodef1<?= $producto->IdProductos ?>" style="height: 80px;font-size: 1.6em" onkeyup="VerificarEmpleado(1,<?= $producto->IdProductos ?>)">
                        <span id="resclaveempleadodef1<?= $producto->IdProductos ?>"></span>
                        <input type="hidden" id="puestoclaveempleadodef1<?= $producto->IdProductos ?>">
                    </div>
                    </td>
                    <td style="width: 45%">
                    <center><strong><u>DEFECTO 2</u></strong><br><br></center>
                    <div id="masdefecto2<?= $producto->IdProductos ?>" class="center">
                        <span class="mif-plus fg-darkGreen" style="font-size: 5em" onclick="ApareceFormulario(2,<?= $producto->IdProductos ?>)"></span>
                    </div>

                    <div id="formulariodefecto2<?= $producto->IdProductos ?>" style="display:none">

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
                                <select id="defecto2<?= $producto->IdProductos ?>">
                                    <option value="0">Selecciona primero una categoría</option>
                                </select>
                            </div>
                        </div>
                        <b>Clave de empleado</b><br>
                        <input type="text" class="input-control text full-size" id="claveempleadodef2<?= $producto->IdProductos ?>" style="height: 80px;font-size: 1.6em" onkeyup="VerificarEmpleado(2,<?= $producto->IdProductos ?>)">
                        <span id="resclaveempleadodef2<?= $producto->IdProductos ?>"></span>
                        <input type="hidden" id="puestoclaveempleadodef2<?= $producto->IdProductos ?>">
                    </div>
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
    function Notificacion(titulo, texto, icono, color)
    {
        $.Notify({
            caption: titulo,
            content: texto,
            icon: "<span class='mif-" + icono + "'></span>",
            type: color
        });
    }
    function ApareceFormulario(defecto, producto)
    {
        if (defecto == 1)
        {
            $("#formulariodefecto1" + producto).fadeIn();
            $("#masdefecto1" + producto).fadeOut();
        } else
        {
            $("#formulariodefecto2" + producto).fadeIn();
            $("#masdefecto2" + producto).fadeOut();
        }
    }
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
        var iddefecto1 = $("#defecto1" + idprod).val();
        var iddefecto2 = $("#defecto2" + idprod).val();
        var clavepuesto1 = $("#puestoclaveempleadodef1" + idprod).val();
        var clavepuesto2 = $("#puestoclaveempleadodef2" + idprod).val();
        if (iddefecto1 != 0 && clavepuesto1 == "")
        {
            Notificacion("Error", "Es necesario que captures la clave del empleado relacionado al defecto 1.", "cancel", "alert");
            return(0);
        }
        if (iddefecto2 != 0 && clavepuesto2 == "")
        {
            Notificacion("Error", "Es necesario que captures la clave del empleado relacionado al defecto 2.", "cancel", "alert");
            return(0);
        }
        $.post("GuardarClasificacion", {"idclasi": idclasi, "idprod": idprod, "defecto1": iddefecto1, "defecto2": iddefecto2, "puestodefecto1": clavepuesto1, "puestodefecto2": clavepuesto2}, function (data) {
            if (data == "correcto")
            {
                Notificacion("Correcto", "La Clasificación se guardó correctamente", "check", "success");
            } else
            {
                Notificacion("Error", "Ocurrió un error al guardar la clasificación", "cancel", "alert");
            }
        });
        /*Fin guardado*/
        $(".tablasproductos").hide();
        $("#tabla" + cont).fadeIn();
        //alert($("#spanbotonsiguiente" + idprod).html());
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
        $("#botonclasificacion" + clasificacion + "-" + producto).css("border", "3px solid black");
    }
    function VerificarEmpleado(defecto, producto)
    {
        var clave = $("#claveempleadodef" + defecto + producto).val();
        var categoria = $("#catdefectos" + defecto + producto).val();
        $("#resclaveempleadodef" + defecto + producto).html("Verificando Empleado");
        $.getJSON("VerificarEmpleado", {"clave": clave, "categoria": categoria}, function (data) {

            $("#resclaveempleadodef" + defecto + producto).html(data.nombre);
            if (data.puesto_id != null)
            {
                $("#puestoclaveempleadodef" + defecto + producto).val(data.puesto_id);
            } else
            {
                $("#puestoclaveempleadodef" + defecto + producto).val("");
            }
        });
    }
</script>