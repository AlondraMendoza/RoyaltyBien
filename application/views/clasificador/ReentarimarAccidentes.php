<?php
$ci = &get_instance();
$ci->load->model("modelocedis");
?>
<h1><b> REENTARIMAR ACCIDENTES</b></h1><br>
<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Lista de accidentes sin procesar</span>
    </div>
    <div class="content">
        <table class="table bordered border hovered" id="tablaproductos" data-role="datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>FechaCaptura</th>
                    <th>Motivo</th>
                    <th>Responsable</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Verificar</th>
                </tr>
            </thead>
            <?php foreach ($accidentes->result() as $a): ?>
                <tr>
                    <td><?= $a->IdAccidentes ?></td>
                    <td><?= $a->IdDAccidentes ?></td>
                    <td><?= $a->Motivo ?></td>
                    <td><?= $a->Fecha ?></td>
                    <td><?= $a->TarimasId ?></td>
                    <td>
                        <button class="button primary" onclick="ProcesarDevolucion(<?= $dev->IdDevoluciones ?>)">Procesar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script>

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