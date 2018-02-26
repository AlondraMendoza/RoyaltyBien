<option value="0">Todos</option>
<?php foreach ($colores->result() as $color): ?>
    <option value="<?= $color->IdColores; ?>"><?= $color->Nombre; ?></option>
<?php endforeach; ?>