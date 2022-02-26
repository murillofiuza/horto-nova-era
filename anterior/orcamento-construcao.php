<?php 
// INICIA LIGAÇÃO À BASE DE DADOS

//$con=mysqli_connect("mysql.weblink.com.br","u430987243_hnes","hne2017","u430987243_hnes");
include_once "admin/iniSis.php";

	$conn = @mysql_connect(HOST,USER,PASS) or die('Erro ao conectar' .mysql_error());
	$dbsa = @mysql_select_db(DBSA) or die('Erro ao selecionar banco de dados' .mysql_error());


$nome = @$_POST['nome'];
$email = @$_POST['email'];
$contato = @$_POST['contato'];
$p1 = @$_POST['1'];
$p2 = @$_POST['2'];
$p3 = @$_POST['3'];
$p4 = @$_POST['4'];
$p5 = @$_POST['5'];
$p6 = @$_POST['6'];
$p7 = @$_POST['7'];
$p8 = @$_POST['8'];
$p9 = @$_POST['9'];
$p10 = @$_POST['10'];
$p11 = @$_POST['11'];
$p12 = @$_POST['12'];
$p13 = @$_POST['13'];
$p14 = @$_POST['14'];
$p15 = @$_POST['15'];
$p16 = @$_POST['16'];
$p17 = @$_POST['17'];
$p18 = @$_POST['18'];

if(empty($nome) && empty($email) && empty($contato) && empty($material)){
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
$sql= "INSERT INTO orcamento (nome,
email,
contato,
elaboracao_projeto_paisagistico,
execucao_projeto_paisagistico,
projeto_iluminacao,
projeto_irrigacao,
projeto_arquitetonico,
projeto_designer_interior,
projeto_solucoes_construcao_civil,
programa_recuperacao_degradada,
manutencao_jardins,
venda_gramado,
tamanho_grama_metro,
implantacao_gramado,
venda_planta,
quantidade_planta,
implatacao_planta,
observacao_solicitado) VALUES ('$nome', '$email', '$contato', '$p1', '$p2', '$p3', '$p4', '$p5', '$p6', '$p7', '$p8', '$p9', '$p10', '$p11', '$p12', '$p13', '$p14', '$p15', '$p16')";

