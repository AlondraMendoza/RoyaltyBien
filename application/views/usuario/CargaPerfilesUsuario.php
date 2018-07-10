<table class="table  bordered hovered">
    <thead>
        <tr>
            <th>Perfil</th>
            <th>Fecha Inicio</th>
        </tr>
    </thead>
    <?php foreach ($perfiles->result() as $perfil): ?>
        <tr>
            <td><?= $perfil->Nombre ?></td>
            <td class="center"><?= $perfil->FechaInicio ?></td>
        </tr>    
    <?php endforeach; ?>
</table>