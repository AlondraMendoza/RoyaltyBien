<script>
    function Continuar(){
         var carro=document.getElementById("ECarro").innerHTML;
         var horno=document.getElementById("ENHorno").innerHTML;
         var producto=document.getElementById("EProducto").innerHTML;
         var modelo=document.getElementById("EModelo").innerHTML; 
         var color=document.getElementById("EColor").innerHTML; 
         var cantidad=document.getElementById("ECantidad").innerHTML; 
         var fecha=document.getElementById("EFecha").innerHTML;
         var input='<tr><td>';
                input+='<b style="font-size: 1.3em" class="fg-darkEmerald">Carro:</b><br>';
                input+=carro;
                input+='</td>';
                input+='<td><b style="font-size: 1.3em" class="fg-darkEmerald">Horno:</b><br>';
                input+=horno;
                input+='</td>';
                input+='<td><b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br>';
                input+=producto;
                input+='</td>';
                input+='<td><b style="font-size: 1.3em" class="fg-darkEmerald">Modelo:</b><br>';
                input+=modelo;
                input+='</td>';
                input+='<td><b style="font-size: 1.3em" class="fg-darkEmerald">Color:</b><br>';
                input+=color;
                input+='</td>';
                input+='<td><b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad:</b><br>';
                input+=cantidad;
                input+='</td>';
                input+='<td><b style="font-size: 1.3em" class="fg-darkEmerald">Fecha:</b><br>';
                input+=fecha;
                input+='</td>';
                input+='</tr>';
                $("#ListaTabla").append(input);
                $('#Lista').show();
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
                <tr id='Result'>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Carro:</b><br>
                        <label id='ECarro'><?= $prod->carro ?></label>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Horno:</b><br>
                        <label id='ENHorno'><?= $prod->NHorno ?></label>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br>
                        <label id='EProducto'><?= $prod->producto ?></label>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Modelo:</b><br>
                        <label id='EModelo'><?= $prod->modelo ?></label>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Color:</b><br>
                        <label id='EColor'><?= $prod->color ?></label>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad:</b><br>
                        <label id='ECantidad'><?= count($lista) ?></label>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha Quemado:</b><br>
                        <label id='EFecha'><?php
                        $date = date_create($prod->FechaQuemado);
                        ?>
                        <?= date_format($date,'d-m-Y'); ?></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td class="center">
                        <div class="input-control text big-input medium-size">
                            <button class="button success" onclick="Continuar()">Continuar</button></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="panel warning" data-role="panel" style="display: none;" id="Lista">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Detalle de capturas</span>
        </div>
        <div class="content" id="Lista">
            <table class="table" id="ListaTabla">
                
            </table>
        </div>
    </div>

</center>

