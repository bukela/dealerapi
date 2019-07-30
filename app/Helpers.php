<?php

function getBetween($content,$start,$end) {

    $r = explode($start, $content);
 
    if (isset($r[1])){
 
        $r = explode($end, $r[1]);
 
        return $r[0];
 
    }
 
    return '';
 
 }

 function encodeFile64($file_name) {

    $encoded_file = chunk_split(base64_encode(file_get_contents(storage_path('documents/'.$file_name))));
    return $encoded_file;
    // dd($pdf64);
 }