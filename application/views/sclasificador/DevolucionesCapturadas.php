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
            <th>Verificar</th>
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

                        <?php $subproductos = $ci->modelocedis->SubproductosDetalle($detalle->IdDetalleDevoluciones); ?>
                        <?php foreach ($subproductos->result() as $sub): ?>
                            <?php if ($dev->VerificadaSupervisor == "No") { ?>
                                <label class="input-control checkbox">
                                    <input <?php if ($sub->Verificado == "Si") { ?>checked<?php } ?> type="checkbox" id="veri<?= $sub->IdSubproductosDevoluciones ?>"onclick="VerificarSubproducto(<?= $sub->IdSubproductosDevoluciones ?>)" class="inputs<?= $dev->IdDevoluciones ?>">
                                    <span class="check"></span>
                                    <span class="caption text-small fg-darkGreen"><?= $sub->Descripcion ?> / <?= $sub->Clave ?> </span>
                                </label>
                                <br>
                            <?php } else {
                                ?>
                                <span class="mif-checkmark"></span> <span class="caption text-small fg-darkGreen"><?= $sub->Descripcion ?> / <?= $sub->Clave ?> </span><br>
                            <?php } ?>
                        <?php endforeach; ?>

                    <?php endforeach; ?>
                </ol>
            </td>
            <td class="center">
                <?php if ($dev->VerificadaSupervisor == "No") { ?>
                    <div class="input-control text big-input medium-size" id="botonguardar<?= $dev->IdDevoluciones ?>">
                        <button class="button primary" onclick="ProcesarDevolucion(<?= $dev->IdDevoluciones ?>)">Procesar
                        </button>
                    </div>
                    <?php
                } else {
                    echo "Verificada";
                }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    function ProcesarDevolucion(id)
    {
        $.get("ProcesarDevolucion", {"dev_id": id}, function (data) {
            $("#botonguardar" + id).html("<center>Verificada</center>");
            $(".inputs" + id).attr("disabled", "disabled");
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