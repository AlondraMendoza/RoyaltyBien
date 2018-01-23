
<script>
    $(document).ready(function () {
        $("#clave").keyup(function () {
            $("#tdhornero").html($("#clave").val());
        });
        $("#piezas").keyup(function () {
            $("#tdcantidad").html($("#piezas").val());
        });
        $("#fecha").change(function () {
            $("#tdfecha").html($("#fecha").val());
        });
    });

    var Producto = "";
    var Modelo = "";
    var Color = "";

    function AbrirModelos(id)
    {
        $("#tdproducto").html($("#producto" + id).text());
        Producto = id;
        $("#MostrarModelos").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando modelos');
        $("#MostrarModelos").load("ObtenerModelos", {"id": id, "withouttem": 1});
    }

    function AbrirColores(id)
    {
        $("#tdmodelo").html($("#modelo" + id).text());
        Modelo = id;
        $("#MostrarColores").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando colores');
        $("#MostrarColores").load("ObtenerColores", {"id": id, "withouttem": 1});
    }

    function VerOtros(id)
    {
        $("#tdcolor").html($("#color" + id).text());
        Color = id;
        $("#DivOtros").fadeIn();
    }

    function SeleccionCarro()
    {
        $("#tdcarro").html($("#carro option:selected").text());
    }

    function SeleccionHorno()
    {
        $("#tdhorno").html($('#SHorno option:selected').text());
    }
    
    function Notificacion(titulo, texto, icono, color)
    {
        $.Notify({
            caption: titulo,
            content: texto,
            icon: "<span class='mif-" + icono + "'></span>",
            type: color
        });
    }

    function Siguiente()
    {
        var carro = $("#carro").val();
        var horno = $('#SHorno').val();
        var prod = Producto;
        var mod = Modelo;
        var col = Color;
        var piezas = $("#piezas").val();
        var fecha = $("#fecha").val();
        var hornero = $("#idpu").val();
        if(carro ==0){
            Notificacion("Error", "Selecciona el carro antes de continuar", "cancel", "alert");
            return(0);
        }
        if(horno ==0){
            Notificacion("Error", "Selecciona el horno antes de continuar", "cancel", "alert");
            return(0);
        }
        if(piezas == ""){
            Notificacion("Error", "Captura el número de piezas antes de continuar", "cancel", "alert");
            return(0);
        }
        if(fecha == ""){
            Notificacion("Error", "Selecciona la fecha antes de continuar", "cancel", "alert");
            return(0);
        }
        if(hornero == ""){
            Notificacion("Error", "Captura el número de hornero antes de continuar", "cancel", "alert");
            return(0);
        }
        $.get("Resultados", {"carro": carro, "horno": horno, "prod": prod, "mod": mod, "col": col, "piezas": piezas, "fecha": fecha, "hornero": hornero, "withouttem": 1},function(){
            $("#Lista").fadeIn();
            $("#ListaTabla").append("<tr>"+$("#trprevia").html()+"</tr>");
            $("#tdcarro").html("");
            $("#tdhornero").html("");
            $("#tdmodelo").html("");
            $("#tdproducto").html("");
            $("#tdhorno").html("");
            $("#tdcolor").html("");
            $("#tdcantidad").html("");
            $("#tdfecha").html(""); 
        });
        $("#carro").val("");
        $('#SHorno').val("");
        Producto="";
        Modelo="";
        Color="";
        $("#piezas").val("");
        $("#fecha").val("");
        $("#clave").val("");
        $("#idpu").val("");
        $("#NombreC").val("");
        
    }

    function Cancelar(){
    $("#carro").val("");
        $('#SHorno').val("");
        Producto="";
        Modelo="";
        Color="";
        $("#piezas").val("");
        $("#fecha").val("");
        $("#clave").val("");
        $("#idpu").val("");
        $("#NombreC").val("");
    }

    function VerificarClave() {
        var clave = $("#clave").val();
        $("#NombreC").html("Verificando clave");
        $.getJSON("VerificarClave", {"clave": clave}, function (data) {
            $("#NombreC").html(data.nombrec);
            $("#idpu").val(data.idpu);
        });
    }
