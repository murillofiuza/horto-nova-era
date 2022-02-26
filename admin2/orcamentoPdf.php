
<?php
//REFERENCIAR O ARQUIVO COM A CLASSE DE GERAÇÃO DE PDF
include '../pdf/mpdf.php';
include_once '../dts/iniSis.php';

	$conn = @mysql_connect(HOST,USER,PASS) or die('Erro ao conectar' .mysql_error());
	$dbsa = @mysql_select_db(DBSA) or die('Erro ao selecionar banco de dados' .mysql_error());
	
	$id = $_GET ['id'];
	
		$sql = "select * FROM orcamento WHERE id = '$id'" ;
		// Executa a consulta
		$query = @mysql_query($sql);
		$row = @mysql_fetch_assoc($query);
		
		//$nome = ['nome'];
		//$email = ['email'];
		//$contato = ['contato'];
		if($row['execucao_projeto_paisagistico'] == 'Sim'){ echo "<li><strong>Execução de projetos paisagistico:</strong> </li> ".$row['execucao_projeto_paisagistico'];}else{ echo "";}
		
$saida = 
        "<html>
            <body>
                <center><img src='../img/logo.png' width='100' height='50'/></center>
				<h2>ORÇAMENTO DE SERVIÇOS:</h2>
                <ul>
                    <li><strong>Nome:</strong> </li> ".$row['nome']."
                    <li><strong>E-mail:</strong> </li>".$row['email']."
                    <li><strong>Contato:</strong> </li>".$row['contato']."
					<br>
					<li><strong>Serviços Solicitados:</strong> </li>
                </ul>
				
				<table align='center' width='900' border='0' >
					<tr bgcolor='#F7F7F7'>
						<td ><strong>Elaboração de projetos paisagistico:</strong><br></td>
						<td colspan='3'>".$row['elaboracao_projeto_paisagistico']."</td>
					</tr>
					<tr>
						<td><strong>Execução de projetos paisagistico:</strong></td>
						<td colspan='3'>".$row['execucao_projeto_paisagistico']."</td>
						
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Projeto Iluminação:</strong></td>
						<td colspan='3'>".$row['instala_sist_iluminacao']."</td>
						
					</tr>	
					<tr>	
						<td><strong>Projeto Irrigação:</strong></td>
						<td>".$row['instala_sist_irrigacao']."</td>
						<td align='right'><strong>Possui poço artesiano?: </strong></td>
						<td>".$row['poco_artesiano']."</td>
					</tr>	
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Projeto Arquitetônico:</strong></td>
						<td colspan='3'>".$row['projeto_arquitetonico']."</td>
					</tr>
					<tr>
						<td><strong>Projeto de Designer de Interior:</strong></td>
						<td colspan='3'>".$row['projeto_designer_interior']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Projeto e soluções em construção civil:</strong></td>
						<td colspan='3'>".$row['projeto_construcao_civil']."</td>
					</tr>	
					<tr>
						<td><strong>Programa de recuperação em áreas degradadas:</strong></td>
						<td colspan='3'>".$row['prog_recup_degradada']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Implantação de Campo de Futebol:</strong></td>
						<td colspan='3'>".$row['implantacao_campo_futebol']."</td>

					</tr>
					<tr>	
						<td><strong>Recuperação e Manutenção de Campo de Futebol:</strong></td>
						<td colspan='3'>".$row['recup_manutencao_campo_futebol']."</td>
					</tr>
					
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Venda de Grama:</strong></td>
						<td>".$row['venda_grama']."</td>
						<td align='right'><strong>Metragem da grama:</strong></td>
						<td>".$row['metragem_grama']." m².</td>
					</tr>
					<tr>	
						<td><strong>Serviço de instalação de Grama:</strong></td>
						<td>".$row['serv_instalacao_grama']."</td>
						<td align='right'><strong>Metragem do serviço:</strong></td>
						<td>".$row['serv_metragem_grama']."</td>
					</tr bgcolor='#F7F7F7'>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Venda de Plantas:</strong></td>
						<td>".$row['venda_planta']."</td>
						<td align='right'><strong>Quantidade de plantas:</strong></td>
						<td>".$row['quant_planta']." Unid.</td>
					</tr>
					<tr>	
						<td><strong>Espécie da planta:</strong></td>
						<td colspan='3'>".$row['especie_planta']."</td>

					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Serviço de instalação de Plantas:</strong></td>
						<td colspan='3'>".$row['serv_plantil_planta']." Unid.</td>
					</tr>
					<tr>	
						<td><strong>Venda de Artigos para Jardins:</strong></td>
						<td>".$row['venda_artigo_jardim']."</td>
						<td align='right'><strong>Quantidade:</strong></td>
						<td>".$row['quant_artigo_jardim']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Observações de solicitação:</strong></td>
						<td colspan='3'>".$row['desc_artigo_jardim']."</td>
						
					</tr>
					<tr>	
						<td><strong>Fertilizar terra/gramado:</strong></td>
						<td colspan='3'>".$row['fetiliza_terra']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Jardins Verticais:</strong></td>
						<td colspan='3'>".$row['jardim_vertical']."</td>
					</tr>
					<tr>	
						<td><strong>Horta Verticais:</strong></td>
						<td colspan='3'>".$row['horta_vertical']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Canteiros:</strong></td>
						<td colspan='3'>".$row['canteiro']."</td>
					</tr>
					<tr>	
						<td><strong>Tipo do imovel:</strong></td>
						<td colspan='3'>".$row['tipo_imovel']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Tamanho do projeto:</strong></td>
						<td colspan='3'>".$row['tamanho_projeto']."</td>
					</tr>
					<tr>	
						<td><strong>Para quando você precisa deste serviço?:</strong></td>
						<td colspan='3'>".$row['prazo_servico']."</td>
					</tr>
					<tr bgcolor='#F7F7F7'>	
						<td><strong>Descreva o que você precisa:</strong></td>
						<td colspan='3'>".$row['descricao_detalhe']."</td>
					</tr>
					<tr>	
						<td><strong>Gostaria de agendar visita técnica?:</strong></td>
						<td colspan='3'>".$row['visita_tecnica']."</td>
						
					</tr>
				</table>
				
					
					<table align='center'>
					<tr>
						<td><strong>Imagem 1:</strong><br></td>
						<td><strong>Imagem 2:</strong><br></td>
						<td><strong>Imagem 3:</strong><br></td>
					</tr>
					<tr>	
						<td><img src='../img/upload_orcamento/".$row['imagem1']."' height='151' width='270' /> </td>
						<td><img src='../img/upload_orcamento/".$row['imagem2']."' height='151' width='270' /></td>
						<td><img src='../img/upload_orcamento/".$row['imagem3']."' height='151' width='270' /></td>
					</tr>	
					
					</table>
					
					
                
                
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
