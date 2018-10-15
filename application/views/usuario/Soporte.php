<script>
    $(document).ready(function () {
    });

    function CargaPerfiles()
    {
        $("#PerfilesUsuario").load("CargaPerfilesUsuario", {"usuario": "<?= $usuario->IdUsuarios ?>"});
    }

</script>
<h1><b> SOPORTE </b></h1><br>
<center>
    <div class="panel info" data-role="panel" style="overflow: hidden">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Enviar nueva solicitud de soporte</span>
        </div>
        <div class="content" >
            <form action="GuardarSoporte" method="post">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mensaje para soporte</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>
                            <div class="input-control textarea full-size">
                                <textarea name="mensaje"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="center"><button class="button primary">Enviar mensaje</button></td>
                    </tr>
                </table>


            </form>

        </div>
    </div>
    <br>
    <div class="panel warning" data-role="panel" style="overflow: hidden">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Lista de soportes registrados</span>
        </div>
        <div class="content">
            <table class="table hovered" data-role="datatable">
                <thead>
                    <tr>
                        <th>Reporte</th>
                        <th>Creó</th>
                        <?php if (IdUsuario() > 1) { ?>
                            <th>Respuesta</th>
                            <th>Respondió</th>
                        <?php } ?>
                        <th>Fecha</th>
                        <?php if (IdUsuario() == 1) { ?>
                            <th>Respuesta</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($soportes->result() as $s): ?>
                        <tr>
                            <td><?= $s->Mensaje ?></td>
                            <td><?= $s->Nombre1 . " " . $s->Paterno1 ?></td>
                            <?php if (IdUsuario() > 1) { ?>
                                <td>

                                    <?= $s->Respuesta ? $s->Respuesta : 'No se ha respondido su mensaje' ?>

                                </td>
                                <td><?= $s->Nombre2 . " " . $s->Paterno2 ?></td>
                            <?php } ?>
                            <td><?= $s->Fecha ?></td>
                            <?php if (IdUsuario() == 1) { ?>
                                <td>
                                    <form action="GuardarRespuesta" method="post">
                                        <div class="input-control textarea full-size">
                                            <textarea name="respuesta"><?= $s->Respuesta ?></textarea>
                                        </div>
                                        <input type="hidden" value="<?= $s->IdSoportes ?>" name="soporte">
                                        <br>
                                        <center>
                                            <button class="button primary" onclick="Responder(<?= $s->IdSoportes ?>)">Responder</button>
                                        </center>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</center>