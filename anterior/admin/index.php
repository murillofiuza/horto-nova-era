             <?php 
session_start();
require 'iniSis.php';
$conn = mysql_connect(HOST, USER, PASS)	or die ('Erro ao Conectar o Servidor'.mysql_error());
$dbsa = mysql_select_db(DBSA) or die ('Erro ao Conectar o Banco de Dados'.mysql_error());

if(isset($_SESSION['userLog']))
     header("Location: painel.php");
  else
     header("Location: login.php");
  
?>