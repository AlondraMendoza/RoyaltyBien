<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
    function Iniciar() {
        var usuario = $("#user_login").val();
        var contraseña = $("#user_password").val();
        if (usuario != null && contraseña != null) {
            alert("bien");
        } else {
            alert("Escribe usuario y contraseña");
        }
    }
</script>

<div class="login-form padding20 block-shadow" style="opacity: 1; transform: scale(1); transition: 0.5s;">
    <form>
        <h1 class="text-light"><b>Royalty Ceramic</b></h1>
        <hr class="thin"/>
        <br />
        <div class="input-control text full-size" data-role="input">
            <label for="user_login"><b>Usuario:</b></label>
            <input type="text" name="user_login" id="user_login" style="padding-right: 36px;">
            <button class="button helper-button clear"><span class="mif-cross"></span></button>
        </div>
        <br />
        <br />
        <div class="input-control password full-size" data-role="input">
            <label for="user_password"><b>Contraseña:</b></label>
            <input type="password" name="user_password" id="user_password" style="padding-right: 36px;">
            <button class="button helper-button reveal"><span class="mif-looks"></span></button>
        </div>
        <br />
        <br />
        <div class="form-actions">
            <button class="button success" onclick="Iniciar()">Iniciar Sesión</button>
            <button class="button link">Cancelar</button>
        </div>
    </form>
</div>

