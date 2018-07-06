<?php

function EstaLogueado() {
// Get current CodeIgniter instance
    $CI = &get_instance();
// We need to use $CI->session instead of $this->session
    $user = $CI->session->userdata('logueado');
    if (!isset($user)) {
        return false;
    } else {
        return true;
    }
}

function IdUsuario() {
// Get current CodeIgniter instance
    $CI = &get_instance();
// We need to use $CI->session instead of $this->session
    $user = $CI->session->userdata('id');
    if (!isset($user)) {
        return $user;
    } else {
        return true;
    }
}

function IdPersona() {
// Get current CodeIgniter instance
    $CI = &get_instance();
// We need to use $CI->session instead of $this->session
    $user = $CI->session->userdata('idpersona');
    if (!isset($user)) {
        return $user;
    } else {
        return true;
    }
}

function NombreUsuario() {
// Get current CodeIgniter instance
    $CI = &get_instance();
// We need to use $CI->session instead of $this->session
    $user = $CI->session->userdata('persona');
    if (!isset($user)) {
        return $user;
    } else {
        return true;
    }
}
