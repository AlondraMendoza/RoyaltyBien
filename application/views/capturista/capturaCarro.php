
<script>
    $(document).ready(function () {

    });
    
    var Producto="";
    var Modelo="";
    var Color="";

    function AbrirModelos(id)
    {
        Producto=id;
        $("#MostrarModelos").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando modelos');
        $("#MostrarModelos").load("ObtenerModelos", {"id": id, "withouttem": 1});
    }

    function AbrirColores(id)
    {
        Modelo=id;
        $("#MostrarColores").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando colores');
        $("#MostrarColores").load("ObtenerColores", {"id": id, "withouttem": 1});
    }

    function VerOtros(id)
    {
        Color=id;
        $("#DivOtros").fadeIn();
    }
    
    function SeleccionCarro()
    {
        $('#carro').change(function(){
        $(this).val();
        });
    }
    
    function SeleccionHorno()
    {
        $('#SHorno').change(function(){
        $(this).val();
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
        $("#Resultados").load("capturista/Resultados", {"carro": carro,"horno": horno,"prod": prod,"mod": mod,"col": col,"piezas": piezas,"fecha": fecha, "withouttem": 1});
    }
    
    function Cancelar()
    {
        
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
                                <?php if ($carros->num_rows() == 0) { ?>
                                <option value="0">No hay carros</option>
                                <?php } else { ?>
                                <?php foreach ($carros->result() as $carro): ?>
                                <?php
                                $ci = &get_instance();
                                $ci->load->model("modelocapturista");
                                //$npen = $ci->modelocapturista->ProductosPendientesHornos($d, $horno->IdHornos);
                                ?>
                                <option value="<?= $carro->IdCarros ?>"><?= $carro->Nombre.""; ?></option>
                                <?php endforeach; ?>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el horno:</b><br>
                        <div class="input-control select full-size" style="height: 80px;">   
                            <select id="SHorno" onchange="SeleccionHorno()">   
                                <?php if ($hornos->num_rows() == 0) { ?>
                                <option value="0">No hay hornos</option>
                                <?php } else { ?>
                                <?php foreach ($hornos->result() as $horno): ?>
                                <?php
                                $ci = &get_instance();
                                $ci->load->model("modelocapturista");
                                //$npen = $ci->modelocapturista->ProductosPendientesHornos($d, $horno->IdHornos);
                                ?>
                                <option value="<?= $horno->IdHornos ?>"><?= "Horno " . $horno->NHorno.""; ?></option>
                                <?php endforeach; ?>
                                <?php } ?>
                            </select>
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
                    //$npen = $ci->modelocapturista->ListarModelos($prod->IdCProductos);?>
                    <button class="button" style='width: 210px; height: 210px;' onclick="AbrirModelos(<?= $prod->IdCProductos?>)">
                            <input type="image" src="http://localhost/RoyaltyBien/public/imagenes/<?= $prod->Imagen ?>" height="190px;" width="190px;" title="<?= $prod->Nombre ?>"/><b>
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
                            <div class="input-control text big-input full-size" data-role="datepicker" id="datepicker" data-locale="es" data-format="dd-mm-yyyy" style="height:80px;font-size: x-large">
                                <input type="text" id="fecha">
                                <button class="button"><span class="mif-calendar"></span></button>
                            </div>
                        </td>
                        <td class="center"><br>
                            <div class="input-control text big-input medium-size">
                            <button class="button primary" onclick="Siguiente()">Siguiente</button></div>
                            <div class="input-control text big-input medium-size">
                            <button class="button danger" onclick="Cancelar()">Cancelar</button></div>
                        </td>
                    </tr>
                    <br><br>
                </table>
            </div>
        </div>
    </div>
</center><br><br><br>