</script>
<h1><b> CAPTURA DE PRODUCTOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar datos</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td style="width: 50%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Clave del carro:</b><br>
                        <div class="input-control select full-size" style="height: 80px;">   
                            <select id="carro" onchange="SeleccionCarro()">                                    
                                <option value="0">Selecciona el carro</option>
                                <?php if ($carros->num_rows() == 0) { ?>
                                    <option value="0">No hay carros</option>
                                <?php } else { ?>
                                    <?php foreach ($carros->result() as $carro): ?>
                                        <?php
                                        $ci = &get_instance();
                                        $ci->load->model("modelocapturista");
                                        //$npen = $ci->modelocapturista->ProductosPendientesHornos($d, $horno->IdHornos);
                                        ?>
                                        <option value="<?= $carro->IdCarros ?>"><?= $carro->Nombre . ""; ?></option>
                                    <?php endforeach; ?>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td style="width: 50%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el horno:</b><br>
                        <div class="input-control select full-size" style="height: 80px;">   
                            <select id="SHorno" onchange="SeleccionHorno()">  
                                <option value="0">Selecciona el horno</option>
                                <?php if ($hornos->num_rows() == 0) { ?>
                                    <option value="0">No hay hornos</option>
                                <?php } else { ?>
                                    <?php foreach ($hornos->result() as $horno): ?>
                                        <?php
                                        $ci = &get_instance();
                                        $ci->load->model("modelocapturista");
                                        //$npen = $ci->modelocapturista->ProductosPendientesHornos($d, $horno->IdHornos);
                                        ?>
                                        <option value="<?= $horno->IdHornos ?>"><?= "Horno " . $horno->NHorno . ""; ?></option>
                                    <?php endforeach; ?>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">N° de Hornero de quema:</b><br>
                        <div class="input-control text full-size"  style="height:80px;font-size: x-large">
                            <input type="text" id="clave" onkeyup="VerificarClave()">
                            <input type="hidden" id="idpu">
                        </div>
                    </td>
                    <td style="width:40%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Nombre del hornero:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <br><span id="NombreC"></span>
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="background-color: gray;height: 1px;">
            <div id="MostrarProd">
                <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el Producto:</b>
                <div class="grid" >
                    <div data-role="group" data-group-type="one-state">   
                        <?php if ($productos->num_rows() == 0) { ?>
                            <label>No hay Productos</label>
                        <?php } else { ?>
                            <?php foreach ($productos->result() as $prod): ?>
                                <?php
                                $ci = &get_instance();
                                $ci->load->model("modelocapturista");
                                //$npen = $ci->modelocapturista->ListarModelos($prod->IdCProductos);
                                ?>
                                <button id="producto<?= $prod->IdCProductos ?>" class="button" style='width: 210px; height: 210px;' onclick="AbrirModelos(<?= $prod->IdCProductos ?>)">
                                    <input  type="image" src="<?php echo base_url() ?>public/imagenes/<?= $prod->Imagen ?>" height="190px;" width="190px;" title="<?= $prod->Nombre ?>"/><b>
                                <?= $prod->Nombre ?></b></button>
                            <?php endforeach; ?>
<?php } ?>
                    </div>
                </div>
            </div>
            <hr style="background-color: gray;height: 1px;">
            <div id="MostrarModelos"></div>
            <hr style="background-color: gray;height: 1px;">
            <div id="MostrarColores"></div>
            <hr style="background-color: gray;height: 1px;">
            <div id="DivOtros" style="display: none">
                <table class="table">
                    <tr>
                        <td style="width: 32%" class="center">
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad de piezas:</b><br> 
                            <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                <input type="text" id="piezas" placeholder="Teclea la cantidad">
                            </div>
                        </td>
                        <td style="width: 32%" class="center">
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha de quemado:</b><br>
                            <div class="input-control text big-input full-size" data-role="datepicker" id="datepicker" data-locale="es" data-format="dd-mm-yyyy" style="height:80px;font-size: x-large;z-index: 99999">
                                <input type="text" id="fecha">
                                <button class="button"><span class="mif-calendar"></span></button>
                            </div>
                        </td>
                    </tr>
                    <tr><td><br><br></td></tr>
                    <tr>
                        <td></td>
                        <td class="center" id="Botones"><br>
                            <div class="input-control text big-input medium-size">
                                <button class="button success" onclick="Siguiente()">Siguiente</button></div>
                            <div class="input-control text big-input medium-size">
                                <button class="button danger" onclick="Cancelar()">Cancelar</button></div>
                        </td>
                    </tr>
                    <br><br>
                </table>
            </div>
        </div>
    </div><br><br>
    <div class="panel primary" data-role="panel" id="" style="z-index: 1">
        <div class="heading" style="position:relative;z-index: 1">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Detalle de Carro</span>
        </div>
        <div class="content" id="">
            <div id="Resultados">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Carro:</th>
                            <th>Horno:</th>
                            <th>Hornero:</th>
                            <th>Producto:</th>
                            <th>Modelo:</th>
                            <th>Color:</th>
                            <th>Cantidad:</th>
                            <th>Fecha Quemado:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="trprevia">
                            <td id="tdcarro"></td>
                            <td id="tdhorno"></td>
                            <td id="tdhornero"></td>
                            <td id="tdproducto"></td>
                            <td id="tdmodelo"></td>
                            <td id="tdcolor"></td>
                            <td id="tdcantidad"></td>
                            <td id="tdfecha"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
        </div>
    </div>
    <br>
    <div class="panel success" data-role="panel" style="display: none;" id="Lista">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkGreen"></span>
            <span class="title">Detalle de capturas</span>
        </div>
        <div class="content" id="">
            <table class="table" id="ListaTabla">
                <thead>
                        <tr>
                            <th>Carro:</th>
                            <th>Horno:</th>
                            <th>Hornero:</th>
                            <th>Producto:</th>
                            <th>Modelo:</th>
                            <th>Color:</th>
                            <th>Cantidad:</th>
                            <th>Fecha Quemado:</th>
                        </tr>
                    </thead>
            </table>
        </div>
    </div>
</center><br><br><br>


