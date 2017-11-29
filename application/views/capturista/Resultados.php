<script>
    function Nuevo(){
         location.reload(true);
    }
</script>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Detalle de carros</span>
        </div>
        <div class="content" id="Resultados">
            <table class="table">
                
                <?php $ci = &get_instance();
                $ci->load->model("modelocapturista");
                $prod = $ci->modelocapturista->Buscar($lista[0])->row(); ?>
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Carro:</b><br>
                        <?= $prod->carro ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Horno:</b><br>
                        <?= $prod->NHorno ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br>
                        <?= $prod->producto ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Modelo:</b><br>
                        <?= $prod->modelo ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Color:</b><br>
                        <?= $prod->color ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad:</b><br>
                        <?= count($lista) ?>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha Quemado:</b><br>
                        <?php
                        $date = date_create($prod->FechaQuemado);
                        ?>
                        <?= date_format($date,'d-M-Y'); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td class="center">
                        <div class="input-control text big-input medium-size">
                            <button class="button success" onclick="Nuevo()">Continuar</button></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</center>

