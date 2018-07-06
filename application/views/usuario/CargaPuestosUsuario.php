<table class="table  bordered hovered">
    <thead>
        <tr>
            <th>Puesto</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Área</th>
            <th>Clave</th>
            <th>Acción</th>
        </tr>
    </thead>
    <?php foreach ($puestos->result() as $puesto): ?>
        <tr>
            <td><?= $puesto->Nombre ?></td>
            <td class="center"><?= $puesto->FechaInicio ?></td>
            <td class="center"><?= $puesto->FechaFin ?></td>
            <td class="center"><?= $puesto->Area ?></td>
            <td class="center"><?= $puesto->Clave ?></td>
            <td class="center">
                <?php
                if ($puesto->Activo == 1) {
                    ?>
                    <span style="color:green">Activo</span>
                    <?php
                } else {
                    echo "<span style='color:red'>Finalizado";
                }
                ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>