<?php 
$con=mysqli_connect("mysql.weblink.com.br","u430987243_hnes","hne2017","u430987243_hnes");

// VERIFICA A LIGAÇÃO NÃO TEM ERROS
if (mysqli_connect_errno())
{
    // CASO TENHA ERROS MOSTRA O ERRO DE LIGAÇÃO À BASE DE DADOS
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>