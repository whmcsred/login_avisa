<?php
//Desenvolvido por Luciano Zanita :: http://whmcs.red
//Laravel DataBase
use WHMCS\Database\Capsule;

//Bloqueia o acesso direto ao arquivo
if (!defined("WHMCS")){
	die("Acesso restrito!");
}
//Função do Módulo
function login_avisa($vars){
	//Titulo do E-mail (nome da mensagem)
	$mensagem = "Nome unico do e-mail";
	//usuário do administrador
	$admin = "usuario";
	//////////////////////////////////////////////////////
	////////////NÃO MODIFICAR A PARTIR DAQUI//////////////
	//////////////////////////////////////////////////////
	//ID do usuário que esta logando
	$id = $vars['userid'];
	//Query para encontrar usuário
	$query = Capsule::table('tblclients')->WHERE('id', $id)->get();
	//Obtendo informações agora do usuário
	foreach ($query as $cliente) {
    	$primeiro_nome = $cliente->firstname;
    	$segundo_nome = $cliente->lastname;

	}
	//Usuario do administrador
	$administrador = $admin;
	//ID do Cliente
	$valores["id"] = $id;
	//Email a ser enviado (nome dele)
	$valores["messagename"] = $mensagem;
	//Comando a ser executado na função
	$comando = "sendemail";
	//Executa o envio para a API Local
	$executar = localAPI($comando, $valores, $administrador);
}
//Strings para o E-mail
function login_stringsemail($vars){
	$loginstring = array();
	//Obtendo informações do usuario que esta tentando logar
	$loginstring['ip'] = getenv("REMOTE_ADDR");
	$loginstring['hostname'] = gethostbyaddr($loginstring['ip']);
	//Pegar a data e horário atual
	$loginstring['data_atual'] = date("d/m/Y");
	$loginstring['horario'] = date("H:m");
	//Retorna valores
	return $loginstring;
}
//Adicionar Função ao Cliente logar do WHMCS
add_hook("ClientLogin",1,"login_avisa");
//Armazena as Strings para o email
add_hook("EmailPreSend",1,"login_stringsemail");
