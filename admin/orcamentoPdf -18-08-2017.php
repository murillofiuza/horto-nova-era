<?php
//REFERENCIAR O ARQUIVO COM A CLASSE DE GERAÇÃO DE PDF
include '../pdf/mpdf.php';
include_once "iniSis.php";

	$conn = @mysql_connect(HOST,USER,PASS) or die('Erro ao conectar' .mysql_error());
	$dbsa = @mysql_select_db(DBSA) or die('Erro ao selecionar banco de dados' .mysql_error());
	
	$id = $_GET['id'];
	
		$sql = "select * FROM orcamento WHERE id = '$id'" ;
		// Executa a consulta
		$query = @mysql_query($sql);
		$row = @mysql_fetch_assoc($query);
		
		$nome = ['nome'];
		//$email = ['email'];
		//$contato = ['contato'];

		
$saida = 
        "<html>
            <body>
                <center><img src='img/logo.png' width='100' height='50'/></center>
				<h1>ORÇAMENTO DE SERVIÇOS:</h1>
                <ul>
                    <li><strong>Nome:</strong> </li> ".$row['nome']."
                    <li><strong>E-mail:</strong> </li>".$row['email']."
                    <li><strong>Contato:</strong> </li>".$row['contato']."
					<li><strong>Serviços Solicitados:</strong> </li>
					
                </ul>
				<ul>
				
                    <li><strong>Elaboração de projetos paisagistico:</strong> </li> ".$row['elaboracao_projeto_paisagistico']."
					<li><strong>Execução de projetos paisagistico:</strong> </li> ".$row['execucao_projeto_paisagistico']."
					<li><strong>Projeto Iluminação:</strong> </li> ".$row['projeto_iluminacao']."
					<li><strong>Projeto Irrigação:</strong> </li> ".$row['projeto_irrigacao']."
					<li><strong>Projeto Arquitetônico:</strong> </li> ".$row['projeto_arquitetonico']."
					<li><strong>Projeto de Designer de Interior:</strong> </li> ".$row['projeto_designer_interior']."
					<li><strong>Projeto e soluções em construção civil:</strong> </li> ".$row['projeto_solucoes_construcao_civil']."
					<li><strong>Programa de recuperação em áreas degradadas:</strong> </li> ".$row['programa_recuperacao_degradada']."
					<li><strong>Manutenção de áreas verdes e jardins:</strong> </li> ".$row['manutencao_jardins']."
					<li><strong>Venda de Gramados:</strong> </li> ".$row['venda_gramado']."
					<li><strong>Quantidade Gramado (m²):</strong> </li> ".$row['tamanho_grama_metro']." m².
					<li><strong>Implantação de Gramados:</strong> </li> ".$row['implantacao_gramado_paisagistico']."
					<li><strong>Venda de Plantas:</strong> </li> ".$row['venda_planta']."
					 <li><strong>Quantidade Plantas:</strong> </li>".$row['quantidade_planta']." Unidades.
					<li><strong>Implantação de Plantas:</strong> </li> ".$row['implatacao_planta']."
                   
					<br><br>
                    <li><strong>Observações de solicitação:</strong> </li>".$row['observacao_solicitado']."
					
					
                </ul>
                
            </body>
        </html>
        ";

		
		
		
		
$arquivo = "ORÇAMENTO-SERVIÇOS-CLIENTE-".$row['nome'].".pdf";

$mpdf = new mPDF();
$mpdf->WriteHTML($saida);
/*
 * F - salva o arquivo NO SERVIDOR
 * I - abre no navegador E NÃO SALVA
 * D - chama o prompt E SALVA NO CLIENTE
 */

$mpdf->Output($arquivo, 'I');

echo "PDF GERADO COM SUCESSO";


?>
