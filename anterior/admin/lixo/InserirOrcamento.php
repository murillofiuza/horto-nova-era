<?php 
// INICIA LIGAÇÃO À BASE DE DADOS
$con=mysqli_connect("mysql.weblink.com.br","u430987243_hnes","hne2017","u430987243_hnes");

// VERIFICA A LIGAÇÃO NÃO TEM ERROS
if (mysqli_connect_errno())
{
    // CASO TENHA ERROS MOSTRA O ERRO DE LIGAÇÃO À BASE DE DADOS
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// CASO TUDO ESTEJA OK INSERE DADOS NA BASE DE DADOS
$sql="INSERT INTO suites (nome, cpf, email, contato, material) VALUES ('$_POST[nome]','$_POST[cpf]','$_POST[email]','$_POST[contato]','$_POST[material]')";


// CASO ESTEJA TUDO OK ADICIONA OS DADOS, SENÃO MOSTRA O ERRO
if (!mysqli_query($con,$sql)){
	
	echo "	<script>
			   alert('Cadastrado com Sucesso !');
			   location.href = '../orcamento.php';
			</script>";
}
else {
	echo "<script>
			   alert('Nao Cadastrado!');
			   location.href = '../orcamento.php';
			</script>";
	// echo mysql_error();
}
mysqli_close($con);

?>