<?php $color = $datos["color"];?>
<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el color:</b>
<div class="grid" >
    <div data-role="group" data-group-type="one-state">
        <?php
            while ($fila = mysqli_fetch_assoc($color)) {
            ?>
            <button class="button" style='width: 210px; height: 210px;' onclick="VerOtros(<?php echo $fila["IdColores"];?>)">
            <input type="image" src="<?php echo URL; ?>Views/template/colores/<?php echo $fila["Descripcion"];?>" height="190px;" width="190px;" title="<?php echo $fila["Nombre"]; ?>"/><b>
            <?php echo $fila["Nombre"]; ?></b></button>    
            <?php }?>
    </div>   
</div>

