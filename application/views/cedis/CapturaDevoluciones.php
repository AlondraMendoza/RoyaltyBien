<script>
    function VerificarClave(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des").html("Verificando clave...");
                $.getJSON("VerificarClaveProd", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des").html("Producto encontrado");
                        if ($("#td" + data.id).length) {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ya agregaste el producto a la lista',
                                type: 'alert'
                            });
                            $("#claveProd").val("");
                            $("#des").html("");
                        } else {
                            //metodo para Abrir tabla y agregar datos
                            var input = '<tr id=' + data.id + '>';
                            input += '<td class="center" id="td' + data.id + '"><label class="input-control checkbox">';
                            input += '<input type="checkbox" name="IDS[]" value="' + data.id + '" checked>';
                            input += '<span class="check"></span>';
                            input += '</label></td>';
                            input += '<td class="center">' + data.id + '</td><td class="center">';
                            input += '<b style="font-size: 1.3em" class="fg-darkEmerald"></b><br>';
                            input += data.nombre;
                            input += '</td>';
                            input += '<td><div class="input-control text full-size" data-role="input" ><input  onkeyup="BuscarSub(this.value,' + data.id + ')" type="text" placeholder="Buscar subproductos" id="inputsub' + data.id + '"><button class="button"><span class="mif-search"></span></button></div><br><div id="subprod' + data.id + '"></div><div id="subproductosagregados' + data.id + '" style="display:none"><i><b><br>SubProductos Relacionados</b></i><hr><br></div></td>';
                            input += '</tr>';
                            $("#tablaproductos").append(input);
                            $("#claveProd").val("");
                            $("#des").html("");
                            $("#resultadosproductos").fadeIn();
                        }
                    } else {
                        $("#des").html("No se encontró producto");
                    }
                });
            }
        }
    }

    var guardado = 0;

    function BuscarSub(texto, id) {
        $("#infoadi").fadeIn();
        if (texto.length > 2) {
            $.get("BuscarSubproducto", {'texto': texto, 'id': id}, function (data) {
                $("#subprod" + id).html(data);
            });
        } else {
            $("#subprod" + id).html("");
        }
    }

    function Seleccionar(idseccion, idsubproducto, texto) {
        $("#subprod" + idseccion).html("");
        $("#inputsub" + idseccion).val("");
        $("#subproductosagregados" + idseccion).fadeIn();
        $("#subproductosagregados" + idseccion).append("<label class='input-control checkbox'>");
        $("#subproductosagregados" + idseccion).append('<input type="checkbox" class="checksub' + idseccion + '" checked value="' + idsubproducto + '"> ');
        $("#subproductosagregados" + idseccion).append(' <span class="check"></span>');
        $("#subproductosagregados" + idseccion).append('<span class="caption">' + texto + '</span>');
        $("#subproductosagregados" + idseccion).append('</label>');
        $("#subproductosagregados" + idseccion).append("<br>");
    }

    function GuardarSubproductos(id, id_detalle) {
        $(".checksub" + id + ":checked").each(function () {
            $.get("GuardarSubproducto", {
                'detalle_id': id_detalle,
                'subproducto_id': $(this).val()
            }, function (id_sub) {
                $.Notify({
                    caption: 'Correcto',
                    content: 'El subproducto se guardó correctamente',
                    type: 'success'
                });
            });
        });
        alert("Información registrada correctamente");
        Cancelar();
    }

    function GuardarDevolucion() {
        var cliente = $("#cliente").val();
        var motivo = $("#motivo").val();
        var responsable = $("#responsable").val();
        if (cliente == "" || motivo == "" || responsable == "") {
            $.Notify({
                caption: 'Error',
                content: 'Es necesario capturar el cliente, responsable y motivo',
                type: 'alert'
            });
        } else {
            $.get("GuardarDevolucion", {
                'cliente': cliente,
                'motivo': motivo,
                'responsable': responsable
            }, function (data) {
                if (data > 0) {
                    $("input[name='IDS[]']:checked").each(function () {
                        var idprod = $(this).val();
                        //alert("Se guardaría el detalle de la devolución prod" + $(this).val());
                        $.get("GuardarDetalleDevolucion", {
                            'devolucion_id': data,
                            'producto_id': idprod
                        }, function (id_detalle) {
                            if (id_detalle == "existe") {
                                alert("El producto con clave " + idprod + " no se ha agegado a la devolución ya que existe en el almacén de devoluciones. ")
                            } else {
                                GuardarSubproductos(idprod, id_detalle);
                            }
                        });
                    });
                }
            });
        }
    }
    function GuardarProducto() {
        if (guardado == 0) {
            $("input[name='IDS[]']:checked").each(function () {
                var id = $(this).val();
                $.post("GuardarProductoCedis", {"idproducto": $(this).val()}, function (data) {
                    if (data == "Correcto") {
                        $("#td" + id).html('<span class="mif-checkmark fg-green"></span> Producto Guardado');
                    } else if (data == "Existe") {
                        $("#td" + id).html("<span class='mif-cancel fg-red'></span> El producto ya se encuentra <br>en el CEDIS");
                    } else {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error al guardar el producto',
                            type: 'alert'
                        });
                    }
                });
            });
            guardado = 1;
            $("#botonguardar").fadeOut();
            $("#nuevatarima").fadeIn();
        }
        //$("#tablaproductos").empty();
    }
    function Cancelar() {
        location.reload(true);
    }
