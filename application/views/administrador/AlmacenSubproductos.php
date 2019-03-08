<?php
$ci = &get_instance();
$ci->load->model("modeloalmacenista");
?>
<h1><b> ALMACÉN DE SUBPRODUCTOS</b></h1><br>

        <div class="frame" id="concentrado">
            <table class="table bordered border hovered" id="tablaproductos" data-role="datatable">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <?php foreach ($subproductosunicos->result() as $su): ?>
                    <?php $cantidad = $ci->modeloalmacenista->ExistenciasSubproductos($su->IdCGriferia); ?>
                    <tr>
                        <td><?= $su->Clave ?></td>
                        <td><?= $su->Descripcion ?></td>
                        <td><?= $cantidad ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
       