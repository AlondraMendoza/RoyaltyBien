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
    function CargarHornos(d)
    {
        $("#divclasificacion").html('');
        $("#tdhornos").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> Cargando hornos con productos pendientes de clasificar...');
        $("#tdhornos").load("ObtenerHornos", {"d": d}, function () {
            CargarProductos();
        });
    }
    function CargarProductos()
    {
        var d = $("#fecha").val();
        var horno = $("#hornos").val();
        $("#divclasificacion").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando tipos de productos con productos pendientes de clasificar...');
        $("#divclasificacion").load("ObtenerProductos", {"fecha": d, "horno": horno});
    }
    function CargarModelos(cprod)
    {
        var d = $("#fecha").val();
        var horno = $("#hornos").val();
        $("#divclasificacion").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando modelos con productos pendientes de clasificar...');
        $("#divclasificacion").load("ObtenerModelos", {"fecha": d, "horno": horno, "cprod": cprod});
    }
    function CargarColores(mod, cprod)
    {
        var d = $("#fecha").val();
        var horno = $("#hornos").val();
        $("#divclasificacion").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando colores con productos pendientes de clasificar...');
        $("#divclasificacion").load("ObtenerColores", {"fecha": d, "horno": horno, "cprod": cprod, "mod": mod});
    }
    function TablaProductos(cprod, mod, color)
    {
        var d = $("#fecha").val();
        var horno = $("#hornos").val();
        $("#divclasificacion").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando colores con productos pendientes de clasificar...');
        $("#divclasificacion").load("TablaProductos", {"fecha": d, "horno": horno, "cprod": cprod, "mod": mod, "color": color});
    }
    $(document).ready(function () {
        CargarHornos('<?= $hoy ?>');
    });
