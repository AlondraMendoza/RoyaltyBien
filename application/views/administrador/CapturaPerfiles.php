<h1><b> Usuarios</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuarios</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios->result() as $usuario): ?>
                        <tr>
                            <td class="bordered">
                                <?= $usuario->NombreCompleto ?>
                            </td>
                            <td class="center">
                                <a class="button block-shadow-info text-shadow primary" href="ExpedienteUsuario?usuario=<?= $usuario->IdUsuarios ?>">Abril Expediente</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</center>