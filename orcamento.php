<?php 
// INICIA LIGAÇÃO À BASE DE DADOS

//$con=mysqli_connect("mysql.weblink.com.br","u430987243_hnes","hne2017","u430987243_hnes");
include_once "admin/iniSis.php";

	$conn = @mysql_connect(HOST,USER,PASS) or die('Erro ao conectar' .mysql_error());
	$dbsa = @mysql_select_db(DBSA) or die('Erro ao selecionar banco de dados' .mysql_error());


$nome = @$_POST['nome'];
$email = @$_POST['email'];
$contato = @$_POST['contato'];
$elaboracao_paisagismo = @$_POST['elaboracao_paisagismo'];
$execucao_paisagismo = @$_POST['execucao_paisagismo'];
$projeto_iluminacao = @$_POST['iluminacao'];
$projeto_irrigacao = @$_POST['irrigacao'];
$poco_irrigacao = @$_POST['poco_irrigacao'];
$projeto_arquitetonico = @$_POST['arquitetonico'];
$projeto_designer = @$_POST['designer'];
$construcao_civil = @$_POST['construcao_civil'];
$area_degradada = @$_POST['area_degradada'];
$campo_futebol = @$_POST['campo_futebol'];
$rec_campo_futebol = @$_POST['rec_campo_futebol'];
$venda_grama = @$_POST['venda_grama'];
$metragem_grama = @$_POST['metragem_grama'];
$serv_instala_grama = @$_POST['serv_instala_grama'];
$serv_metragem_grama = @$_POST['serv_metragem_grama'];
$venda_planta = @$_POST['venda_planta'];
$quantidade_planta = @$_POST['quantidade_planta'];
$especie_planta = @$_POST['especie_planta'];
$serv_instala_planta = @$_POST['serv_instala_planta'];
$venda_vaso = @$_POST['venda_vaso'];
$quantidade_vaso = @$_POST['quantidade_vaso'];
$venda_artigo_jardim = @$_POST['venda_artigo_jardim'];
$quantidade_artigo_jardim = @$_POST['quantidade_artigo_jardim'];
$desc_artigo_jardim = @$_POST['desc_artigo_jardim'];
$fertilizar_terra = @$_POST['fertilizar_terra'];
$jardim_vertical = @$_POST['jardim_vertical'];
$horta_vertical = @$_POST['horta_vertical'];
$canteiros = @$_POST['canteiros'];
$tipo_imovel = @$_POST['tipo_imovel'];
$tamanho_projeto = @$_POST['tamanho_projeto'];
$prazo_servico = @$_POST['prazo_servico'];

$imagem1 = @$_FILES['imagem1'];
$imagem2 = @$_FILES['imagem2'];
$imagem3 = @$_FILES['imagem3'];

$descricao_detalhe = @$_POST['descricao_detalhe'];
$visita_tecnica = @$_POST['visita_tecnica'];

	/******************************************************
	************************IMAGEM 1 *********************
	******************************************************/
			// Pega extensão da imagem
			@preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $imagem1['name'], $ext);
 
        	// Gera um nome único para a imagem
        	@$nome_imagem1 = md5(uniqid(time())) . "." . $ext[1];
 
        	// Caminho de onde ficará a imagem
        	$caminho_imagem = "img/upload_orcamento/".$nome_imagem1;
 
			// Faz o upload da imagem para seu respectivo caminho
			@move_uploaded_file($imagem1['tmp_name'], $caminho_imagem);
	/******************************************************
	************************IMAGEM 2 *********************
	******************************************************/
			// Pega extensão da imagem
			@preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $imagem2['name'], $ext);
 
        	// Gera um nome único para a imagem
        	@$nome_imagem2 = md5(uniqid(time())) . "." . $ext[1];
 
        	// Caminho de onde ficará a imagem
        	$caminho_imagem = "img/upload_orcamento/".$nome_imagem2;
 
			// Faz o upload da imagem para seu respectivo caminho
			@move_uploaded_file($imagem2['tmp_name'], $caminho_imagem);
			
	/******************************************************
	************************IMAGEM 3 *********************
	******************************************************/
			// Pega extensão da imagem
			@preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $imagem3['name'], $ext);
 
        	// Gera um nome único para a imagem
        	@$nome_imagem3 = md5(uniqid(time())) . "." . $ext[1];
 
        	// Caminho de onde ficará a imagem
        	$caminho_imagem = "img/upload_orcamento/".$nome_imagem3;
 
			// Faz o upload da imagem para seu respectivo caminho
			@move_uploaded_file($imagem3['tmp_name'], $caminho_imagem);
	
	
