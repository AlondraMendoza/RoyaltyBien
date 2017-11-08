
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Detalle de carros</span>
        </div>
        <div class="content" id="Resultados">
            <table class="table">
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br>
                        Accesorios
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha Quemado:</b><br>
                        <?php
                        foreach ($lista->result() as $prod) {
                            $date = date_create($prod->FechaQuemado);
                        ?>
                        <?= date_format($date,'d-M-Y'); ?>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</center>

