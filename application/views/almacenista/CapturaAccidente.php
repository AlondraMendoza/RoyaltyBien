<script>   
    function VerificarClaveTarima(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des2").html("Verificando clave de tarima...");
                $.getJSON("VerificarClaveTarimaAlmacen", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des2").html("Tarima encontrada");
                        if ($("#td2" + data.id).length)
                        {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ya agregaste la tarima a la lista',
                                type: 'alert'
                            });
                            $("#claveProd").val("");
                            $("#des2").html("");
                        } else {
                            //metodo para Abrir tabla y agregar datos
                            var input = '<tr><td class="center">' + data.id + '</td>';
                            input += '</td>';
                            input += '<td class="center" id="td2' + data.id + '"><label class="input-control checkbox">';
                            input += '<input type="checkbox" name="IDS2[]" value="' + data.id + '" checked>';
                            input += '<span class="check"></span>';
                            input += '</label></td></tr>';
                            $("#tablatarimas").append(input);
                            $("#claveProd").val("");
                            $("#des2").html("");
                            $("#resultadostarimas").fadeIn();
                        }
                    } else
                    {
                        $("#des2").html("No se encontró la tarima");
                    }
                });
            }
        }
     }

var guardado = 0;
    function GuardarAccidenteT() {
    var Responsable = $("#ResponsableT").val();
    var Motivo= $("#MotivoT").val();
        if (guardado == 0) {
            $("input[name='IDS2[]']:checked").each(function () {
                var id = $(this).val();
                $.post("SalirTarimasAlmacenAccidente", {"idtarima": id, "Responsable": Responsable, "Motivo": Motivo}, function (data) {
                    if (data == "Correcto")
                    {
                        $("#td2" + id).html('<span class="mif-checkmark fg-green"></span> Tarima fuera del almacén');
                    } else if (data == "NoExiste")
                    {
                       $("#td2" + id).html('<span class="mif-cancel fg-red"></span> La tarima no se encontró');
                    } else
                    {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error con la tarima',
                            type: 'alert'
                        });
                    }
                });
            });
            guardado = 1;
            $("#botonguardar2").fadeOut();
            $("#nuevatarima2").fadeIn();
        }
        //$("#tablaproductos").empty();
    }
    
    function Cancelar() {
        location.reload(true);
    }
</script>

<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
        <ul class="tabs">
            <li class="active"><a href="#tarimas">Tarimas</a></li>
            <li><a href="#productos">Productos</a></li>
        </ul>
        <div class="frames">
            <div class="frame" id="tarimas">
<h1><b> ACCIDENTE DE TARIMAS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar Código de Barras</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveProd" onkeyup="VerificarClaveTarima(event)">
                        </div>
                         <br><label><span id="des2"></span></label>
                    </td> 
                </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div id="resultadostarimas" style="display: none">
        <center>
    <div class="panel primary" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Detalle de Tarimas</span>
        </div>
        <div class="content" id="Resultados2">
        <table class="table bordered border hovered" id="tablatarimas">
            <thead>
             <tr>
                <th>Clave</th>
                <th>Seleccion/Acción</th>
             </tr>
           </thead>
        </table>
        <table>
             <tr>
                <td><b>Responsable: <br></b><div class="input-control text full-size">
                            <input type="text" id="ResponsableT">
                        </div></td>
                <td><b>Motivo: <br></b><div class="input-control text full-size">
                            <input type="text" id="MotivoT" >
                        </div></td>
                
            </tr>
        <tr>
            <td class="center" id="Botones"><br>
                <div class="input-control text big-input medium-size" id="nuevatarima2" style="display: none"><button class="button warning" onclick="Cancelar()">Nueva Entrada</button></div>
                <div class="input-control text big-input medium-size" id="botonguardar2"><button class="button success" onclick="GuardarAccidenteT()">Guardar</button></div>
                <div class="input-control text big-input medium-size"><button class="button danger" onclick="Cancelar()">Cancelar</button></div>
            </td>
        </tr>
        </table>
        </div>
    </div>
