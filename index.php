<?php 
   // Exibe todos os erros
    error_reporting(E_ALL);
    
    require_once('./classes/Application.php');
    
    // Iniciando a aplicação
    $app = new Application('development');
    $app->run();   
    
?>