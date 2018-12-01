<?php
$ci = &get_instance();
$ci->load->model("modelocedis");
?>
<table class="table bordered border hovered" id="tablaproductos" data-role="datatable">
    <thead>
        <tr>
            <th>Código</th>
            <th>FechaCaptura</th>
            <th>Motivo</th>
            <th>Responsable</th>
            <th>Cliente</th>
            <th>Productos</th>
            <th>Enviada Clasificación</th>
        </tr> 
    </thead>
    <?php foreach ($devolucionescapturadas->result() as $dev): ?>
        <tr>
            <td><?= $dev->IdDevoluciones ?></td>
            <td><?= $dev->FechaCaptura ?></td>
            <td><?= $dev->Motivo ?></td>
            <td><?= $dev->Responsable ?></td>
            <td><?= $dev->Cliente ?></td>
            <td>
                <?php $detalles = $ci->modelocedis->DetalleDevolucionesCapturadas($dev->IdDevoluciones); ?>
                <ol class="simple-list large-bullet fg-blue">
                    <?php foreach ($detalles->result() as $detalle): ?>
                        <li>   
                            <b><?= $detalle->producto ?> / <?= $detalle->color ?> / <?= $detalle->modelo ?></b>                        
                        </li>
                        <ul class="simple-list green-bullet fg-green" >
                            <?php $subproductos = $ci->modelocedis->SubproductosDetalle($detalle->IdDetalleDevoluciones); ?>
                            <?php foreach ($subproductos->result() as $sub): ?>
                                <li style="margin-left: 20px">
                                    <?= $sub->Descripcion ?> / 
                                    <?= $sub->Clave ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    <?php endforeach; ?>
                </ol>
            </td>
            <td>
                <label class="input-control checkbox">
                    <input type="checkbox" id="enviada<?= $dev->IdDevoluciones?>" onchange="CambioEnviada(<?= $dev->IdDevoluciones?>)" <?= $dev->CheckEnviada? 'checked':'' ?>>
                    <span class="check"></span>
                    <span class="caption"></span>
                </label>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    function CambioEnviada(dev)
    {
        $.get("CambioEnviada",{"devolucion_id":dev},function(data){
            if(data.includes("correcto"))
            {
                MsjCorrecto("La devolución se actualizó correctamente.");
            }
        });
    }
</script>