<script>
function AbrirT(codigo){
    
    $.post("PeticionAbrirTarima", {"idtarima": codigo}, function (data) {
        if (data == "Correcto")
        {
            $.Notify({
            caption: 'Bien',
            content: 'Se esta procesando',
            type: 'success'
            });
        }else {
            $.Notify({
            caption: 'Error',
            content: 'Ocurrió un error con la tarima',
            type: 'alert'
            });
            }
     });
     $("#Abrir").fadeOut();
     location.reload();
}

function AbrirTarima(codigo){
    $.post("AbrirTarima", {"idtarima": codigo}, function (data) {
        if (data == "Correcto")
        {
            $.Notify({
            caption: 'Bien',
            content: 'Se esta procesando',
            type: 'success'
            });
        }else {
            $.Notify({
            caption: 'Error',
            content: 'Ocurrió un error con la tarima',
            type: 'alert'
            });
            }
     });
     $("#Abrir2").fadeOut();
     location.reload();
}
</script>
<h1><b> EXPEDIENTE TARIMA</b></h1><br>
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
                        <img src="<?= base_url() ?>public/imagenes/SinImagen.png" height="190px;" width="190px;" title="Tarima">        
                        <br><br>
                        <img src="barcodevista?text=<?= $codigo ?>"><br>
                        <?= $codigo; ?>
                    </td> 

                </tr>
                <tr>
                    <td><?php $ci = &get_instance();
                          $ci->load->model("modeloalmacenista");
                          $destruida = $ci->modeloalmacenista->TarimasDestruidas($tarima);?>
                        <b>Productos:</b><br>
                        <hr><?php foreach ($producto->result() as $p): ?>
                        <?= "Clave-".$p->clave."  ".$p->Nombre."/".$p->modelo."/".$p->color; ?>
                        <span class="<?= $p->Color1; ?> cycle-button" style="font-size: 1.5rem;width: 40px;height: 40px "><?= $p->Letra ?></span><br>
                        <?php endforeach; ?>
                        <hr> 
                        <b>Ubicación:</b><br>
                        <hr><?= $ubicacion ?>
                        <hr><b>Acción:</b><br>
                        <hr><?php if ($pendiente==null){?>
                        <div class="input-control text big-input medium-size" id="Abrir" ><button class="button danger" onclick="AbrirT(<?=$tarima?>)">Abrir Tarima</button></div>
                        <?php } else if ($pendiente->FechaAutoriza==null) {
                        echo 'Esta pendiente la autorización';}else if ($destruida->FechaApertura==null){ ?>
                         <div class="input-control text big-input medium-size" id="Abrir2" ><button class="button success" onclick="AbrirTarima(<?=$tarima?>)">Abrir</button></div>
                         <b>Nota: </b>Al abrir la tarima los productos tendrán que salir del almacén como producto solo
                            <?php }else{  echo 'Tarima Abierta';}?>
                        <hr>
                    </td>
                    
                </tr>
                <br><br>
            </table>
        </div>
    </div>
    <br>
    <br>
    <div>
        <center>
            <div class="panel primary" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkBlue"></span>
                    <span class="title">Detalle de movimientos</span>
                </div>
                <div class="content" id="">
                    <br>
                    <div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
                        <ul class="tabs">
                            <li class="active"><a href="#historial">Historial</a></li>
                        </ul>
                        <div class="frames">
                            <div class="frame" id="historial">
                                <div class="content">
                                    <table class="table">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Movimiento</th>
                                            <th>Usuario</th>
                                        </tr>
                                        <?php foreach ($historiales->result() as $historial): ?>
                                            <tr>
                                                <td class="center"><?= $historial->Fecha ?></td>
                                                <td class="center"><?= $historial->Movimiento ?></td>
                                                <td class="center"><?= $historial->Persona ?></td>
                                            </tr>    
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </center>
    </div>
</center><br><br><br>