if(empty($nome) && empty($email) && empty($contato)){
	//verifica se o usuario preencheu todos os campos
	     echo "<script>alert ('Preencha todos os campos');
				</script>";
	  }else if(empty($nome)){ 
	     echo "<script>alert ('Preencha nome');
				</script>";
	  }else if(empty($email)){
	     echo "<script>alert ('Preencha email');
				</script>";
	  }else if(empty($contato)){
	    echo "<script>alert ('Preencha contato');
				</script>";
	  }/*else if(empty($material)){
	    echo "<script>alert ('Preencha Algum Serviço');
				</script>";
	  }*/else{

// CASO TUDO ESTEJA OK INSERE DADOS NA BASE DE DADOS
$sql= "INSERT INTO orcamento (
					nome,
					email,
					contato,
					elaboracao_projeto_paisagistico,
					execucao_projeto_paisagistico,
					instala_sist_iluminacao,
					instala_sist_irrigacao,
					poco_artesiano,
					projeto_arquitetonico,
					projeto_designer_interior,
					projeto_construcao_civil,
					prog_recup_degradada,
					implantacao_campo_futebol,
					recup_manutencao_campo_futebol,
					venda_grama,
					metragem_grama,
					serv_instalacao_grama,
					serv_metragem_grama,
					venda_planta,
					quant_planta,
					especie_planta,
					serv_plantil_planta,
					venda_vaso,
					quant_vaso,
					venda_artigo_jardim,
					quant_artigo_jardim,
					desc_artigo_jardim,
					fetiliza_terra,
					jardim_vertical,
					horta_vertical,
					canteiro,
					tipo_imovel,
					tamanho_projeto,
					prazo_servico,
					imagem1,
					imagem2,
					imagem3,
					descricao_detalhe,
					visita_tecnica) 
						VALUES (
					'$nome',
					'$email',
					'$contato',
					'$elaboracao_paisagismo',
					'$execucao_paisagismo',
					'$projeto_iluminacao',
					'$projeto_irrigacao',
					'$poco_irrigacao',
					'$projeto_arquitetonico',
					'$projeto_designer',
					'$construcao_civil',
					'$area_degradada',
					'$campo_futebol',
					'$rec_campo_futebol',
					'$venda_grama',
					'$metragem_grama',
					'$serv_instala_grama',
					'$serv_metragem_grama',
					'$venda_planta',
					'$quantidade_planta',
					'$especie_planta',
					'$serv_instala_planta',
					'$venda_vaso',
					'$quantidade_vaso',
					'$venda_artigo_jardim',
					'$quantidade_artigo_jardim',
					'$desc_artigo_jardim',
					'$fertilizar_terra',
					'$jardim_vertical',
					'$horta_vertical',
					'$canteiros',
					'$tipo_imovel',
					'$tamanho_projeto',
					'$prazo_servico',
					'$nome_imagem1',
					'$nome_imagem2',
					'$nome_imagem3',
					'$descricao_detalhe',
					'$visita_tecnica')";

// CASO ESTEJA TUDO OK ADICIONA OS DADOS, SENÃO MOSTRA O ERRO
if (mysql_query($sql, $conn)){
			   echo "<script>alert ('Orçamento cadastrado com sucesso!');
		 		location.href = 'servicos.html';
				</script>";
				
				/*echo "<script>alert ('Orçamento cadastrado com sucesso!');
		 		location.href = '../servicos.html';
				</script>";*/
			}else{
			   echo "<script>alert ('Erro ao cadastrar!');
				</script>";

	  }
	  
	   }
	  

