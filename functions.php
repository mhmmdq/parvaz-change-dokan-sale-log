<?php

function view( $path , $datas = '') {
    $datas;
    $path = VIEW . str_replace('.' , '/' , $path) . '.php';
    include_once $path;
}