// CASO ESTEJA TUDO OK ADICIONA OS DADOS, SENÃO MOSTRA O ERRO
if (mysql_query($sql,$conn)){
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

			
							<form  method="POST">
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
								
											<p><input type="checkbox"  name="1" id="elaboracao_paisagismo" value="Sim" >
											<label>Elaboração de projetos paisagístico</label>
											<p><input type="checkbox"  name="2" id="execucao_paisagismo" value="Sim" >
											<label>Execução de projetos paisagístico</label>
											<p><input type="checkbox"  name="3" id="iluminacao" value="Sim" >
											<label>Projeto de iluminação</label>
											<p><input type="checkbox"  name="4" id="irrigacao" value="Sim">
											<label>Projeto de irrigação</label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;  
                                            <label>Possui poço artesiano? </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="checkbox"  name="4" id="poco_irrigacao" value="Sim">SIM
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="checkbox"  name="4" id="poco_irrigacao" value="Nao">NÃO
                                            
									  <p><input type="checkbox"  name="5" id="arquitetonico" value="Sim" >
                                            
										<label>Projeto Arquitetônico</label>
											<p><input type="checkbox"  name="6" id="designer" value="Sim" >
											<label>Projeto de Designer de Interiores</label>
											<p><input type="checkbox"  name="7" id="construcao_civil" value="Sim">
											<label>Projeto e soluções em construção civil</label>
											<p><input type="checkbox"  name="8" id="area_degradada" value="Sim" >
											<label>Programa de recuperação em áreas degradadas (PRAD)</label>
											<p><input type="checkbox"  name="9" id="campo_futebol" value="Sim" >
											<label>Implantação de Campo de Futebol</label>
                                      <p><input type="checkbox"  name="9" id="rec_campo_futebol" value="Sim" >
											<label>Recuperação e Manutenção de Campo de Futebol</label>
									  <p><input type="checkbox"  name="10" id="venda_grama" value="Sim" onclick="document.getElementById('quantidade_gramado').disabled = !this.checked;">
											<label>Venda de Grama</label>
											<p><input type="text"  name="11" id="metragem_grama" maxlength="15" disabled="disabled" >
											<label>Metragem (m²)</label>
											<p><input type="checkbox"  name="12" id="serv_instala_grama" value="Sim" onclick="document.getElementById('quantidade_gramado').disabled = !this.checked;">
											<label>Serviço de instalação de Grama</label>
                                            <p><input type="text"  name="11" id="serv_metragem_grama" maxlength="15" disabled="disabled" >
											<label>Metragem (m²)</label>
									  <p><input type="checkbox"  name="13" id="venda_planta" value="Sim" onclick="document.getElementById('quantidade_planta').disabled = !this.checked;">
											<label>Venda de Plantas</label>
											<p><input type="text"  name="14" id="quantidade_planta" maxlength="15" disabled="disabled" >
											<label>Quantidade  (unidades)</label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="text"  name="14" id="quantidade_planta" maxlength="15" disabled="disabled" >
                                            <label>Espécie</label>
											<!---->
                                            
											<p><input type="checkbox"  name="15" id="serv_instala_planta" value="Sim" onclick="document.getElementById('quantidade_planta').disabled = !this.checked;">
											<label>Serviço de plantio de Plantas</label>
                                            <p><input type="text"  name="16" id="serv_quantidade_planta" maxlength="15" disabled="disabled" >
                                            <label>Quantidade</label>
                                            
                                            <p><input type="checkbox"  name="15" id="venda_artigo_jardim" value="Sim" onclick="document.getElementById('quantidade_planta').disabled = !this.checked;">
											<label>Venda de Artigos para Jardins  </label>
                                            <p><input type="text"  name="16" id="quantidade_artigo_jardim" maxlength="15" disabled="disabled" >
                                            <label>Quantidade</label>
									  <p><label>Descrição</label>
									    <br>
                                        <textarea name="16" required class="form-control" id="projeto" data-msg-required="Digite sua observação."></textarea>
                                        
                                        <p><input type="checkbox"  name="9" id="fertilizar_terra" value="Sim" >
											<label>Fertilizar terra/gramado</label>
                                         <p><input type="checkbox"  name="9" id="jardim_vertical" value="Sim" >
											<label>Jardins Verticais</label>   
                                         <p><input type="checkbox"  name="9" id="horta_vertical" value="Sim" >
											<label>Horta Verticais</label> 
                                         <p><input type="checkbox"  name="9" id="canteiros" value="Sim" >
											<label>Canteiros</label>        
									  </div>
                                      
									<div class="col-md-12">
                                    <label><h1>Qual o tipo de imóvel?</h1></label>
                                    <p><input type="checkbox"  name="9" id="apartamento" value="Sim" >
											<label>Apartamento</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;    
                                       <input type="checkbox"  name="9" id="casa" value="Sim" >
											<label>Casa</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;    
                                       <input type="checkbox"  name="9" id="comercial" value="Sim" >
											<label>Comercial</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;
                                       <input type="checkbox"  name="9" id="condominio" value="Sim" >
											<label>Condomínio</label>
                                       &nbsp;&nbsp;&nbsp;&nbsp;    
                                       <input type="checkbox"  name="9" id="outros" value="Sim" >
											<label>Outros</label>
                                    </div>
                                    <label><h1>Qual o tamanho do projeto?</h1></label>
									<div class="col-md-12">
                                    
                                    <p><input type="checkbox"  name="9" id="pequeno" value="Pequeno (menor que 50m²)" >
											<label>Pequeno (menor que 50m²)</label>
                                        
                                    <p><input type="checkbox"  name="9" id="medio" value="Médio (50 a 150m²)" >
											<label>Médio (50 a 150m²)</label>
                                       
                                    <p><input type="checkbox"  name="9" id="grande" value="Grande (entre 150 a 250m²)" >
											<label>Grande (entre 150 a 250m²)</label>
                                       
                                    <p><input type="checkbox"  name="9" id="mutio_grande" value="Muito Grande (a cima de 250m²)" >
											<label>Muito Grande (a cima de 250m²)</label>
                                    </div>
                                    
                                    
									<div class="col-md-12">
                                    <label><h1>Para quando você precisa deste serviço?</h1></label>
                                    <p><input type="checkbox"  name="9" id="q_antes_possivel" value="O quanto antes possível" >
											<label>O quanto antes possível</label>
                                        
                                    <p><input type="checkbox"  name="9" id="medio" value="Nos próximos 30 dias" >
											<label>Nos próximos 30 dias</label>
                                       
                                    <p><input type="checkbox"  name="9" id="grande" value="Nos próximos 3 meses" >
											<label>Nos próximos 3 meses</label>
                                       
                                    <p><input type="checkbox"  name="9" id="mutio_grande" value="Não tenho certeza" >
											<label>Não tenho certeza</label>
                                    </div>
                                    
                                    
									<div class="col-md-12">
                                    <label><h1>Inserir imagem do local</h1></label>
                                    <p><input type="file"  name="9" id="q_antes_possivel">
                                       <!--input type="submit" name="9" value="Salvar"-->
                                    </div>
                                    
                                    
									<div class="col-md-12">
                                    <label><h1>Descreva o que você precisa:</h1></label>
                                    <p><textarea name="16" required class="form-control" id="descricao_detalhe" data-msg-required="Descreva aqui" ></textarea>
                                    </div>
                                    
                                    <label><h1>Gostaria de agendar visita técnica?</h1></label>
									<div class="col-md-12">
                                    <p><input type="checkbox"  name="4" value="Sim">SIM
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="checkbox"  name="4" value="Nao">NÃO
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