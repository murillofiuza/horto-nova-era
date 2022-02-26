<?php
// definições de host, database, usuário e senha
$con=mysqli_connect("mysql.weblink.com.br","u430987243_hnes","hne2017","u430987243_hnes");
//$con=mysqli_connect("localhost","root","","hne");

// VERIFICA A LIGAÇÃO NÃO TEM ERROS
if (mysqli_connect_error())
{
    // CASO TENHA ERROS MOSTRA O ERRO DE LIGAÇÃO À BASE DE DADOS
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// cria a instrução SQL que vai selecionar os dados
$query = sprintf("SELECT nome, cpf, email,contato, material FROM orcamento");
// executa a query
$dados = mysqli_query($con, $query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$total = mysqli_num_rows($dados);
?>

<html>
	<head>
	<title>Exemplo</title>
</head>
<body>
<?php
	// se o número de resultados for maior que zero, mostra os dados
	if($total > 0) {
		// inicia o loop que vai mostrar todos os dados
		do {
?>
			<p><?=$linha['nome']?> / <?=$linha['cpf']?> / <?=$linha['email']?> / <?=$linha['contato']?> / <?=$linha['material']?></p>
<?php
		// finaliza o loop que vai mostrar os dados
		}while($linha = mysqli_fetch_assoc($dados));
	// fim do if 
	}
?>
</body>
</html>
<?php
// tira o resultado da busca da memória
mysqli_free_result($dados);
?>