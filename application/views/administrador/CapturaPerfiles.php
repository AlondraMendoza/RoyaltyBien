<h1><b> Empleados</b></h1><br>
<center>
    <div class="panel primary" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Nuevo Empleado</span>
        </div>
        <div class="content">
            <form action="GuardarEmpleado">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Número Empleado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="input-control text full-size"><input type="text" name="nombre"></div></td>
                            <td><div class="input-control text full-size"><input type="text" name="apellidop"></div></td>
                            <td><div class="input-control text full-size"><input type="text" name="apellidom"></div></td>
                            <td><div class="input-control text full-size"><input type="text" name="nempleado"></div></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="center">
                                <input type="submit" value="Guardar" class="primary">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Lista de Empleados</span>
        </div>
        <div class="content">
            <table class="table " data-role="datatable">
                <thead>
                    <tr>
                        <th class="center">Nombre</th>
                        <th class="center">Puesto</th>
                        <th class="center">Usuario</th>
                        <th class="center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios->result() as $usuario): ?>
                        <tr>
                            <td class="bordered">
                                <?= strtoupper($usuario->NombreCompleto) ?>
                            </td>
                            <td class="bordered center">
                                <?php
                                $ci = &get_instance();
                                $ci->load->model("modeloadministrador");
                                $puesto = $ci->modeloadministrador->UltimoPuesto($usuario->IdPersonas);
                                $usuario2 = $ci->modeloadministrador->Usuario($usuario->IdPersonas);
                                ?>
                                <?php
                                if (isset($puesto)) {
                                    echo $puesto->Nombre;
                                }
                                ?>
                            </td>
                            <td class="bordered center">
                                <?php if (isset($usuario2)): ?>
                                    <?= $usuario2->Nombre ?>
                                <?php endif; ?>
                            </td>
                            <td class="center">
                                <a class="button block-shadow-info text-shadow primary" href="ExpedienteUsuario?persona=<?= $usuario->IdPersonas ?>">Expediente</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</center>
<br><br><br><br>