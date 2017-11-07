<script>
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
        CargarProductos();
    });
</script>

<h1 class="light text-shadow">CLASIFICACIÓN</h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Filtros</span>
        </div>
        <div class="content">
            <table class="table">
                <tr>
                    <td style="width: 50%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona la fecha de captura:</b><br> 
                        <div class="input-control text full-size" style="height:80px;font-size: x-large" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker" data-on-select="CargarHornos(d)">
                            <input type="text" id="fecha" value="<?= $hoy ?>">
                            <button class="button" style="height: 80px"><span class="mif-calendar"></span></button>
                        </div>
                    </td>
                    <td id="tdhornos" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el horno de quemado:</b><br>
                        <div id="" class="input-control select full-size" style="height: 80px;">
                            <select onchange="CargarProductos()" id="hornos">
                                <?php
                                $rhornos = $datos["hornos"];
                                if ($rhornos->num_rows == 0) {
                                    ?><option value="0" >No hay productos pendientes de clasificar</option><?php
                                } else {
                                    while ($fila = mysqli_fetch_assoc($rhornos)) {
                                        $pendientes = Models\Hornos::ListaProductosDia($datos["hoyingles"], $fila["IdHornos"]);
                                        $npen = $pendientes->num_rows;
                                        ?>
                                        <option value="<?php echo $fila["IdHornos"]; ?>"><?php echo "Horno " . $fila["NHorno"] . " - " . $npen . " prod. pendiente(s) de clasificar"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="background-color: gray;height: 1px;">
            <div id="divclasificacion"></div>
            <br><br><br><br><br><br>
        </div>

    </div>
    <br><br><br><br><br><br>
</center>
