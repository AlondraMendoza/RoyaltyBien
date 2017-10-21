<b>Defecto</b><br>
<div id="" class="input-control select full-size" style="height: 80px;">
    <select id="defectos<?php echo $datos["idprod"]; ?>">
        <?php
        $defectos = $datos["defectos"];
        while ($defecto = mysqli_fetch_assoc($defectos)) {
            ?>
            <option value="<?php echo $defecto["IdDefectos"]; ?>"><?php echo $defecto["Nombre"]; ?></option>
            <?php
        }
        ?>
    </select>
</div>