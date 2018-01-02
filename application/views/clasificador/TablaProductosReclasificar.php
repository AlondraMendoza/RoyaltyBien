<?php
$cont = 1;
?>
<?php
$oculto = "";
if ($cont > 1) {
    $oculto = "display:none";
}
?>
<div class="panel primary tablasproductos" data-role="panel" style="font-size: 1.2em;<?= $oculto ?>" id="tabla<?= $cont ?>">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span> Selecciona la información de la clasificación
    </div>
    <div class="content">
        <div class=""  >
            <table class="table hovered border bordered" >
                <tr class="center primary" style="font-size: 1.2em">
                    <td class="center" style="text-align: center"><b>Clave producto:</b><br> <?= $producto->IdProductos ?></td>
                    <td class="center" style="text-align: center"><b>Nombre producto:</b><br> <?= $producto->NombreProducto ?></td>
                    <td class="center" style="text-align: center"><b>Modelo:</b><br> <?= $producto->NombreModelo ?></td>
                    <td class="center" style="text-align: center"><b>Color:</b><br> <?= $producto->NombreColor ?></td>
                </tr>
            </table>
            <table class="table bordered hovered border" style="font-size: 1.2em">
                <tr>
                    <td class="center" colspan="4">
                        <b><u>Selecciona la Clasificación</u></b><br><br>
                        <?php foreach ($clasificaciones->result() as $clasificacion): ?>
                            <button  id="botonclasificacion<?= $clasificacion->IdClasificaciones ?>-<?= $producto->IdProductos ?>" class="botonesclasificacion button cycle-button <?= $clasificacion->Color ?>" style="width: 100px;height:100px;font-size: 3em" onclick="SeleccionarClasificacion(<?= $clasificacion->IdClasificaciones ?>,<?= $producto->IdProductos ?>, '<?= $clasificacion->Letra ?>')"><?= $clasificacion->Letra ?></button>
                            <input type="hidden" id="clasel<?= $producto->IdProductos ?>">
                            <input type="hidden" id="letraclasel<?= $producto->IdProductos ?>">
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 45%">
                <center><strong><u>Defecto 1</u></strong><br><br></center>
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
                <center><strong><u>Defecto 2</u></strong><br><br></center>
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
                <tr>
                    <td colspan="2" class="center">
                        <b><u>Marca si el producto está fuera de tono</u></b><br><br>
                        <label class="input-control checkbox">
                            <span class="caption">Fuera de Tono</span>
                            <input type="checkbox" id='fueratono<?= $producto->IdProductos ?>' value="1">
                            <span class="check"></span>

                        </label>
                    </td>
                </tr>
                <tr><td colspan="2">
                        <p style="text-align: right;margin-right: 10px;">
                            <button id="botonsiguiente<?= $producto->IdProductos ?>" style="height: 80px;" class="button block-shadow-success text-shadow success big-button botonessiguiente" onclick="Guardar(<?= $producto->IdProductos ?>, '<?= date_format(date_create($producto->FechaCaptura), 'dmY') ?>')"><span class="mif-floppy-disk mif-ani-hover-horizontal"></span>
                                <span id='spanbotonsiguiente <?= $producto->IdProductos ?>'>Guardar reclasificación</span></button>
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
<div id='etiqueta'></div>
<iframe src="" id="imprimeme" width="100%" height="100%" style="display:none"></iframe>
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
            VerificarEmpleado(ndef, idprod);
        }
    }

    var cont = 2;
    var ultimo = <?php echo $cont - 1;
?>;
    function imprimir() {
        var objeto = document.getElementById('imprimeme');  //obtenemos el objeto a imprimir
        var ventana = window.open('', '_blank', 'width=100,height=100');  //abrimos una ventana vacía nueva
        ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
        ventana.document.close();  //cerramos el documento
        ventana.print();  //imprimimos la ventana
        setTimeout(ventana.close());  //cerramos la ventana
    }
    function Guardar(idprod, fecha)
    {
        /*Guardar clasificacion*/
        var idclasi = $("#clasel" + idprod).val();
        var letraclasi = $("#letraclasel" + idprod).val();
        var iddefecto1 = $("#defecto1" + idprod).val();
        var iddefecto2 = $("#defecto2" + idprod).val();
        var clavepuesto1 = $("#puestoclaveempleadodef1" + idprod).val();
        var clavepuesto2 = $("#puestoclaveempleadodef2" + idprod).val();
        var fueratono = 0;
        if ($('#fueratono' + idprod).prop('checked')) {
            fueratono = 1;
        }
        if (idclasi == null || idclasi == "")
        {
            Notificacion("Error", "Selecciona la clasificación antes de continuar", "cancel", "alert");
            return(0);
        }
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
        $.post("GuardarClasificacion", {"idclasi": idclasi, "idprod": idprod, "defecto1": iddefecto1, "defecto2": iddefecto2, "puestodefecto1": clavepuesto1, "puestodefecto2": clavepuesto2, "fueratono": fueratono}, function (data) {
            if (data == "correcto")
            {
                Notificacion("Correcto", "La Clasificación se guardó correctamente", "check", "success");
                $("#panelbusqueda").fadeIn();
                $("#claveProd").val("");
                $("#des").html("");
                $.post("GuardarProcesado", {"producto": idprod});

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
    function SeleccionarClasificacion(clasificacion, producto, letra)
    {
        $(".botonesclasificacion").css("border", "1px");
        $("#clasel" + producto).val(clasificacion);
        $("#letraclasel" + producto).val(letra);
        $("#botonclasificacion" + clasificacion + "-" + producto).css("border", "3px solid black");
    }
    function VerificarEmpleado(defecto, producto)
    {
        var clave = $("#claveempleadodef" + defecto + producto).val();
        var categoria = $("#catdefectos" + defecto + producto).val();
        $("#resclaveempleadodef" + defecto + producto).html("<b>Verificando Empleado...Espera un momento</b>");
        $.getJSON("VerificarEmpleado", {"clave": clave, "categoria": categoria}, function (data) {
            if (data.nombre == 'No se encontró trabajador')
            {
                $("#resclaveempleadodef" + defecto + producto).html("<b style='color:red'>" + data.nombre + "</b>");
            } else
            {
                $("#resclaveempleadodef" + defecto + producto).html("<b style='color:green'>" + data.nombre + "</b>");
            }

            if (data.puesto_id != null)
            {
                $("#puestoclaveempleadodef" + defecto + producto).val(data.puesto_id);
            } else
            {
                $("#puestoclaveempleadodef" + defecto + producto).val("");
            }
        });
    }
    function pad(n, length) {
        var n = n.toString();
        while (n.length < length)
            n = "0" + n;
        return n;
    }
</script>


