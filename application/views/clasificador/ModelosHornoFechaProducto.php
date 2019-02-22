<?php
$modelos = $datos["modelos"];
?>
<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el modelo</b>

<div class="grid" >
    <div class="row cells1">
        <div class="cell" >
            <span onclick="CargarProductos()" style="font-size: 5em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de productos"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            while ($fila = mysqli_fetch_assoc($modelos)) {
                ?>
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="CargarColores(<?php echo $fila["ModelosId"] . ',' . $fila["CProductosId"]; ?>)">
                    <div class="frame">
                        <img src="<?php echo URL; ?>Views/template/imagenes/<?php echo $fila["Imagen"]; ?>" height="190px;" width="190px;" title="<?php echo $fila["Nombre"]; ?>">        
                    </div>
                    <div class="image-overlay op-orange">
                        <h2><?php echo $fila["Nombre"]; ?></h2>
                        <p>
                            <?php
                            $pendientes = Models\Productos::ListarProductosHornoFechaCProdModelo($datos["dia"], $fila["HornosId"], $fila["CProductosId"], $fila["ModelosId"]);
                            ?>
                            <?php echo $pendientes; ?> productos pendientes de clasificación
                        </p>
                    </div>
                </div>
            <?php }
            ?>  </div>
    </div>

</div>
