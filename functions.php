<?php

function view( $path , $datas = '') {
    $datas;
    $path = VIEW . str_replace('.' , '/' , $path) . '.php';
    include_once $path;
}

function prvazccd_load_reports( $items = 10 ) {
    global $_reports;
    return $_reports->paginate_render( $items );
}

function prvazccd_get_links() {
    global $_reports;
    return $_reports->render_links();
}