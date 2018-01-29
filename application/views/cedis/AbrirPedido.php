<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Lista de productos en pedido</span>
    </div>
    <div class="content" id="" style="padding: 15px">
        <div class="input-control text big-input medium-size" id="">
            <button class="button primary" onclick="Cancelar()">Regresar</button>
        </div>
        <br>
        <table class = " table dataTable border bordered hovered hover" id = "tablalistaproductos" data-role = "datatable">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Modelo</th>
                    <th>Color</th>
                </tr>
            </thead>
            <?php foreach ($ListaProductos->result() as $producto):
                ?>
                <tr>
                    <td><?= $producto->IdProductos ?></td>
                    <td><?= $producto->modelo ?></td>
                    <td><?= $producto->color ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br><br>
    </div>