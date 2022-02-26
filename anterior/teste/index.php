<?php 
// INICIA LIGAÇÃO À BASE DE DADOS

$con=mysqli_connect("mysql.weblink.com.br","u430987243_hnes","hne2017","u430987243_hnes");

// VERIFICA A LIGAÇÃO NÃO TEM ERROS
if (mysqli_connect_errno())
{
    // CASO TENHA ERROS MOSTRA O ERRO DE LIGAÇÃO À BASE DE DADOS
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$contato = $_POST['contato'];
$material = $_POST['material'];

if(empty($nome) && empty($cpf) && empty($email) && empty($contato) && empty($material)){
	//verifica se o usuario preencheu todos os campos
	     echo "<script>alert ('Preencha todos os campos');
				</script>";
	  }else if(empty($nome)){ 
	     echo "<script>alert ('Preencha nome');
				</script>";
	  }else if(empty($cpf)){ 
	     echo "<script>alert ('Preencha CPF');
				</script>";
	  }else if(empty($email)){
	     echo "<script>alert ('Preencha email');
				</script>";
	  }else if(empty($contato)){
	    echo "<script>alert ('Preencha contato');
				</script>";
	  }else if(empty($material)){
	    echo "<script>alert ('Preencha material');
				</script>";
	  }else{

// CASO TUDO ESTEJA OK INSERE DADOS NA BASE DE DADOS
$sql= "INSERT INTO orcamento(nome, cpf, email, contato, material) VALUES ('$nome', '$cpf', '$email', '$contato', '$material')";

// CASO ESTEJA TUDO OK ADICIONA OS DADOS, SENÃO MOSTRA O ERRO
if (mysqli_query($con,$sql)){
			   echo "<script>alert ('Orçamento cadastrado com sucesso!');
		 		location.href = '../servicos.html';
				</script>";
			}else{
			   echo "<script>alert ('Erro ao cadastrar!');
				</script>";
			}
	  }
	  

?>
<html>
<body>

<form method="post" >
    Nome: <input type="text" name="nome" value="<?php echo $nome; ?>">
    CPF: <input type="text" name="cpf" value="<?php echo $cpf; ?>">
    email: <input type="text" name="email" value="<?php echo $email; ?>">
    Contato: <input type="text" name="contato" value="<?php echo $contato; ?>">
    Material: <input type="text" name="material" value="<?php echo $material; ?>">
    
    <input type="submit">
</form>

</body>
</html>
