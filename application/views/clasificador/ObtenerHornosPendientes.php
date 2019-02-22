<strong style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el horno de quemado:</strong><br>
<div id="" class="input-control select full-size" style="height: 80px;">
    <select onselect= "CargarProductos()" id="hornos" onchange="CargarProductos()">
        <?php
        $rhornos = $datos["hornos"];
        if ($rhornos->num_rows == 0) {
            ?><option value="0">No hay productos pendientes de clasificar</option><?php
        } else {
            while ($fila = mysqli_fetch_assoc($rhornos)) {
                $pendientes = Models\Hornos::ListaProductosDia($datos["dia"], $fila["IdHornos"]);
                $npen = $pendientes->num_rows;
                ?>
                <option value="<?php echo $fila["IdHornos"]; ?>"><?php echo "Horno " . $fila["NHorno"] . " - " . $npen . " prod. pendiente(s) de clasificar"; ?></option>
                <?php
            }
        }
        ?>
    </select>
</div>