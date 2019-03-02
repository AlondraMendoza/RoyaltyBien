<script>
   function AbrirT(codigo){
       $.post("AceptarAbrirTarima", {"idtarima": codigo}, function (data) {
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
     location.reload();
   } 
</script>
<h1 class="light text-shadow">Solicitudes para abrir tarimas</h1><br>

<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">
        <li class="active"><a href="#tarimaspendientes" >Tarimas por Abrir</a></li>
        <li class="active"><a href="#tarimasabiertas" >Tarimas abiertas</a></li>
    </ul>
    <div class="frames">
        <div class="frame" id="tarimaspendientes">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de tarimas por abrir</span>
                </div>
                <div class="content" id="listatarimasporabrir" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablatarimasporabrir" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Tarima</th>
                                <th>Fecha de solicitud</th>
                                <th>Usuario que solicita</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <?php foreach ($tarimas->result() as $tpa): ?>
                            <tr>
                                <td class="center">
                                    <?php 
                                    $cic = &get_instance();
                                        $cic->load->model("modeloalmacenista");
                                        $codigo = $cic->modeloalmacenista->CodigoBarrasTarimaTexto($tpa->TarimasId);?>
                                    <img src="barcodevista?text=<?= $codigo ?>"><br>
                                        <?= $codigo;
                                    ?>
                                </td>
                                <td><?= $tpa->FechaSolicita ?></td>
                                <td><?php
                                        $ci = &get_instance();
                                        $ci->load->model("modeloventas");
                                        $usuario = $ci->modeloventas->Usuario($tpa->UsuarioSolicita);
                                        ?>
                                        <?= $usuario->Nombre . " " . $usuario->APaterno ?>
                                </td>
                                <td class="center">
                                    <div class="input-control text big-input medium-size" id="Abrir" ><button class="button danger" onclick="AbrirT(<?=$tpa->TarimasId?>)">Abrir Tarima</button></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="frame" id="tarimasabiertas">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de tarimas abiertas</span>
                </div>
                <div class="content" id="listatarimasabiertas" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablatarimasabiertas" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Tarima</th>
                                <th>Fecha de solicitud</th>
                                <th>Usuario que solicita</th>
                                <th>Fecha de autorización</th>
                                <th>Usuario que autoriza</th>
                                <th>Fecha de apertura de tarima</th>
                            </tr>
                        </thead>
                        <?php foreach ($tarimasabiertas->result() as $ta): ?>
                            <tr>
                                <td class="center">
                                    <?php 
                                    $cic = &get_instance();
                                        $cic->load->model("modeloalmacenista");
                                        $codigo = $cic->modeloalmacenista->CodigoBarrasTarimaTexto($ta->TarimasId);?>
                                    <img src="barcodevista?text=<?= $codigo ?>"><br>
                                        <?= $codigo;
                                    ?>
                                </td>
                                <td><?= $ta->FechaSolicita ?></td>
                                <td><?php
                                        $ci = &get_instance();
                                        $ci->load->model("modeloventas");
                                        $usuario1 = $ci->modeloventas->Usuario($ta->UsuarioSolicita);
                                        ?>
                                        <?= $usuario1->Nombre . " " . $usuario1->APaterno ?></td>
                                <td><?= $ta->FechaAutoriza ?></td>
                                <td><?php
                                    $usuario2 = $ci->modeloventas->Usuario($ta->UsuarioAutoriza);
                                    echo $usuario2->Nombre . " " . $usuario2->APaterno ?></td>
                                <td>
                                    <?php
                                    $ci3 = &get_instance();
                                    $ci3->load->model("modeloadministrador");
                                    $tarimafecha = $ci3->modeloadministrador->DetalleTarimas($ta->TarimasId);
                                    if($tarimafecha->FechaApertura==null){
                                    echo 'La tarima no esta abierta';}else{
                                        echo $tarimafecha->FechaApertura;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>