?>

<!DOCTYPE html>
<html>
	<head>

		<!-- Basico -->
		<meta charset="utf-8">
		<title>Horto Nova Era | Contato</title>		
		<meta name="keywords" content="Horto Nova Era" />
		<meta name="description" content="Horto Paisagismo - Nova Era">
		<meta name="author" content="murilofiuza.com">

		<!-- movel Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/favicon1.ico" type="image/x-icon" />

		<!-- Web Fontes  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/bootstrap.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css">
		<link rel="stylesheet" href="vendor/owlcarousel/owl.carousel.min.css" media="screen">
		<link rel="stylesheet" href="vendor/owlcarousel/owl.theme.default.min.css" media="screen">
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" media="screen">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css">
		<link rel="stylesheet" href="css/theme-elements.css">
		<link rel="stylesheet" href="css/theme-blog.css">
		<link rel="stylesheet" href="css/theme-shop.css">
		<link rel="stylesheet" href="css/theme-animate.css">

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">
		<style type="text/css">
		a:link {
	color: #000;
}
        body,td,th {
	color: #CCCCCC;
}
        </style>

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>

		<!--[if IE]>
			<link rel="stylesheet" href="css/ie.css">
		<![endif]-->

		<!--[if lte IE 8]>
			<script src="vendor/respond/respond.js"></script>
			<script src="vendor/excanvas/excanvas.js"></script>
		<![endif]-->

