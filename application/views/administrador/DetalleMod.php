<h1><b> Detalle Productos, modelos y colores</b></h1><br>
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
                        <th>Colores</th>
                        <th>Imagén</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colores->result() as $col): ?>
                        <tr>
                            <td class="bordered">
                                <?= $col->Nombre ?>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/colores/<?= $col->Descripcion ?>" height="100px;" width="100px;">
                                <form enctype="multipart/form-data" action="uploader.php" method="POST">
                                <input name="uploadedfile" type="file" />
                                <input type="submit" value="Subir archivo" />
                                </form>
                            </td> 
                            <td class="center">
                                <a class="button block-shadow-info text-shadow alert">Desactivar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</center>


