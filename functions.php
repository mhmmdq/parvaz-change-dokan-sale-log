<?php

function parvaz_report_view( $path , $data = []) {
    extract($data);
    $path = VIEW . str_replace('.' , '/' , $path) . '.php';
    include_once $path;
}