</head>
	<body>

		<div class="body">
			<header id="header">
				<div class="container">
					<div class="logo">
						<a href="index.html">
							<img alt="Horto Nova Era" width="280" height="134" data-sticky-width="176" data-sticky-height="78" src="img/logo.png">
						</a>
					</div>
					<!--div class="search">
						<form id="searchForm" action="page-search-results.html" method="get">
							<div class="input-group">
							<!-- livezilla.net PLACE IN BODY <div id="lvztr_d12" style="display:none"></div><script id="lz_r_scr_f5db375567e9576ae7861d6db7cc431e" type="text/javascript">lz_code_id="f5db375567e9576ae7861d6db7cc431e";var script = document.createElement("script");script.async=true;script.type="text/javascript";var src = "http://hortonovaera.com.br/livezilla/server.php?rqst=track&output=jcrpt&intgroup=c3VwcG9ydA__&el=cHQtYnI_&nse="+Math.random();script.src=src;document.getElementById('lvztr_d12').appendChild(script);</script><noscript><img src="http://hortonovaera.com.br/livezilla/server.php&quest;rqst=track&amp;output=nojcrpt" width="0" height="0" style="visibility:hidden;" alt=""></noscript><a href="javascript:void(window.open('http://hortonovaera.com.br/livezilla/chat.php?v=2&intgroup=c3VwcG9ydA__&el=cHQtYnI_','','width=400,height=600,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))" class="lz_cbl"><img src="http://hortonovaera.com.br/livezilla/image.php?id=3&type=inlay" width="180" height="50" style="border:0px;" alt="LiveZilla Live Chat Software"></a>
							</div>
						</form>
					</div-->
					<nav>
						<ul class="nav nav-pills nav-top">
							<!--li>
								<a href="#"><i class="fa fa-angle-right"></i>NOSSA LOJA VIRTUAL EM BREVE!</a>
							</li-->
							<li></li>
							<li class="phone">
								<span><img src="img/icons/whats_icon.png" width="20px" height="20px"> (55)71 98688-3754</span>
							</li>
						</ul>
					</nav>
					<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
						<i class="fa fa-bars"></i>
					</button>
				</div>
				<div class="navbar-collapse nav-main-collapse collapse">
					<div class="container">
						<ul class="social-icons">
							<li class="facebook"><a href="http://www.facebook.com/hortonovaera/" target="_blank" title="Facebook">Facebook</a></li>
							<li class="instagram"><a href="https://www.instagram.com/hortonovaera/" target="_blank" title="Instagram">Instagram</a></li>
							<!--<li class="skype"><a href="http://www.skype.com/" target="_blank" title="Skype">Skype</a></li>-->
						</ul>
						<nav class="nav-main mega-menu">
							<ul class="nav nav-pills nav-main" id="mainMenu">
								<li>
									<a href="index.html">Home</a>
								</li>
								<li>
									<a href="sobre.html">Sobre</a>
								</li>
                              <!--Em standBy-->
								<li>
									<a href="servicos.html">Serviços</a>
								</li>
                                
                                <li>
									<a href="produtos.html">Produtos</a>
								</li>
                                
                                <li>
									<a href="clientes.html">Clientes</a>
								</li>
                                
                                <<li>
									<a href="profissional.html">Profissionais</a>
								</li>
                                
                                <li>
									<a href="portfolio.html">Portifólio</a>
								</li>
								
                                <li>
									<a href="orcamento.php">Orçamento</a>
								</li>
                                
								<li>
									<a href="contato.html">Contato</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
			</header>

			<div role="main" class="main">

				<section class="page-top">
					

					<div class="container">
						<div class="row"></div>
						<div class="row">
							<div class="col-md-12">
								<h1>Orçamento</h1>
							</div>
						</div>
					</div>
				</section>

				<!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
				<!--<div id="googlemaps" class="google-map"></div>-->

				<div class="container">

				  <div class="row">
					<div class="col-md-6-orcamento">

			
							<form  method="POST" enctype="multipart/form-data">
								<div class="row">
									<div class="form-group">
										<div class="col-md-6">
											<p><label>Nome *</label>
											<input type="text" value="" data-msg-required="Preencha seu nome." maxlength="100" class="form-control" name="nome" id="nome" required>
									  </div>
										<div class="col-md-6">
											<p><label>Seu email*</label>
											<input type="email" value="" data-msg-required="Preencha seu email" data-msg-email="preencher um email valido." maxlength="75" class="form-control" name="email" id="email" required>
										</div>
                                        <div class="col-md-6">
											<p><label>Contato*</label>
											<input type="tel" value="" data-msg-required="Preencha seu email" maxlength="16" class="form-control" name="contato" id="contato" required>
										</div>
										
                                       <!--<div class="col-md-6">
											<p><label>Data*</label>
											<input type="text" value=""  maxlength="100" class="form-control" name="data" id="data" required>
										</div>-->
									</div>
								</div>
								<label><h1>Qual é o tipo de serviço que você precisa?</h1></label>
									<div class="col-md-12">
								
											<p><input type="checkbox"  name="elaboracao_paisagismo" id="elaboracao_paisagismo" value="Sim" >
											<label>Elaboração de projetos paisagístico</label>
											<p><input type="checkbox"  name="execucao_paisagismo" id="execucao_paisagismo" value="Sim" >
											<label>Execução de projetos paisagístico</label>
											<p><input type="checkbox"  name="iluminacao" id="iluminacao" value="Sim" >
											<label>Projeto de iluminação</label>
											<p><input type="checkbox"  name="irrigacao" id="irrigacao" value="Sim">
											<label>Projeto de irrigação</label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;  
                                            <label>Possui poço artesiano? </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <INPUT TYPE="RADIO" NAME="poco_irrigacao" id="poco_irrigacao" VALUE="Sim"> SIM
                                            &nbsp;&nbsp;&nbsp;&nbsp;
											<INPUT TYPE="RADIO" NAME="poco_irrigacao" id="poco_irrigacao" VALUE="Não"> NÃO
                                            
                                          
                                            
									  		<p><input type="checkbox"  name="arquitetonico" id="arquitetonico" value="Sim" >
                                        	<label>Projeto Arquitetônico</label>
											<p><input type="checkbox"  name="designer" id="designer" value="Sim" >
											<label>Projeto de Designer de Interiores</label>
											<p><input type="checkbox"  name="construcao_civil" id="construcao_civil" value="Sim">
											<label>Projeto e soluções em construção civil</label>
											<p><input type="checkbox"  name="area_degradada" id="area_degradada" value="Sim" >
											<label>Programa de recuperação em áreas degradadas (PRAD)</label>
											<p><input type="checkbox"  name="campo_futebol" id="campo_futebol" value="Sim" >
											<label>Implantação de Campo de Futebol</label>
                                      <p><input type="checkbox"  name="rec_campo_futebol" id="rec_campo_futebol" value="Sim" >
											<label>Recuperação e Manutenção de Campo de Futebol</label>
									  <p><input type="checkbox"  name="venda_grama" id="venda_grama" value="Sim" onclick="document.getElementById('metragem_grama').disabled = !this.checked;">
											<label>Venda de Grama</label>
											<p><input type="text"  name="metragem_grama" id="metragem_grama" maxlength="15" disabled="disabled" >
											<label>Metragem (m²)</label>
											<p><input type="checkbox"  name="serv_instala_grama" id="serv_instala_grama" value="Sim" onclick="document.getElementById('serv_metragem_grama').disabled = !this.checked;">
											<label>Serviço de instalação de Grama</label>
                                            <p><input type="text"  name="serv_metragem_grama" id="serv_metragem_grama" maxlength="15" disabled="disabled" >
											<label>Metragem (m²)</label>
									  <p><input type="checkbox"  name="venda_planta" id="venda_planta" value="Sim" 
                                      onclick="document.getElementById('quantidade_planta').disabled = !this.checked; 
                                      document.getElementById('especie_planta').disabled = !this.checked;">
                                      
											<label>Venda de Plantas</label>
											<p><input type="text"  name="quantidade_planta" id="quantidade_planta" maxlength="15" disabled="disabled" >
											<label>Quantidade  (unidades)</label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="text"  name="especie_planta" id="especie_planta" maxlength="35" disabled="disabled" >
                                            <label>Espécie</label>
											<!---->
                                            
											<p><input type="checkbox"  name="serv_instala_planta" id="serv_instala_planta" value="Sim" >
											<label>Serviço de instalação de Plantas</label>
                                           
                                           <p><input type="checkbox"  name="venda_vaso" id="venda_vaso" value="Sim" 
                                      onclick="document.getElementById('quantidade_vaso').disabled = !this.checked;">
                                      
											<label>Venda de Vasos</label>
											<p><input type="text"  name="quantidade_vaso" id="quantidade_vaso" maxlength="15" disabled="disabled" >
											<label>Quantidade de Vasos(unidades)</label>
                                            
                                            <p><input type="checkbox"  name="venda_artigo_jardim" id="venda_artigo_jardim" value="Sim" onclick="document.getElementById('desc_artigo_jardim').disabled = !this.checked;
            document.getElementById('quantidade_artigo_jardim').disabled = !this.checked;">
											<label>Venda de artigos para jardins  </label>
                                            
									  <p><label>Descrição dos artigos para jardins</label>
									    <br>
                                        <textarea name="desc_artigo_jardim" required class="form-control" id="desc_artigo_jardim" data-msg-required="Digite sua observação." maxlenght="500" disabled="disabled"></textarea>
                                        <p><input type="text"  name="quantidade_artigo_jardim" id="quantidade_artigo_jardim" maxlength="15" disabled="disabled" >
                                            <label>Quantidade dos artigos para jardins</label>
                                        <p><input type="checkbox"  name="fertilizar_terra" id="fertilizar_terra" value="Sim" >
											<label>Fertilizar terra/gramado</label>
                                         <p><input type="checkbox"  name="jardim_vertical" id="jardim_vertical" value="Sim" >
											<label>Jardins Verticais</label>   
                                         <p><input type="checkbox"  name="horta_vertical" id="horta_vertical" value="Sim" >
											<label>Horta Verticais</label> 
                                         <p><input type="checkbox"  name="canteiros" id="canteiros" value="Sim" >
											<label>Canteiros</label>        
									  </div>
                                      
									<div class="col-md-12">
                                    <label><h1>Qual o tipo de imóvel?</h1></label>
                                    <p><input type="radio"  name="tipo_imovel" id="tipo_imovel" value="Apartamento" >
											<label>Apartamento</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;    
                                       <input type="radio"  name="tipo_imovel" id="tipo_imovel" value="Casa" >
											<label>Casa</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;    
                                       <input type="radio"  name="tipo_imovel" id="tipo_imovel" value="Comercial" >
											<label>Comercial</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;
                                       <input type="radio"  name="tipo_imovel" id="tipo_imovel" value="Condomínio" >
											<label>Condomínio</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;    
                                       <input type="radio"  name="tipo_imovel" id="tipo_imovel" value="Outros" >
											<label>Outros</label>
                                    </div>
                                    
									<div class="col-md-12">
                                    <label><h1>Qual o tamanho do projeto?</h1></label>
                                    
                                    <p><input type="radio"  name="tamanho_projeto" id="tamanho_projeto" value="Pequeno (menor que 50m²)" >
											<label>Pequeno (menor que 50m²)</label>
                                        
                                    <p><input type="radio"  name="tamanho_projeto" id="tamanho_projeto" value="Médio (50 a 150m²)" >
											<label>Médio (50 a 150m²)</label>
                                       
                                    <p><input type="radio"  name="tamanho_projeto" id="tamanho_projeto" value="Grande (entre 150 a 250m²)" >
											<label>Grande (entre 150 a 250m²)</label>
                                       
                                    <p><input type="radio"  name="tamanho_projeto" id="tamanho_projeto" value="Muito Grande (a cima de 250m²)" >
											<label>Muito Grande (a cima de 250m²)</label>
                                    </div>
                                    
                                    
									<div class="col-md-12">
                                    <label><h1>Para quando você precisa deste serviço?</h1></label>
                                    <p><input type="radio"  name="prazo_servico" id="prazo_servico" value="O quanto antes possível" >
											<label>O quanto antes possível</label>
                                        
                                    <p><input type="radio"  name="prazo_servico" id="prazo_servico" value="Nos próximos 30 dias" >
											<label>Nos próximos 30 dias</label>
                                       
                                    <p><input type="radio"  name="prazo_servico" id="prazo_servico" value="Nos próximos 3 meses" >
											<label>Nos próximos 3 meses</label>
                                       
                                    <p><input type="radio"  name="prazo_servico" id="prazo_servico" value="Não tenho certeza" >
											<label>Não tenho certeza</label>
                                    </div>
                                    
                                    
									<div class="col-md-12">
                                    <label><h1>Inserir imagem do local</h1></label>
                                    <p><input type="file"  name="imagem1" id="imagem1">
                                    <p><input type="file"  name="imagem2" id="imagem2">
                                    <p><input type="file"  name="imagem3" id="imagem3">
                                       <!--input type="submit" name="9" value="Salvar"-->
                                    </div>
                                    
                                    
									<div class="col-md-12">
                                    <label><h1>Descreva o que você precisa:</h1></label>
                                    <p><textarea name="descricao_detalhe" required class="form-control" id="descricao_detalhe" data-msg-required="Descreva aqui" ></textarea>
                                    </div>
                                    
                                    <label><h1>Gostaria de agendar visita técnica?</h1></label>
									<div class="col-md-12">
                                    <p><INPUT TYPE="RADIO" NAME="visita_tecnica" VALUE="Sim"> SIM
											<INPUT TYPE="RADIO" NAME="visita_tecnica" VALUE="Não"> NÃO
                                    </div>
									
							  <!--<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<p><label>Mensagem *</label>
											<textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
										</div>
									</div>
							  </div>-->
								<div class="row">
									<div class="col-md-12">
										<input type="submit" value="Enviar Orçamento" class="btn_contato btn-primary btn-lg" >
									</div>
								</div>
							</form>
					  </div>
					</div>
				</div>
			</div>
			<footer id="footer">
				<div class="container">
					<div class="row">
					  <div class="col-md-4">
						  <div class="contact-details">
								<h4>Contato</h4>
							<ul class="contact">
									<li>
									  <i class="fa fa-map-marker"></i> <strong>Endereço </strong>Alameda Jequitibá, nº 100 – São Marcos Cep: 41.250-581, Salvador/Ba , Brasil
							  </li>
									<li>
									  <i class="fa fa-phone"></i><strong>TEL:</strong>(55)71 98688-3754(whatsapp) / 71 98105-5585 / (71) 3412-4518 (Fixo)
							  </li>
									<li><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:mail@example.com">hortonovaera@hotmail.com</a></li>
							</ul>
						  </div>
					  </div>
						<div class="col-md-2">
							<h4>Siga-nos</h4>
							<div class="social-icons">
								<ul class="social-icons">
									<li class="facebook"><a href="http://www.facebook.com/hortonovaera/" target="_blank" data-placement="bottom" data-tooltip title="Facebook">Facebook</a></li>
									<li class="instagram"><a href="http://www.instagram.com/" target="_blank" data-placement="bottom" data-tooltip title="Instagram">Instagram</a></li>
									<!--<li class="skype"><a href="http://www.skype.com/" target="_blank" data-placement="bottom" data-tooltip title="Skype">Skype</a></li>-->
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-copyright">
					<div class="container">
						<div class="row">
							<div class="col-md-1">
								<a href="index.html" class="logo">
									<img src="img/logo.png" alt="Porto Website Template" width="40" height="40" class="img-responsive">
								</a>
							</div>
							<div class="col-md-7">
								<p>© Copyright 2017. Todos os direitos reservados.</p>
							</div>
							<div class="col-md-4">
								<nav id="sub-menu">
									<ul>
										<!--<li><a href="page-faq.html">FAQ's</a></li>
										<li><a href="sitemap.html">Sitemap</a></li>-->
										<li><a href="contato.html">Contato</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>

		<!-- Vendor -->
		<script src="vendor/jquery/jquery.js"></script>
		<script src="vendor/jquery.appear/jquery.appear.js"></script>
		<script src="vendor/jquery.easing/jquery.easing.js"></script>
		<script src="vendor/jquery-cookie/jquery-cookie.js"></script>
		<script src="vendor/bootstrap/bootstrap.js"></script>
		<script src="vendor/common/common.js"></script>
		<script src="vendor/jquery.validation/jquery.validation.js"></script>
		<script src="vendor/jquery.stellar/jquery.stellar.js"></script>
		<script src="vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="vendor/jquery.gmap/jquery.gmap.js"></script>
		<script src="vendor/isotope/jquery.isotope.js"></script>
		<script src="vendor/owlcarousel/owl.carousel.js"></script>
		<script src="vendor/jflickrfeed/jflickrfeed.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/vide/vide.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="js/theme.js"></script>

		<!-- Specific Page Vendor and Views -->
		<script src="js/views/view.contact.js"></script>
		
		<!-- Theme Custom -->
		<script src="js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>

		<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script>

			/*
			Map Settings

				Find the Latitude and Longitude of your address:
					- http://universimmedia.pagesperso-orange.fr/geo/loc.htm
					- http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

			*/

			// Map Markers
			var mapMarkers = [{
				address: "New York, NY 10017",
				html: "<strong>New York Office</strong><br>New York, NY 10017",
				icon: {
					image: "img/pin.png",
					iconsize: [26, 46],
					iconanchor: [12, 46]
				},
				popup: true
			}];

			// Map Initial Location
			var initLatitude = 40.75198;
			var initLongitude = -73.96978;

			// Map Extended Settings
			var mapSettings = {
				controls: {
					draggable: true,
					panControl: true,
					zoomControl: true,
					mapTypeControl: true,
					scaleControl: true,
					streetViewControl: true,
					overviewMapControl: true
				},
				scrollwheel: false,
				markers: mapMarkers,
				latitude: initLatitude,
				longitude: initLongitude,
				zoom: 16
			};

			var map = $("#googlemaps").gMap(mapSettings);

			// Map Center At
			var mapCenterAt = function(options, e) {
				e.preventDefault();
				$("#googlemaps").gMap("centerAt", options);
			}

		</script>

		<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
		<script type="text/javascript">
		
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-12345678-1']);
			_gaq.push(['_trackPageview']);
		
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		
		</script>
		 -->

	</body>
</html>