</script>
<h1 class="light text-shadow">CLASIFICACIÓN</h1><br>
<center>
    <div class="tabcontrol" data-role="tabcontrol" data-open-target="#regular" data-save-state="true"  id='tabs'>
        <ul class="tabs">
            <li><a href="#regular" >Regular</a></li>
            <li><a href="#accesorios">Accesorios</a></li>
        </ul>
        <div class="frames">
            <div class="frame" id="regular">
                <div class="panel warning" data-role="panel">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkOrange"></span>
                        <span class="title">Filtros</span>
                    </div>
                    <div class="content">
                        <table class="table">
                            <tr>
                                <td style="width: 50%" class="center">
                                    <b style="" class="fg-darkEmerald">Selecciona la fecha de captura:</b><br>
                                    <div class="input-control text full-size" style="height:80px;" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker" data-on-select="CargarHornos(d)">
                                        <input type="text" id="fecha" value="<?= $hoy ?>">
                                        <button class="button" style="height: 80px"><span class="mif-calendar"></span></button>
                                    </div>
                                </td>
                                <td id="tdhornos" class="center"></td>
                            </tr>
                        </table>
                        <hr style="background-color: gray;height: 1px;">
                        <div id="divclasificacion"></div>
                        <br><br><br><br><br><br>
                    </div>
                </div>
            </div>
            <div class="frame" id="accesorios">
                <div class="panel warning" data-role="panel">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkOrange"></span>
                        <span class="title">Captura de accesorios</span>
                    </div>
                    <div class="content">
                        <table class="table hovered bordered border">
                            <tr>
                                <td class="center" colspan="2">
                                    <b  class="fg-darkEmerald">Selecciona el color</b>
                                    <table class="table">
                                        <tr>
                                            <?php $cont = 0; ?>
                                            <?php foreach ($colores->result() as $color): ?>
                                                <?php $cont++; ?>
                                                <?php if ($cont == 8 || $cont == 16 || $cont == 24) { ?>
                                                </tr><tr>
                                                <?php } ?>
                                                <td class="center">
                                                    <img style="width:100px;height: 100px" class="imgcolores" id="color-<?= $color->IdColores ?>" src="<?= base_url() ?>public/colores/<?= $color->Descripcion ?>" title="<?= $color->Nombre ?>" onclick="SeleccionaColor(<?= $color->IdColores ?>)" ><br><br>
                                                    <?= $color->Nombre ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="center"><br><br><br>
                                    <b style="font-size: 1.3em" class="fg-darkEmerald">Marca si el accesorio está fuera de tono</b><br>
                                    <label class="input-control checkbox">
                                        <span class="caption">Fuera de Tono</span>
                                        <input type="checkbox" id='fueratonoaccesorio' value="1">
                                        <span class="check"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="center" colspan="4">
                                    <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona la clasificación</b><br>
                                    <?php foreach ($clasificaciones->result() as $clasificacion): ?>
                                        <?php if ($clasificacion->IdClasificaciones < 3) { ?>
                                            <button  id="botonclasificacionacc<?= $clasificacion->IdClasificaciones ?>" class="botonesclasificacionacc button cycle-button <?= $clasificacion->Color ?>" style="width: 100px;height:100px;font-size: 3em" onclick="SeleccionarClasificacionacc(<?= $clasificacion->IdClasificaciones ?>, '<?= $clasificacion->Letra ?>')"><?= $clasificacion->Letra ?></button>
                                            <input type="hidden" id="claselacc">
                                            <input type="hidden" id="letraclaselacc">
                                            <?php
                                        }
                                        ?>
                                    <?php endforeach; ?>


                                </td>

                            </tr>
                            <tr>
                                <td style="width: 45%">
                            <center><strong><u>Defecto 1</u></strong><br><br></center>
                            <div id="masdefectoacc1" class="center">
                                <span class="mif-plus fg-darkBlue" style="font-size: 5em" onclick="ApareceFormularioAcc(1)"></span>
                            </div>
                            <div id="formulariodefectoacc1" style="display:none">
                                <b>Categoría defectos</b><br>
                                <div id="" class="input-control select full-size" style="height: 80px;">
                                    <select onchange="CargarDefectosAcc(1)" id="catdefectosacc1">
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
                                <div id="divdefectoacc1">
                                    <b>Defecto</b><br>
                                    <div id="" class="input-control select full-size" style="height: 80px;">
                                        <select id="defectoacc1">
                                            <option value="0">Selecciona primero una categoría</option>
                                        </select>
                                    </div>
                                </div>
                                <b>Clave de empleado</b><br>
                                <input type="text" class="input-control text full-size" id="claveempleadodefacc1" style="height: 80px;font-size: 1.6em" onkeyup="VerificarEmpleadoAcc(1)">
                                <span id="resclaveempleadodefacc1"></span>
                                <input type="hidden" id="puestoclaveempleadodefacc1">
                            </div>
                            </td>
                            <td style="width: 45%">
                            <center><strong><u>Defecto 2</u></strong><br><br></center>
                            <div id="masdefectoacc2" class="center">
                                <span class="mif-plus fg-darkGreen" style="font-size: 5em" onclick="ApareceFormularioAcc(2)"></span>
                            </div>
                            <div id="formulariodefectoacc2" style="display:none">
                                <b>Categoría defectos</b><br>
                                <div id="" class="input-control select full-size" style="height: 80px;">
                                    <select onchange="CargarDefectosAcc(2)" id="catdefectosacc2">
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
                                <div id="divdefectoacc2">
                                    <b>Defecto</b><br>
                                    <div id="" class="input-control select full-size" style="height: 80px;">
                                        <select id="defectoacc2">
                                            <option value="0">Selecciona primero una categoría</option>
                                        </select>
                                    </div>
                                </div>
                                <b>Clave de empleado</b><br>
                                <input type="text" class="input-control text full-size" id="claveempleadodefacc2" style="height: 80px;font-size: 1.6em" onkeyup="VerificarEmpleadoAcc(2)">
                                <span id="resclaveempleadodefacc2"></span>
                                <input type="hidden" id="puestoclaveempleadodefacc2">
                            </div>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="center"><br><br><br>
                                    <button id="" style="height: 80px;" class="button block-shadow-info text-shadow primary big-button" onclick="Guardar()"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> Guardar</button>
                                </td>
                            </tr>
                        </table>
                        <br><br><br><br><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br><br><br>
	<div style="display:none">
        <canvas id="barcode"></canvas>
        <div id="imgbarcode"></div>
    </div>
