<strong style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el horno de quemado:</strong><br>
<div id="" class="input-control select full-size" style="height: 80px;">
    <select onselect= "CargarProductos()" id="hornos" onchange="CargarProductos()">
        <?php if ($hornos->num_rows() == 0) { ?>
            <option value="0">No hay productos pendientes de clasificar</option>
        <?php } else { ?>
            <?php foreach ($hornos->result() as $horno): ?>
                <?php
                $ci = &get_instance();
                $ci->load->model("modeloclasificador");
                $npen = $ci->modeloclasificador->ProductosPendientesHornos($d, $horno->IdHornos);
                ?>
                <option value="<?= $horno->IdHornos ?>"><?= "Horno " . $horno->NHorno . " - " . $npen . " prod. pendiente(s) de clasificar"; ?></option>
            <?php endforeach; ?>

        <?php } ?>
    </select>
</div>