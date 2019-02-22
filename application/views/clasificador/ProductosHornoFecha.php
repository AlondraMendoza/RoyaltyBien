<?php
$productos = $datos["productos"];
?>
<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el producto</b>

<div class="grid" >
    <div class="row cells1">
        <div class="cell" >
            <?php
            while ($fila = mysqli_fetch_assoc($productos)) {
                ?>

                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="CargarModelos(<?php echo $fila["IdCProductos"]; ?>)">
                    <div class="frame">
                        <img src="<?php echo URL; ?>Views/template/imagenes/<?php echo $fila["Imagen"]; ?>" height="190px;" width="190px;" title="<?php echo $fila["Nombre"]; ?>">        
                    </div>
                    <div class="image-overlay op-green">
                        <h2><?php echo $fila["Nombre"]; ?></h2>
                        <p>
                            <?php
                            $pendientes = Models\Productos::ListarProductosHornoFechaCProd($datos["dia"], $fila["HornosId"], $fila["CProductosId"]);
                            ?>
                            <?php echo $pendientes; ?> prod. pendiente(s) de clasificación.
                        </p>
                    </div>
                </div>

            <?php }
            ?>  </div>
    </div>   
</div>