<?php

function get_isset_value($value, $default) {
    return isset($value) ? $value : $default;
}


function get_not_empty_value($value, $default) {
    return !empty($value) ? $value : $default;
}

function formate_not_empty($value, $func, $default) {
    return !empty($value) ? call_user_func($func, $value) : $default;
}

function isset_do($value, $func) {
    if (isset($value)) {
        call_user_func($func);
    }
}

function not_empty_do($value, $func) {
    if (!empty($value)) {
        call_user_func($func);
    }
}

function translate_to_array($value) {
    return json_decode(json_encode($value), true);
}
