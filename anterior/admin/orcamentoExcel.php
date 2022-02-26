<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once "iniSis.php";

	$conn = @mysql_connect(HOST,USER,PASS) or die('Erro ao conectar' .mysql_error());
	$dbsa = @mysql_select_db(DBSA) or die('Erro ao selecionar banco de dados' .mysql_error());
	
	$id = $_GET['id'];
	
	$sql = "select * FROM orcamento WHERE id = '$id'" ;
		// Executa a consulta
		$query = @mysql_query($sql);
		$row = @mysql_fetch_assoc($query);
		
//$grupo = selectAllPessoa();
//var_dump($grupo);

$arqexcel = "<meta charset='UTF-8'>";

$arqexcel .= "<table border='1'>
            <thead>
			<tr>
			<th height='60'><img src='img/logo.png' width='100' height='50'/> </th>
                <th></th>
                <th></th>
			</tr>
                <tr>
                    <th>Nome: </th>
                    <th>Email:</th>
                    <th>Contato:</th>
                    
                </tr>
				<tr>
                    <td>".$row['nome']."</td>
                    <td>".$row['email']."</td>
                    <td>".$row['contato']."</td>
                 </tr>
				 
				 <tr>
					<td></td>
					<td></td>
					<td></td>
				 </tr>
				 <tr>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				 <tr>
                    <th>Elaboração de projetos paisagistico: </th>
					<td>".$row['elaboracao_projeto_paisagistico']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Execução de projetos paisagistico: </th>
					<td>".$row['execucao_projeto_paisagistico']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Projeto Iluminação: </th>
					<td>".$row['projeto_iluminacao']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Projeto Irrigação:  </th>
					<td>".$row['projeto_irrigacao']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Projeto Arquitetônico: </th>
					<td>".$row['projeto_arquitetonico']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Projeto de Designer de Interior: </th>
					<td>".$row['projeto_designer_interior']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Projeto e soluções em construção civil:</th>
					<td>".$row['projeto_solucoes_construcao_civil']."</td>
					<td></td>
				<tr>
                    <th>Projeto Arquitetônico: </th>
					<td>".$row['projeto_arquitetonico']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Programa de recuperação em áreas degradadas: </th>
					<td>".$row['programa_recuperacao_degradada']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Manutenção em áreas verdes e jardins: </th>
					<td>".$row['manutencao_jardins']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Implantação de projetos paisagistico: </th>
					<td>".$row['implantacao_projeto_paisagistico']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Venda de Gramados:  </th>
					<td>".$row['venda_gramado']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Quantidade Gramado (m²): </th>
					<td>".$row['tamanho_grama_metro']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Implantação de Gramados:  </th>
					<td>".$row['implantacao_gramado_paisagistico']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Venda de Plantas:  </th>
					<td>".$row['venda_planta']."</td>
					<td></td>
                </tr>
				
				
				<tr>
                    <th>Quantidade Plantas:  </th>
					<td>".$row['quantidade_planta']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Implantação de Plantas:  </th>
					<td>".$row['implatacao_planta']."</td>
					<td></td>
                </tr>
				<tr>
                    <th>Observações de solicitação:  </th>
					<td>".$row['observacao_solicitado']."</td>
					<td></td>
                </tr>
            </tr>
				
				
            </thead>
            <tbody>";
                
                    //foreach ($row as $pessoa) { 
           /*$arqexcel .="        
				 <tr>
                    <td>".$row['projeto_paisagistico']."</td>
                 </tr>";*/
				 
                  
                
          $arqexcel .="  </tbody>
        </table>";
          
          header("Content-Type: application/xls");
          header("Content-Disposition:attachment; filename = relatorio.xls");
          echo $arqexcel;

