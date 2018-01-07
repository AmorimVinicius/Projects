function validarLogin(){
	// constants
	var userGestor = "gestor";
	var passGestor = "gestor";
	var userAnalista = "analista";
	var passAnalista = "analista";
	var user = formLogin.user.value;
	var password = formLogin.password.value;
	var pesquisarnf = searchnf.pesquisarnf.value;
	
	if ((user == userAnalista) && (password == passAnalista)){
		window.open('cadastro.php');
		window.close('index.php');
	}
		else if ((user == userGestor) && (password == passGestor)){
			window.open('alteraPgto.php');
			window.close('index.php');
		}
			else{
				alert("Dados incorretos. Favor verificar.")
			}
	
	if (pesquisarnf == ""){
		alert("O campo de busca não deve estar em branco.")
	}
}

function chamaNewLogin(){
	var senhaGestor = window.prompt("Digite a senha do gestor: ");
	if (senhaGestor == "gestor"){
		window.open('newLogin.php');
		window.close('index.php');
	}else{
		alert("Senha incorreta. Favor verificar.");
	}
}

function enviarDados(){
	window.open('cadastro.php');
	window.close('registro.php');
}

function validarSenha(){
	
	var senha = formCadastroLogin.inputPasswordLogin.value;
	var confirmSenha = formCadastroLogin.inputPasswordConfirm.value;
	if (senha != confirmSenha){
		alert("As senhas não coincidem. Favor, verificar!");
		senha = "";
	}
	
}

function ocultar(){
	setTimeout(function(){
		$('.alert').fadeOut();
	}, 4);
}

/* Início data */
  $(function(){
	$("#calendarFat").datepicker({
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'
			],
		dayNamesMin: [
		'D','S','T','Q','Q','S','S','D'
		],
		dayNamesShort: [
		'Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'
		],
		monthNames: [  'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro',
		'Outubro','Novembro','Dezembro'
		],
		monthNamesShort: [
		'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set',
		'Out','Nov','Dez'
		],
		nextText: 'Próximo',
		prevText: 'Anterior'
		});
	});

	$(function(){
		$("#calendarPag").datepicker({
			dateFormat: 'dd/mm/yy',
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'
				],
			dayNamesMin: [
			'D','S','T','Q','Q','S','S','D'
			],
			dayNamesShort: [
			'Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'
			],
			monthNames: [  'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro',
			'Outubro','Novembro','Dezembro'
			],
			monthNamesShort: [
			'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set',
			'Out','Nov','Dez'
			],
			nextText: 'Próximo',
			prevText: 'Anterior'
			});
		});
/* Fim data */