</center>
    </div>
</center><br><br><br>
</div>
            <div class="frame" id="productos">
<h1><b> ACCIDENTE DE PRODUCTOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar Código de Barras</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveProdP" onkeyup="VerificarClaveTarimaP(event)">
                        </div>
                         <br><label><span id="des2P"></span></label>
                    </td> 
                </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div id="resultadostarimasP" style="display: none">
        <center>
    <div class="panel primary" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Detalle de Productos</span>
        </div>
        <div class="content" id="Resultados2P">
        <table class="table bordered border hovered" id="tablatarimasP">
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
                <td><b>Responsable: <br></b><div class="input-control text full-size">
                            <input type="text" id="Responsable">
                        </div></td>
                <td><b>Motivo: <br></b><div class="input-control text full-size">
                            <input type="text" id="Motivo" >
                        </div></td>
                
            </tr>
        <tr>
            <td class="center" id="BotonesP"><br>
                <div class="input-control text big-input medium-size" id="nuevatarima2P" style="display: none"><button class="button warning" onclick="CancelarP()">Nueva Entrada</button></div>
                <div class="input-control text big-input medium-size" id="botonguardar2P"><button class="button success" onclick="GuardarAccidente()">Guardar Accidente</button></div>
                <div class="input-control text big-input medium-size"><button class="button danger" onclick="CancelarP()">Cancelar</button></div>
            </td>
        </tr>
        </table>
        </div>
    </div>
</center>
    </div>
</center><br><br><br></div>   
</div>
</div>

<script>   
    function VerificarClaveTarimaP(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProdP").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des2P").html("Verificando clave de producto...");
                $.getJSON("VerificarClaveTarimaAlmacenP", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des2P").html("Producto encontrado");
                        if ($("#td2" + data.id).length)
                        {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ya agregaste el producto a la lista',
                                type: 'alert'
                            });
                            $("#claveProdP").val("");
                            $("#des2P").html("");
                        } else {
                            //metodo para Abrir tabla y agregar datos
                            var input = '<tr><td class="center">' + data.id + '</td><td class="center">';
                            input += '<b style="font-size: 1.3em" class="fg-darkEmerald">Descripción:</b><br>';
                            input += data.nombre;
                            input += '</td>';
                            input += '<td class="center" id="td2' + data.id + '"><label class="input-control checkbox">';
                            input += '<input type="checkbox" name="IDS2P[]" value="' + data.id + '" checked>';
                            input += '<span class="check"></span>';
                            input += '</label></td></tr>';
                            $("#tablatarimasP").append(input);
                            $("#claveProdP").val("");
                            $("#des2P").html("");
                            $("#resultadostarimasP").fadeIn();
                        }
                    } else
                    {
                        $("#des2P").html("No se encontró el producto");
                    }
                });
            }
        }
     }

var guardadoP = 0;
    function GuardarAccidente() {
    var Responsable = $("#Responsable").val();
    var Motivo= $("#Motivo").val();
        if (guardadoP == 0) {
            $("input[name='IDS2P[]']:checked").each(function () {
                var id = $(this).val();
                $.post("SalirTarimasAlmacenPAccidente", {"idtarima": id, "Responsable": Responsable, "Motivo": Motivo}, function (data) {
                    if (data == "Correcto")
                    {
                        $("#td2" + id).html('<span class="mif-checkmark fg-green"></span> Producto con accidente fuera del almacén');
                    } else if (data == "NoExiste")
                    {
                       $("#td2" + id).html('<span class="mif-cancel fg-red"></span> El producto no se encontró');
                    } else
                    {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error con el producto',
                            type: 'alert'
                        });
                    }
                });
            });
            guardadoP = 1;
            $("#botonguardar2P").fadeOut();
            $("#nuevatarima2P").fadeIn();
        }
        //$("#tablaproductos").empty();
    }
    
    function CancelarP() {
        location.reload(true);
    }
</script>