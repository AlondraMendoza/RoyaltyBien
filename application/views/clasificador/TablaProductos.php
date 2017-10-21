

<?php
$productos = $datos["productos"];
$cont = 1;
while ($fila = mysqli_fetch_assoc($productos)) {
    $oculto = "";
    if ($cont > 1) {
        $oculto = "display:none";
    }
    ?>
    <div class="tablasproductos" id="tabla<?php echo $cont; ?>" style="font-size: 1.2em;<?php echo $oculto; ?>" >
        <table class="table hovered border " >

            <tr class="center primary">
                <td class="center" style="text-align: center"><span onclick="CargarColores(<?php echo $datos["mod"] . ',' . $datos["cprod"]; ?>)" style="font-size: 3em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de Colores"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="center" style="text-align: center"><b>Clave producto:</b><br> <?php echo $fila["IdProductos"]; ?></td>
                <td class="center" style="text-align: center"><b>Nombre producto:</b><br> <?php echo $fila["NombreProducto"]; ?></td>
                <td class="center" style="text-align: center"><b>Modelo:</b><br> <?php echo $fila["NombreModelo"]; ?></td>
                <td class="center" style="text-align: center"><b>Color:</b><br> <?php echo $fila["NombreColor"]; ?></td>
            </tr>

        </table>
        <table class="table">
            <tr>
                <td class="center">
                    <b>Categoría defectos</b><br>
                    <div id="" class="input-control select full-size" style="height: 80px;">
                        <select onchange="CargarDefectos(<?php echo $fila["IdProductos"]; ?>)" id="catdefectos<?php echo $fila["IdProductos"]; ?>">
                            <option value="0">Selecciona categoría</option>
                            <?php
                            $catdefectos = Models\FuncionesUsuario::CategoriasDefectos();
                            while ($filadef = mysqli_fetch_assoc($catdefectos)) {
                                ?>
                                <option value="<?php echo $filadef["IdCatDefectos"]; ?>"><?php echo $filadef["Nombre"]; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </td>
                <td id="tddefectos<?php echo $fila["IdProductos"]; ?>" class="center" colspan="2">

                </td>
                <td class="center">
                    <b>Clave de empleado</b><br>
                    <input type="text" id="claveempleado" style="height: 80px;font-size: 1.6em">
                </td>
                <td><button class="button primary block-shadow-info text-shadow" style="height:80px"><span class="mif-plus fg-white " style="font-size: 4em"></span></button></td>
            </tr>
        </table>
    </div>
    <?php
    $cont++;
}
?>
<p style="text-align: right;margin-right: 10px;"><button id="botonsiguiente" style="height: 80px" class="button block-shadow-success text-shadow success big-button" onclick="Siguiente()"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> Siguiente producto</button></p>
<script>
    function CargarDefectos(idprod)
    {
        var idcat = $("#catdefectos" + idprod).val();
        if (idcat != 0)
        {
            $("#tddefectos" + idprod).load("clasificador/CargarDefectos", {"cat_id": idcat, "withouttem": 1, "idprod": idprod});
        }
    }
    var cont = 2;
    var ultimo = <?php echo $cont - 1; ?>;
    function Siguiente()
    {
        $(".tablasproductos").hide();
        $("#tabla" + cont).fadeIn();
        if (ultimo == cont)
        {
            $("#botonsiguiente").fadeOut();
        }
        cont++;


    }
</script>