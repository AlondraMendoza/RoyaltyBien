<div class="panel success" data-role="panel" id="paso1">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkGreen"></span>
        <span class="title">Clasificaciones encontradas</span>
    </div>
    <div class="content">
        <table class="table datatable hovered" data-role="datatable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Usuario</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clasificaciones->result() as $p): ?>
                    <tr>
                        <td><?= $p->Nombre ?></td>
                        <td><?= $p->APaterno ?></td>
                        <td><?= $p->NombreUsuario ?></td>
                        <td><?= $p->cuantos ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>