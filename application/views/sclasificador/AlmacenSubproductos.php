<?php
$ci = &get_instance();
$ci->load->model("modeloalmacenista");
?>
<h1><b> ALMACÉN DE SUBPRODUCTOS</b></h1><br>
<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">
        <li class="active"><a href="#concentrado">Concentrado</a></li>
        <li><a href="#detalle">Detalle</a></li>
    </ul>
    <div class="frames">
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
        <div class="frame" id="detalle">
            <table class="table bordered border hovered" id="tablaproductos" data-role="datatable">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Capturista</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <?php foreach ($subproductosdetalle->result() as $sd): ?>
                    <?php $tipo = $ci->modeloalmacenista->Tipo($sd->IdAlmacenSubproductos); ?>
                    <?php $fecha = $ci->modeloalmacenista->Fecha($sd->IdAlmacenSubproductos); ?>
                    <?php $nombre = $ci->modeloalmacenista->NombreUsuario($sd->UsuariosId); ?>
                    <tr>
                        <td><?= $sd->Clave ?></td>
                        <td><?= $sd->Descripcion ?></td>
                        <td><?= $sd->Cantidad ?></td>
                        <td><?= $nombre->Nombre ?> <?= $nombre->APaterno ?></td>
                        <td><?= $fecha ?></td>
                        <td><?= $tipo ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>