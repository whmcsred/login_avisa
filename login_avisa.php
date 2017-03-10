<?php
//Desenvolvido por Luciano Zanita :: http://whmcs.red
//Laravel DataBase
use WHMCS\Database\Capsule;
//Bloqueia o acesso direto ao arquivo
if (!defined("WHMCS")){
	die("Acesso restrito!");
}
//Função do Módulo
function login_avisa_cliente($vars){

	//Titulo do E-mail (nome da mensagem)
	$mensagem = "Nome único do email";
	//usuário do administrador
	$adminsys = "Usuário Administrador";

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
	$administrador = $adminsys;
	//ID do Cliente
	$valores["id"] = $id;
	//Email a ser enviado (nome dele)
	$valores["messagename"] = $mensagem;
	//Comando a ser executado na função
	$comando = "sendemail";
	//Verifica se não é o Admin que logou na conta do cliente
	if($_SESSION['adminid']==''){
			//caso não tenha sido ele executa o comando.
		//Executa o envio para a API Local
		$executar = localAPI($comando, $valores, $administrador);
	}
	else{
		//Caso tenha sido ele não executa nada aqui.
	}

}

//Função do Módulo
function login_avisa_admin($vars){

	//Titulo do E-mail (nome da mensagem)
	$mensagem = "Nome único do email";
	//usuário do administrador
	$adminsys = "Usuário Administrador";

	//////////////////////////////////////////////////////
	////////////NÃO MODIFICAR A PARTIR DAQUI//////////////
	//////////////////////////////////////////////////////

	//Query para encontrar usuário
	$query = Capsule::table('tbladmins')->WHERE('id', $vars['adminid'])->get();
	//Obtendo informações agora do usuário
	foreach ($query as $admin) {
		$usuarioadmin = $admin->username;
    	$primeiro_nome = $admin->firstname;
    	$segundo_nome = $admin->lastname;
	}
	//Montagem do Email a ser enviado
	$valores["messagename"] = $mensagem;
	//Comando a ser executado na função
	$comando = "Send_Admin_Email";
	//Executa o envio para a API Local
	$executar = localAPI($comando, $valores, $adminsys);
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


//Adicionando Widget ao Admin Dashboard
add_hook('AdminHomeWidgets', 1, function() {
    return new LoginAvisa();
});

//Montagem do Wideget
class LoginAvisa extends \WHMCS\Module\AbstractWidget
{
    protected $title = 'WHMCS.RED - Login Avisa';
    protected $description = '';
    protected $weight = 150;
    protected $columns = 1;
    protected $cache = false;
    protected $cacheExpiry = 120;
    protected $requiredPermission = '';

    public function getData()
    {
        return array();
    }

    public function generateOutput($data)
    {

 $versao = "0.3";
 $versaodisponivel = file_get_contents("http://whmcs.red/versao/loginavisa.txt");

$codigo = '<div class="icon-stats">
    <div class="row">
        <div class="col-sm-6">
            <div class="item">
                <div class="icon-holder text-center color-orange">
                        <i class="fa fa-wrench"></i>
                </div>
                <div class="data">
                    <div class="note">
                        <a href="clients.php?status=Active">Versão Atual</a>
                    </div>
                    <div class="number">
                            <span class="color-orange">'.$versao.'</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="item">
                <div class="icon-holder text-center color-green">
                    <i class="fa fa-wrench"></i>
                </div>
                <div class="data">
                    <div class="note">
                        Versão Disponível
                    </div>
                    <div class="number">
                        <span class="color-green">'.$versaodisponivel.'</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
 //Checa As versões
 if($versao != $versaodisponivel){ $codigo .= '<center><a href="http://whmcs.red/hook-login-avisa/" target="_new" class="btn btn-danger"><i class="fa fa-download" aria-hidden="true"></i> Baixar Atualizações</a></center><br/>'; }

 		//retorna o codigo
        return $codigo;
    }
}


//Adicionar Função ao Cliente e ou Admin logar do WHMCS
add_hook("ClientLogin",1,"login_avisa_cliente");
add_hook("AdminLogin",1,"login_avisa_admin");

//Armazena as Strings para o email
add_hook("EmailPreSend",1,"login_stringsemail");

