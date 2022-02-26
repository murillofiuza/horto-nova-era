             <?php 
session_start();
require '../dts/iniSis.php';
$conn = mysqli_connect(HOST, USER, PASS)	or die ('Erro ao Conectar o Servidor'.mysql_error());
$dbsa = mysqli_select_db($conn, DBSA) or die ('Erro ao Conectar o Banco de Dados'.mysql_error());

if(isset($_SESSION['userLog']))
     header("Location: admin.php");
  else
     header("Location: login.php");
  
?>