<?php
	session_start();
	//PUXA CONEXÃO COM O SERVIDOR
	require_once "iniSis.php";
	//CONEXÃO COM O SERVIDOR E BANCO DE DADOS
		$conn = @mysql_connect(HOST, USER, PASS) or die ('Erro ao conectar ao servidor!'.mysql_error());
		$dbsa = @mysql_select_db(DBSA) or die ('Erro ao conectar com o banco de dados!'.mysql_error());
   
   //INICIA SESSÃO
	if(!isset($_SESSION['userLog'])){
		header("Location: login.php");
		die();
	}
   
    $login = base64_decode($_SESSION['userInfo']['login']);
	$senha = base64_decode($_SESSION['userInfo']['senha']);
	
	$query = mysql_query("SELECT * FROM usuario WHERE login = '$login' AND senha = '$senha'") or die(mysql_error());
	//verifica se o usuario existe
	if(mysql_num_rows($query) <=0){
		unset($_SESSION['userLog'], $_SESSION['userLog']);
		session_destroy();
       header("Location: login.php");
	   die();
   }
   $infoUser = mysql_fetch_assoc($query);
   
   //RETORNA PARA A PAGINA LIGIN.PHP
	if(isset($_GET['acao']) && $_GET['acao'] == 'sair'){
		unset($_SESSION['userLog'], $_SESSION['userLog']);
		session_destroy();
		header("Location: login.php");
		die();
 }
  ?>
<html>
 <head>
 
 
 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Seja bem, vindo, <?php echo strtoupper($infoUser['nome']); ?></title>
	<link href="css.css" rel="stylesheet" type="text/css">
</head>

<div id="tp">
		<div class="tp_logo"><img src="../img/logo.png" width="200px" height="90px"/></div>
	</div>

	<div class="tp_menu_msg">OLÁ, 
			<?php 
			echo strtoupper($infoUser['login']); 
			echo "!";
			?> 
	</div>

	<div class="tp_menu">
			<a href="?Pagina=Menu_painel"><div class="tp_menu_icon"><img src="img/home_icon1.png"/></div></a>
			<a href="#"><div class="tp_menu_icon"><img src="img/help_icon.png"/></div></a>
			<a href="?acao=sair"><div class="tp_menu_icon"><img src="img/sair_icon.png"/> </div></a>
	</div> 

	<center>	
<table width="100%">
	<tr>
		<td>
			<div id="conteudo">
			<?php /*
			//ABRE PAGINA NA DIV DO PAINEL.PHP
			$Pagina = @$_GET['Pagina'];
				if ($Pagina == '') {
					include "menu_painel.php";
				}else if ($Pagina == 'Cadastro') {
					include "cadastroUsuario.php";
				}else if ($Pagina == 'Lista') {
					include "busca.php";
				}else if ($Pagina == 'Menu_painel') {
					include "menu_painel.php";
				}
			// ADICIONE OUTROS ELSE IF COM AS OUTRAS PAGINAS

			
			//MOSTRA HORA ATUAL			
						echo "<body onLoad='_hora();'> <div id='hora'></div></center>";
					
								
			*/?>
            <div id="conteudo_mp">
  <div class="conteudo_int"><a href="#">
     <div class="conteudo_icon"><img src="img/user_icon.png"/></div>
     <div class="conteudo_link">Perfil de usuário</div> 
     <div class="conteudo_desc">Cadastro e gerenciamento de usuarios.</div> </a>
  </div>
  <div class="conteudo_int"><a href="busca.php">
     <div class="conteudo_icon"><img src="img/user_icon.jpg"/></div>
     <div class="conteudo_link">Lista de Orçamentos</div>
     <div class="conteudo_desc">Gerencie orçamentos solicitados por cliente pelo site horto nova era.</div> </a>
  </div>
  <!--div class="conteudo_int"><a href="?Pagina=Venda">
     <div class="conteudo_icon"><img src="img/conf_icon.jpg"/></div>
     <div class="conteudo_link">Vendas</div> 
      <div class="conteudo_desc">Gerenciar e Realizar Vendas</div> </a>
  </div-->
  <!--div class="conteudo_int"><a href="?Pagina=Cadastro_pecas">
     <div class="conteudo_icon"><img src="img/post_icon.png"/></div>
     <div class="conteudo_link">Adicionar artigos </div> 
      <div class="conteudo_desc">Postar Artigos ou algo interessante no site.</div> </a>
  </div-->   

  <!--div class="conteudo_int"><a href="#">
     <div class="conteudo_icon"><img src="img/cont_icon.jpg"/></div>
     <div class="conteudo_link">Contato</div>
      <div class="conteudo_desc">Enviar contato para o Suporte.</div> </a>
  </div-->
      
  <!--div class="conteudo_int"><a href="?Pagina=Cadastro">
     <div class="conteudo_icon"><img src="img/sup_icon.png"/></div>
     <div class="conteudo_link">Cadastrar Imagens no Portifólio</div>
      <div class="conteudo_desc">Fazer Cadastro Imagens na pagina de portifolio</div></a>
  </div-->
  
  <!--div class="conteudo_int"><a href="#">
     <div class="conteudo_icon"><img src="img/conf_icon.jpg"/></div>
     <div class="conteudo_link">Historica de Vendas</div>
      <div class="conteudo_desc">Histoico de vendas feita no dia</div> </a>
  </div-->
  <!--div class="conteudo_int"><a href="#">
     <div class="conteudo_icon"><img src="img/conf_icon.jpg"/></div>
     <div class="conteudo_link">Total de Vendas</div>
      <div class="conteudo_desc">Gerenciar Atividade</div> </a>
  </div>
</div-->

			</div>
		<td>
	<tr>
</table>
<center>

<br/>
<div id="rodape">
  <p>Todos os direitos reservados, Layout by Murilo Fiuza.</p>
  <p>Nova Era<br>
  .::Horto & Paisagismo::.</p>
</div>
</body>
</html>