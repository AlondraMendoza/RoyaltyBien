<?php $modelo = $datos["modelo"];?>
<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el modelo:</b>
<div class="grid" >
    <div data-role="group" data-group-type="one-state">
        <?php
            while ($fila = mysqli_fetch_assoc($modelo)) {
            ?>
            <button class="button" style='width: 210px; height: 210px;' onclick="AbrirColores(<?php echo $fila["IdModelos"];?>)">
            <input type="image" src="<?php echo URL; ?>Views/template/imagenes/<?php echo $fila["Imagen"];?>" height="190px;" width="190px;" title="<?php echo $fila["Nombre"]; ?>"/><b>
            <?php echo $fila["Nombre"]; ?></b></button>    
            <?php }?>
    </div>   
</div>

