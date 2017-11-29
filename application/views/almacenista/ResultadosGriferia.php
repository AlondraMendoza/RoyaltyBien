<script>
    function Nuevo(){
         location.reload(true);
    }
</script>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Detalle de entrada de Grifería</span>
        </div>
        <div class="content" id="Resultados">
            <table class="table">
                <tr>
                    <?php
                        foreach ($lista->result() as $prod) {
                            $date = date_create($prod->FechaEntrada);
                        ?>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br>
                        <?= $prod->Clave ."-" ?>
                        <?= $prod->Descripcion ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad:</b><br>
                        <?= $prod->Cantidad ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha Entrada:</b><br>
                        <?= date_format($date,'d-M-Y'); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="center"><div class="input-control text big-input medium-size">
                            <button class="button success" onclick="Nuevo()">Continuar</button></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</center>

