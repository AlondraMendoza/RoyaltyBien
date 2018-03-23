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
