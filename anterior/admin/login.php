<?php
    session_start();
    require_once "iniSis.php";
   $conn = @mysql_connect(HOST, USER, PASS) or die ('Erro ao conectar ao servidor!'.mysql_error());
   $dbsa = @mysql_select_db(DBSA) or die ('Erro ao conectar com o banco de dados!'.mysql_error());
   	
	
//está logado
    if (isset($_SESSION['userLog'])){
        header("Location: painel.php");
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>index</title>
<link href="css.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF">
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
			        header("Location: painel.php");
	            else
				 echo 'Desculpa, ocorreu um erro...';
				 
	    
		   }
	  }
	  echo '<hr size="1" color="#dfdfdf">';
  
  }
	 ?>
<div id="login_div">
<br><br><br><br><br><br>

  <form id="form1" name="form1" method="POST" action="">
    <div id="login_div2">
         <center> <p><img src="../img/logo.png" width="150px" height="70px"> <br>
         Painel de acesso</p></center>
       <div id="login_int2"><span style="font-family: Verdana, Geneva, sans-serif; font-size: 12px;">Login:</span>
         <input type="text" name="login" value="<?php echo $login;?>"  />
    </div>
<br><br><br>
      <div id="login_int2"><span style="font-family: Verdana, Geneva, sans-serif; font-size: 12px;">Senha:</span>
        <input type="password" name="senha" id="senha" value="<?php echo $senha;?>"/>
      </div>
      <br><br><br>
      <div id="login_int2">
        <input type="checkbox"  name="lembrar" <?php echo $lembrar;?> > Lembrar-me<br/><br/>
        <input type="submit" name="logar" id="logar" value="Entrar" />
		
      </div>
	  
    </div> 
<a href="../index.html"><center>Voltar para pagina inicial</center></a>	
  </form>
</form>
</div>

<div id="rodape_login"> Todos os Direitos reservados- Feito por Murilo Fiuza</div>
</body>
