<script>

</script>
<h1><b> EXPEDIENTE PRODUCTO</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content">
            <table class="table">
                <tr>
                    <td class="center" rowspan="2" style="width: 30%">
                        <img src="<?= base_url() ?>public/imagenes/<?= $producto->foto; ?>" height="190px;" width="190px;" title="<?= $producto->NombreProducto; ?>">        
                        <br><br>
                        <img src="barcodevista?text=<?= $codigo ?>"><br>
                        <?= $codigo; ?>
                    </td> 
                </tr>
                <tr>
                    <td>
                        <b>Producto:</b><br><br><?= $producto->NombreProducto; ?>
                        <hr>
                        <b>Modelo:</b><br><br><?= $producto->Modelo; ?>
                        <hr>
                        <b>Color:</b><br><br><?= $producto->Color; ?>
                        <hr>
                    </td>
                </tr>
                <br><br>
            </table>
        </div>
    </div>
    <br>
    <div class="panel success" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkGreen"></span>
            <span class="title">Estados actuales del producto</span>
        </div>
        <div class="content" id="">
            <table class="table">
                <tr>
                    <td class="center">
                        <b>Defectos</b>
                        <br>
                        <?php foreach ($defecto->result() as $d): ?>
                        <?= $d->Nombre ?><br>
                        <?php endforeach; ?>
                        <br><br>
                    </td>
                    <td>
                        <b>Acciones</b>
                        <br>
                        <a class="button block-shadow-info text-shadow primary" href="CapturaReparacion?producto=<?= $producto->IdProductos ?>">Reparar</a>
                        <a class="button block-shadow-info text-shadow alert" onclick="Desactivar(<?= $producto->IdProductos ?>)">Cancelar</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </center><br><br><br>


