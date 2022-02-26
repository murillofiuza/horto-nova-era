<html>
<body>
<br/>
<center>
<form method="POST" action="">
	<label for="consulta">Buscar:</label>
	<input type="text" id="consulta" name="consulta" maxlength="255" />
	<input type="submit" value="Pequisar" name="consultar" />
</form>
</center>
</body>

<?php
include_once "iniSis.php";

	$conn = @mysql_connect(HOST,USER,PASS) or die('Erro ao conectar' .mysql_error());
	$dbsa = @mysql_select_db(DBSA) or die('Erro ao selecionar banco de dados' .mysql_error());
	
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
	
	/*********************************************************************************************************
	$query ="SELECT * FROM Produtos WHERE id_Produtos !='' GROUP BY id_Produtos ORDER BY id_Produtos ASC";
	$exeqr =mysql_query($query) or die (mysql_error());

	if(isset($_POST['consultar'])){
		echo $exeqr;
		}
		
		if (!isset($_GET['consulta'])) {
	  header("Location: busca.php/");
	  exit;
	}***********************************************************************************************************/

	/*******************************************
	 SALVA O QUE FOI BUSCADO EM UMA VARIAVEL
	*********************************************/
	@$busca = mysql_real_escape_string($_POST['consulta']);

	// Registros por página
	$por_pagina = 50;

	// Monta a consulta MySQL para saber quantos registros serão encontrados
	$condicoes = "(`id` = 1 or 3) AND ((`nome` LIKE '%{$busca}%') OR ('%{$busca}%'))";
	$sql = "SELECT COUNT(*) AS nome FROM `orcamento` WHERE {$condicoes}";

	// Executa a consulta
	$query = mysql_query($sql);
	// Salva o valor da coluna 'total', do primeiro registro encontrado pela consulta
	$total = mysql_result($query, 0, 'nome');
	// Calcula o máximo de paginas
	$paginas =  (($total % $por_pagina) > 0) ? (int)($total / $por_pagina) + 1 : ($total / $por_pagina);	
	
	if (isset($_GET['pagina'])) {
	  $pagina = (int)$_GET['pagina'];
	} else {
	  $pagina = 1;
	}
	
	//DELETA REGISTRO DE TABELA
	if(!empty($_GET['del'])){
	$delid = $_GET['del'];
		$sqlDel = "DELETE FROM orcamento WHERE id = {$delid}";
		$exeqrDel = mysql_query($sqlDel) or die (mysql_error());
		if($exeqrDel){
			echo "<script> alert ('Produto removido com sucesso!');
			location.href = 'busca.php';
			</script>";
			echo "";
	}
	}
	$pagina = max(min($paginas, $pagina), 1);
	$offset = ($pagina - 1) * $por_pagina;
	
// ============================================
	// Monta outra consulta MySQL, agora a que fará a busca com paginação
	$sql = "SELECT * FROM `orcamento` WHERE {$condicoes} ORDER BY `nome` DESC LIMIT {$offset}, {$por_pagina}";
	// Executa a consulta
	$query2 = mysql_query($sql);
	
	
	/*******************************************
	 COLUNAS E REGISTROS DA TABELA DE PRODUTOS
	*********************************************/
	echo "<ul>";
	echo "<table  bgcolor='#EAFFFF' border='1' align='center'>";
		//echo"<td width='102'>&nbsp;</td>";
    	//echo "<td colspan='3'>&nbsp;</td>";
		
  	echo "<tr>";
	//COLUNA DA TABELA
		echo "<td width='150' bgcolor='#EFEFEF'><center>Codigo</center></td>";
    	echo "<td width='200' bgcolor='#EFEFEF'><center>Nome</center></td>";
    	echo "<td width='120' bgcolor='#EFEFEF'><center>E-mail</center></td>";
		echo "<td width='200' bgcolor='#EFEFEF'><center>Contato</center></td>";
		echo "<td width='200' bgcolor='#EFEFEF'><center>OBS</center></td>";
		echo "<td width='100' bgcolor='#EFEFEF'><center>Acao</center></td>";
    echo "</tr>";
	// VARIAVEIS QUE CONSUTAM REGISTRO DA TABELA DO BANCO DE DADOS
	while ($resultado = mysql_fetch_assoc($query2)){
		$id = $resultado['id'];
		$nome = $resultado['nome'];
		$email = $resultado['email'];
		$contato = $resultado['contato'];
		$material = $resultado['observacao_solicitado'];
		//$linkDel = "<a href='busca.php?del='['id_Produtos']'>'.$resultado['produto'].</a>";
		//$linkCarinho = '/produtos.php?id=' . $resultado['id_Produtos'];
		//$imgDelete = "<img src='img/excluir.gif' width='20px'>";
		//$imgEdita = "<img src='img/post_icon.png' width='20px'>";
		//$imgCarinho = "<img src='img/help_icon.png' width='20px'>";
	
	//COLUNA ID 
		echo "<tr>";
		echo "<td>"; 
		//echo "<a href='{$link}'>";
		echo "<center>{$id}</center>";
		//echo "</a>";
		echo "</td>";
		
	//COLUNA NOME
		echo "<td>"; 
		//echo "<a href='{$link}'>";
		echo "{$nome}";
		//echo "</a>";
		echo "</td>";	
	
	//COLUNA EMAIL	
		echo "<td>"; 
		//echo "<a href='{$link}'>";
		echo "<center>{$email}</center>";
		//echo "</a>";
		echo "</td>";
		
	//COLUNA CONTATO	
		echo "<td>"; 
		echo "<center>";
		//echo "<a href='{$link}'>";
		echo "<center>{$contato}</center>";
		//echo "</a>";
		echo "</center>";
		echo "</td>";
		
	//COLUNA CATEGORIA	
		echo "<td>"; 
		echo "<center>";
		//echo "<a href='{$link}'>";
		echo "<center>{$material}</center>";
		//echo "</a>";
		echo "</center>";
		echo "</td>";
		
	//COLUNA AÇÕES	
		echo "<td>";
		echo "<center>";
		//echo "<form action='#' method='GET'>";
		//echo "<input type='submit' name='del' value='x' id='del'>";
		//echo "</form>";
		echo "<a href=busca.php?del=$id><img src='../img/excluir.gif' width='20px'></a>";
		echo "       ";
		echo "<a href=orcamentoPdf.php?id=$id target='_blank'><img src='../img/_pdf.gif' width='20px'></a>";;
		echo "       ";
		echo "<a href=orcamentoExcel.php?id=$id><img src='../img/xls.jpg' width='20px'></a>";;
		echo "       ";
		//echo "{$imgCarinho}";
		echo "</center>";
		echo "</td>";

		
		echo "</tr>";
	}
	echo "</table>";
	echo "</ul>";


	// Links de paginação
	// Começa a exibição dos paginadores
	if ($total > 0) {
		for ($n = 1; $n <= $paginas; $n++) {
		}
	}
	// COMEÇA A EXIBIÇÃO DOS RESULTADOS
	echo "<center>";
	echo 'Quantidade de orçamentos '.mysql_num_rows($query2).' resultados. Temos em nossa tabela';
echo mysql_num_fields($query).' colunas';
	echo "</center>";
	
?>
	

