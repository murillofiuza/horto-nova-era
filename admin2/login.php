<?php
    session_start();
    require_once "../dts/iniSis.php";
   $conn = @mysql_connect(HOST, USER, PASS) or die ('Erro ao conectar ao servidor!'.mysql_error());
   $dbsa = @mysql_select_db(DBSA) or die ('Erro ao conectar com o banco de dados!'.mysql_error());
   	
	
//está logado
    if (isset($_SESSION['userLog'])){
        header("Location: admin.php");
        die();
    }
	if(isset($_COOKIE['lembrar'])){
		
		$lembrar ="checked";
		$login = base64_decode($_COOKIE['lembrar-login']);
		$senha = base64_decode($_COOKIE['lembrar-senha']);
	}else{
		$lembrar = null;
		$login = null;
		$senha = null;
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Admin-Horto novaera</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-dark">
<?php
  if(isset($_POST['logar'])){
	   $login = mysql_real_escape_string(strip_tags(trim($_POST['login'])));
	  $senha = mysql_real_escape_string(strip_tags(trim($_POST['senha'])));
	   $lembrar = (isset($_POST['lembrar']))? true : false;
	  
	  if(empty($login) && empty($senha))
	    echo "<script>alert ('Informe seu Login e senha!')</script>";
	  else if(empty($login))
	    echo "<script>alert ('Informe seu Login!')</script>";
	  else if(empty($senha))
	    echo "<script>alert ('Informe seu Senha!')</script>";
	  else{
		  //verifica login
		  $query = mysql_query("SELECT login FROM usuario WHERE login = '$login'") or die (mysql_error());
		   $checkLogin = mysql_num_rows($query);
		   
		   //verifica senha
		   $query = mysql_query("SELECT * FROM usuario WHERE login = '$login' AND senha ='$senha' LIMIT 1") or die (mysql_error());
		   $checkPass = mysql_num_rows($query);
		   
		   if($checkLogin <=0)
		     echo "<script>alert ('Este usuario não existe!')</script>";
			 
		   else if ($checkPass <=0)	 
               echo "<script>alert ('Senha incorreta')</script>";	  
	       else{
			   $infoUser = mysql_fetch_assoc($query);
			   
			   $_SESSION['userLog'] = true; 
			   $_SESSION['userInfo'] = array(
					'nome' => base64_encode($infoUser['nome']),
					'login' => base64_encode($infoUser['login']),
					'senha' => base64_encode($infoUser['senha'])
					);
			   if($lembrar){
				   setcookie('lembrar', true, time()+3600*24*30, '/');
				   setcookie('lembrar-login', base64_encode($login), time()+3600*24*30, '/');
				   setcookie('lembrar-senha', base64_encode($senha), time()+3600*24*30, '/');
			   }else{
				   setcookie('lembrar','', time() -3600*24*30, '/');
				   setcookie('lembrar-login', '', time() -3600*24*30, '/');
				   setcookie('lembrar-senha', '', time() -3600*24*30, '/');
			   } 
			   //se exister session ele redireciona para o painel
			   if(isset($_SESSION['userLog']))
			        header("Location: admin.php");
	            else
				 echo 'Desculpa, ocorreu um erro...';
				 
	    
		   }
	  }
	  echo '<hr size="1" color="#dfdfdf">';
  
  }
	 ?>

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header"><img src="../img/logo.png" height="87" width="175"></div>
      <div class="card-body">
        <form method="post" action="">
          <div class="form-group">
            <label for="exampleInputEmail1">Usuário</label>
            <input class="form-control" name="login" id="login" type="text"  placeholder="Entre com usuario">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Senha:</label>
            <input class="form-control" name="senha" id="senha" type="password" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox"> Lembrar senha.</label>
            </div>
          </div>
          
          <input type="submit" class="btn btn-primary btn-block" name="logar" value="Login">
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="#">voltar</a>
          <a class="d-block small" href="#">Esqueci a senha!</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/popper/popper.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