</script>
<h1><b> DEVOLUCIONES</b></h1><br>
<center>
    <div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
        <ul class="tabs">
            <li class="active"><a href="#capturadevolucion">Captura Devolución</a></li>
            <li><a href="#devolucionescapturadas">Devoluciones Capturadas</a></li>
        </ul>
        <div class="frames">
            <div class="frame" id="capturadevolucion">
                <div class="panel warning" data-role="panel">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkOrange"></span>
                        <span class="title">Ingresar Código de Barras de producto</span>
                    </div>
                    <div class="content" id="Inicio">
                        <table class="table">
                            <tr>
                                <td class="center">
                                    <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                                    <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                        <input type="text" id="claveProd" onkeyup="VerificarClave(event)">
                                    </div>
                                    <br><label><span id="des"></span></label>
                                </td>
                            </tr>
                            <br><br>
                        </table>
                    </div>
                </div>
                <div id="resultadosproductos" style="display: none">
                    <center>
                        <div class="panel primary" data-role="panel">
                            <div class="heading">
                                <span class="icon mif-stack fg-white bg-darkBlue"></span>
                                <span class="title">Detalle de productos agregados</span>
                            </div>
                            <div class="content" id="Resultados">
                                <table class="table bordered border hovered" id="tablaproductos">
                                    <thead>
                                        <tr>
                                            <th>Seleccion/Acción</th>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>Subproductos</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
                    </center>
                </div>
                <div class="panel success" data-role="panel" id="infoadi" style="display: none;">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkGreen"></span>
                        <span class="title">Información adicional</span>
                    </div>
                    <div class="content" id="">
                        <table class="table hovered bordered border">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Motivo</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                            <input type="text" id="cliente">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                            <input type="text" id="motivo">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                            <input type="text" id="responsable">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <tr>
                                <td class="center" id="Botones"><br>
                                    <div class="input-control text big-input medium-size" id="nuevatarima2"
                                         style="display: none">
                                        <button class="button warning" onclick="Cancelar()">Nueva Devolución
                                        </button>
                                    </div>
                                    <div class="input-control text big-input medium-size" id="botonguardar2">
                                        <button class="button success" onclick="GuardarDevolucion()">Guardar
                                        </button>
                                    </div>
                                    <div class="input-control text big-input medium-size">
                                        <button class="button danger" onclick="Cancelar()">Cancelar</button>
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="frame" id="devolucionescapturadas">
                <div class="panel warning" data-role="panel">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkOrange"></span>
                        <span class="title">Lista de devoluciones registradas</span>
                    </div>
                    <div class="content">
                        <table class="table shadow" >
                            <thead>
                                <tr>
                                    <th class="align-center">Fecha Inicio</th>
                                    <th class="center">Fecha Fin</th>
                                    <th class="center">Consultar</th>
                                </tr>
                            </thead>
                            <tr>
                                <td class="center">
                                    <div class="input-control text full-size" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker">
                                        <input type="text" id="fechainicio" value="<?= $hoy ?>">
                                        <button class="button"><span class="mif-calendar"></span></button>
                                    </div>
                                </td>
                                <td class="center">
                                    <div class="input-control text full-size" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker">
                                        <input type="text" id="fechafin" value="<?= $hoy ?>">
                                        <button class="button"><span class="mif-calendar"></span></button>
                                    </div>
                                </td>
                                <td class="center">
                                    <div class="input-control text big-input medium-size" id="botonguardar">
                                        <button class="button success" onclick="CargarDevoluciones()">Consultar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div id="devolucionesconsultadas"></div>
                    </div>
                </div>
                <div id="resultadosproductos" style="display: none">
                    <center>
                        <div class="panel primary" data-role="panel">
                            <div class="heading">
                                <span class="icon mif-stack fg-white bg-darkBlue"></span>
                                <span class="title">Detalle de Productos agregados</span>
                            </div>
                            <div class="content" id="Resultados">
                                <table class="table bordered border hovered" id="tablaproductos">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>Seleccion/Acción</th>
                                        </tr>
                                    </thead>
                                </table>
                                <table>
                                    <tr>
                                        <td class="center" id="Botones"><br>
                                            <div class="input-control text big-input medium-size" id="nuevatarima"
                                                 style="display: none">
                                                <button class="button warning" onclick="Cancelar()">Nueva Entrada
                                                </button>
                                            </div>
                                            <div class="input-control text big-input medium-size" id="botonguardar">
                                                <button class="button success" onclick="GuardarProducto()">Guardar
                                                </button>
                                            </div>
                                            <div class="input-control text big-input medium-size">
                                                <button class="button danger" onclick="Cancelar()">Cancelar</button>
                                            </div>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

</center><br><br><br>


<script>
    function CargarDevoluciones()
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        $("#devolucionesconsultadas").load("DevolucionesCapturadas", {"fechainicio": fechainicio, "fechafin": fechafin});
    }
    $(document).ready(function () {
        CargarDevoluciones();
    });
</script>
