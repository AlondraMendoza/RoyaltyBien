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
        </tr>
    </thead>
    <?php foreach ($devolucionescapturadas->result() as $dev): ?>
        <?php if ($dev->VerificadaSupervisor == "Si"): ?>
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
                                        <label class="input-control checkbox">
                                            <input <?php if ($sub->Verificado == "Si") { ?>checked<?php } ?> type="checkbox" id="veri<?= $sub->IdSubproductosDevoluciones ?>"onclick="VerificarSubproducto(<?= $sub->IdSubproductosDevoluciones ?>)" disabled="disabled">
                                            <span class="check"></span>
                                            <span class="caption"><?= $sub->Descripcion ?> / <?= $sub->Clave ?> </span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                    </ol>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
<script>
    function ProcesarDevolucion(id)
    {
        $.get("ProcesarDevolucion", {"dev_id": id}, function (data) {
            $("#botonguardar" + id).html("<center>Procesada</center>");
            if (data == "correcto") {
                $.Notify({
                    caption: 'Correcto',
                    content: 'La devolución se procesó correctamente',
                    type: 'success'
                });
            }
        });
    }
    function VerificarSubproducto(id)
    {
        var valor = "No";
        if ($("#veri" + id).prop('checked')) {
            valor = "Si";
        }
        $.get("VerificarSubproducto", {"sub_id": id, "valor": valor}, function (data) {

            if (data == "correcto") {
                $.Notify({
                    caption: 'Correcto',
                    content: 'El subproducto se verificó correctamente',
                    type: 'success'
                });
            }
        });
    }
</script>