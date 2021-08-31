<?php

include_once 'vendor/autoload.php';

function convert2html($file_path, $file_name){

    $objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
    $phpWord = $objReader->load("$file_path/$file_name");

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
    $objWriter->save("$file_path/$file_name.html");

}
?>