</center>


<input type="hidden" id="colorseleccionado">

<div style="display:block">
    <div id="etiquetaaccesorio"></div>
</div>
<script>
    function SeleccionaColor(id)
    {
        $("#colorseleccionado").val(id);
        $(".imgcolores").css("border", "none");
        $("#color-" + id).css("border", "2px black solid");
    }
    function pad(n, length) {
        var n = n.toString();
        while (n.length < length)
            n = "0" + n;
        return n;
    }
    function Guardar()
    {
        var fueratonoaccesorio = 0;
        var iddefecto1 = $("#defectoacc1").val();
        var colorseleccionado = $("#colorseleccionado").val();
        var iddefecto2 = $("#defectoacc2").val();
        var clavepuesto1 = $("#puestoclaveempleadodefacc1").val();
        var clavepuesto2 = $("#puestoclaveempleadodefacc2").val();
        var clasi = $("#claselacc").val();
        var letraclasi = $("#letraclaselacc").val();
        if ($('#fueratonoaccesorio').prop('checked')) {
            fueratonoaccesorio = 1;
        }
        $.getJSON("GuardarAccesorios", {"fueratono": fueratonoaccesorio, "iddefecto1": iddefecto1, "iddefecto2": iddefecto2, "clavepuesto1": clavepuesto1, "clavepuesto2": clavepuesto2, "colorseleccionado": colorseleccionado, "clasificacionseleccionada": clasi}, function (data) {
            var codigob = "<?= date_format(date_create($hoyingles), 'dmY') . '-' ?>" + pad(data.idproducto, 10);
            //var imagen = "barcodeventana?text=" + codigob + "";
            //$("#etiquetaaccesorio").html("<img src=" + imagen + ">");
            Notificacion("Correcto", "El accesorio se guardó correctamente", "check", "success");
			CodigoBarras("barcode", codigob, "#imgbarcode", data.descripcion);
            $("#imgbarcode").printArea();
            //$("#imprimeme").attr("src", "EnviarTicket?codigo=<?= date_format(date_create($hoyingles), 'dmY') . "-" ?>" + pad(data, 10) + "&producto_id=" + data);
        });
    }
    function ApareceFormularioAcc(defecto)
    {
        if (defecto == 1)
        {
            $("#formulariodefectoacc1").fadeIn();
            $("#masdefectoacc1").fadeOut();
        } else
        {
            $("#formulariodefectoacc2").fadeIn();
            $("#masdefectoacc2").fadeOut();
        }
    }
    function CargarDefectosAcc(ndef)
    {
        var idcat = $("#catdefectosacc" + ndef).val();
        if (idcat != 0)
        {
            $("#divdefectoacc" + ndef).load("CargarDefectos", {"cat_id": idcat, "ndef": "acc" + ndef});
            VerificarEmpleadoAcc(ndef);
        }
    }
    function VerificarEmpleadoAcc(defecto)
    {
        var clave = $("#claveempleadodefacc" + defecto).val();
        var categoria = $("#catdefectosacc" + defecto).val();
        $("#resclaveempleadodefacc" + defecto).html("<b>Verificando Empleado...Espera un momento</b>");
        $.getJSON("VerificarEmpleado", {"clave": clave, "categoria": categoria}, function (data) {
            if (data.nombre == 'No se encontró trabajador')
            {
                $("#resclaveempleadodefacc" + defecto).html("<b style='color:red'>" + data.nombre + "</b>");
            } else
            {
                $("#resclaveempleadodefacc" + defecto).html("<b style='color:green'>" + data.nombre + "</b>");
            }

            if (data.puesto_id != null)
            {
                $("#puestoclaveempleadodefacc" + defecto).val(data.puesto_id);
            } else
            {
                $("#puestoclaveempleadodefacc" + defecto).val("");
            }
        });
    }
    function SeleccionarClasificacionacc(clasificacion, letra)
    {
        $(".botonesclasificacionacc").css("border", "1px");
        $("#claselacc").val(clasificacion);
        $("#letraclaselacc").val(letra);
        $("#botonclasificacionacc" + clasificacion).css("border", "3px solid black");
    }
</script>
