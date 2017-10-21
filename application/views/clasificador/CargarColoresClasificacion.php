<?php
$colores = $datos["colores"];
?>
<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el color</b>

<div class="grid" >
    <div class="row cells1">
        <div class="cell" >
            <span onclick="CargarModelos(<?php echo $datos["cprod"]; ?>)" style="font-size: 5em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de Modelos"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            while ($fila = mysqli_fetch_assoc($colores)) {
                ?>
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="TablaProductos(<?php echo $fila["CProductosId"] . ',' . $fila["ModelosId"] . ',' . $fila["IdColores"]; ?>)">
                    <div class="frame">
                        <img src="<?php echo URL; ?>Views/template/colores/<?php echo $fila["Descripcion"]; ?>" height="190px;" width="190px;" title="<?php echo $fila["Nombre"]; ?>">        
                    </div>
                    <div class="image-overlay op-orange">
                        <h2><?php echo $fila["Nombre"]; ?></h2>
                        <p>
                            <?php
                            $pendientes = Models\Productos::ProdPendientesColores($datos["dia"], $fila["HornosId"], $fila["CProductosId"], $fila["ModelosId"], $fila["IdColores"]);
                            ?>
                            <?php echo $pendientes; ?> producto(s) pendiente(s) de clasificación
                        </p>
                    </div>
                </div>
            <?php }
            ?></div>
    </div>
</div>
