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
                        <b>Clasificación</b>
                        <br>
                        <?php if ($clasificacion != null): ?>
                            <span class="<?= $clasificacion->Color; ?> cycle-button" style="font-size: 1.5rem;width: 40px;height: 40px "><?= $clasificacion->Letra ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <b>Ubicación</b>
                        <br>
                        <?= $ubicacion ?><br><br>
                    </td>
                    <td class="center">
                        <b>Fuera de Tono</b>
                        <br>
                        <?php if ($clasificacion != null): ?>
                            <?php
                            if ($clasificacion->FueraTono == 1) {
                                echo "Si";
                            } else {
                                echo "No";
                            }
                            ?>
                        <?php endif; ?>
                        <br><br>
                    </td>
                    <td class="center">
                        <b>Tarima</b>
                        <br>
                        <?= $tarima ?><br>
                        <?php
                        $ci = &get_instance();
                        $ci->load->model("modeloclasificador");
                        if ($tarimaid > 0) {
                            $codigotarima = $ci->modeloclasificador->CodigoBarrasTarimaTexto($tarimaid);
                            ?>
                            <img src="barcodevista?text=<?= $codigotarima ?>"><br>
                            <?= $codigotarima; ?>
                            <?php
                        }
                        ?><br>
                    </td>
                    <td class="center">
                        <b>Pedido</b>
                        <br>
                        <?= $pedido; ?>
                        <br><br>
                    </td>
                    <td>
                        <b>Defectos</b><br>
                        <?php
                        if ($defectos != null && $defectos->num_rows() > 0) {
                            foreach ($defectos->result() as $defecto):
                                ?>
                                <?= $defecto->Nombre ?><br>
                                <?php
                            endforeach;
                        }
                        else {
                            echo "Sin defectos <br>Registrados";
                        }
                        ?>
                        <br>
                    </td>
                </tr>
            </table>
        </div>
    </div>
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
                    <div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs' data-open-target="#historial">
                        <ul class="tabs">
                            <li><a href="#historial">Historial</a></li>
                            <li><a href="#clasificaciones">Clasificaciones</a></li>
                            <li><a href="#entarimado">Entarimado</a></li>
                            <li><a href="#reparaciones">Reparaciones</a></li>
                            <li><a href="#defectos">Defectos</a></li>
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
                            <div class="frame" id="clasificaciones">
                                <table class="table">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Letra</th>
                                        <th>Usuario</th>
                                    </tr>
                                    <?php foreach ($clasificaciones->result() as $clasificacion): ?>
                                        <tr>
                                            <td class="center"><?= $clasificacion->FechaClasificacion ?></td>
                                            <td class="center"><?= $clasificacion->Letra ?></td>
                                            <td class="center"><?= $clasificacion->Persona ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <div class="frame" id="entarimado">
                                <table class="table">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tarima</th>
                                        <th>Usuario</th>
                                    </tr>
                                    <?php foreach ($entarimados->result() as $entarimado): ?>
                                        <tr>
                                            <?php
                                            $ci = &get_instance();
                                            $ci->load->model("modeloclasificador");
                                            $codigotarima = $ci->modeloclasificador->CodigoBarrasTarimaTexto($entarimado->IdTarimas);
                                            ?>
                                            <td class="center"><?= $entarimado->FechaCaptura ?></td>
                                            <td class="center"><?= $codigotarima ?></td>
                                            <td class="center"><?= $entarimado->Persona ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <div class="frame" id="reparaciones">
                                <table class="table">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Diagnóstico</th>
                                        <th>Solución</th>
                                        <th>Usuario</th>
                                    </tr>
                                    <?php foreach ($reparacion->result() as $r): ?>
                                        <tr>
                                            <td class="center"><?= $r->Fecha ?></td>
                                            <td class="center"><?= $r->Diagnostico ?></td>
                                            <td class="center"><?php
                                                if ($r->Solucion == 1) {
                                                    echo 'Si';
                                                } else {
                                                    echo 'No';
                                                }
                                                ?></td>
                                            <td class="center"><?= $r->Persona ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <div class="frame" id="defectos">
                                <table class="table">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Defecto</th>
                                        <th>Área</th>
                                        <th>Responsable</th>
                                    </tr>
                                    <?php if($detdefectos!=null){ ?>
                                    <?php foreach ($detdefectos->result() as $dd): ?>
                                        <tr>
                                            <td class="center"><?= $dd->Fecha ?></td>
                                            <td class="center"><?= $dd->Defecto ?></td>
                                            <td class="center"><?= $dd->Area ?></td>
                                            <td class="center"><?= $dd->Persona ?></td>
                                        </tr>
                                    <?php endforeach; ?> <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </center>
    </div>
</center><br><br><br>


