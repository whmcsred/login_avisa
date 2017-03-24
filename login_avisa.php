<?php
//Desenvolvido por Luciano Zanita :: http://whmcs.red
//Laravel DataBase
use WHMCS\Database\Capsule;
//Bloqueia o acesso direto ao arquivo
if (!defined("WHMCS")){
	die("Acesso restrito!");
}

//Aviso para cliente
function login_avisa_cliente($vars){

	$administrador = "lucianozanita";
	$mensagem = "Aviso de Login";
	$receberaviso = "6";

	//////////////////////////////////////////////////////
	////////////NÃO MODIFICAR A PARTIR DAQUI//////////////
	//////////////////////////////////////////////////////

	//ID do usuário que esta logando
	$id = $vars['userid'];
	//ID do Cliente
	$valores["id"] = $id;
	//Email a ser enviado (nome dele)
	$valores["messagename"] = $mensagem;
	//Comando a ser executado na função
	$comando = "sendemail";
	//Verifica se não é o Admin que logou na conta do cliente
	if($_SESSION['adminid']==''){
			//caso não tenha sido ele executa o comando.
		//Verifica se o usuário quer mesmo receber o aviso
		foreach (Capsule::table('tblcustomfieldsvalues')->WHERE('fieldid', $receberaviso)->WHERE('relid', $id)->get() as $aviso){
			$configusuario = $aviso->value;
		}
		//Compara se quer receber mesmo o e-mail
		if($configusuario=='Sim'){
			//Executa o envio para a API Local
			$executar = localAPI($comando, $valores, $administrador);
			//Verifica se o banco de dados ja existe, se não existir ja cria-o
			$pdo = Capsule::connection()->getPdo();
			$pdo->beginTransaction();
			$pdo->query("CREATE TABLE IF NOT EXISTS `tblloginavisa` (`id` int(11) NOT NULL AUTO_INCREMENT, `hash` varchar(255) NOT NULL, `data` varchar(255) NOT NULL, `horario` varchar(255) NOT NULL, `usuario` varchar(255) NOT NULL, `ip` varchar(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
			//Obtendo informações agora do usuário
			foreach (Capsule::table('tblclients')->WHERE('id', $id)->get() as $cliente){
				$primeiro_nome = $cliente->firstname;
		    	$emailusuario = $cliente->email;
			}
			//Configurações Base.
			$dataatual = date("d/m/Y");
			$horarioatual = date("H:i:s");
			$ipusuario = getenv("REMOTE_ADDR");
			//Preparando o MD5
			$preparomd5 = "".$emailusuario."".$id."".$dataatual."".$horarioatual."";
			//Fazendo o MD5
			$md5 = md5($preparomd5);
			//registrando no banco de dados
			Capsule::table('tblloginavisa')->insert(['hash' => $md5, 'data' => $dataatual, 'horario' => $horarioatual, 'usuario' => $id, 'ip' => $ipusuario,]);
		}
	}
	else{
		//Caso tenha sido ele não executa nada aqui
	}
}

//Aviso para Administrador
function login_avisa_admin($vars){

	$adminsitrador = "lucianozanita";
	$mensagem = "Aviso de Login Admin";

	//////////////////////////////////////////////////////
	////////////NÃO MODIFICAR A PARTIR DAQUI//////////////
	//////////////////////////////////////////////////////

	//Atribuindo o administrador
	$valores["id"] = $vars['adminid'];
	//Montagem do Email a ser enviado
	$valores["messagename"] = $mensagem;
	//Comando a ser executado na função
	$comando = "Send_Admin_Email";
	//Executa o envio para a API Local
	$executar = localAPI($comando, $valores, $administrador);
}

//Strings para E-mail
function login_avisa_email($vars){
	//Pegando URL do site
	foreach (Capsule::table('tblconfiguration')->WHERE('setting', 'SystemURL')->get() as $system){
	    $urlsistema = $system->value;
	}
	//ID do usuário
	$id = $_SESSION['uid'];
	//Cria a Array
	$login_avisa = array();
	$login_avisa['ip'] = getenv("REMOTE_ADDR");
	$login_avisa['hostname'] = gethostbyaddr($login_avisa['ip']);
		//Tratando o JSON DECODE
		$obterjson = file_get_contents("http://ip-api.com/json/".$login_avisa['ip']."");
		$json = json_decode($obterjson);
	$login_avisa['pais'] = $json->country;
	$login_avisa['pais_codigo'] = $json->countryCode;
	$login_avisa['estado_codigo'] = $json->region;
	$login_avisa['estado'] = $json->regionName;
	$login_avisa['cidade'] = $json->city;
	$login_avisa['cep'] = $json->zip;
	$login_avisa['isp'] = $json->isp;
	$login_avisa['organizacao'] = $json->org;
	$login_avisa['as'] = $json->as;
	$login_avisa['latitude'] = $json->lat;
	$login_avisa['longitude'] = $json->lon;
	$login_avisa['data_atual'] = date("d/m/Y");
	$login_avisa['horario'] = date("H:i");
		//Montando a hash
		foreach (Capsule::table('tblclients')->WHERE('id', $id)->get() as $usuario){
			$emailusuario = $usuario->email;
		}
		$dataatual = date("d/m/Y");
		$horaatual = date("H:i:s");
		$preparomd5 = "".$emailusuario."".$id."".$dataatual."".$horaatual."";
		$md5 = md5($preparomd5);
	$login_avisa['hashloginavisa'] = $md5;
	$login_avisa['hashloginavisa_link'] = "".$urlsistema."clientarea.php?login-avisa=".$md5."";

	//retorna os valores
	return $login_avisa;
}
//Bloqueio de Usuário
function login_avisa_bloqueio($vars){
	//Recebe a funcão
	$funcao = $_GET['login-avisa'];
	if($funcao){
		//Consulta se existe
		foreach (Capsule::table('tblloginavisa')->WHERE('hash', $funcao)->get() as $loginavisahash){
			$ipusuario = $loginavisahash->ip;
			$usuario = $loginavisahash->usuario;
			$data = $loginavisahash->data;
			$horario = $loginavisahash->horario;
		}
		//verifica se existe o ip do usuario
		if($ipusuario){
			function bloquear_login_avisa($vars){
				$funcao = $_GET['login-avisa'];
				foreach (Capsule::table('tblloginavisa')->WHERE('hash', $funcao)->get() as $loginavisahash){
					$ipusuario = $loginavisahash->ip;
					$usuario = $loginavisahash->usuario;
					$data = $loginavisahash->data;
					$horario = $loginavisahash->horario;
				}
				foreach (Capsule::table('tblclients')->WHERE('id', $id)->get() as $usuariodados){
					$primeironome = $usuariodados->firstname;
				}
				//Montando quem bloqueou
				$bloqueioreason = "Você foi bloqueado por questões de segurança, login não reconhecido, por favor caso for um erro contacte o suporte via telefone ou e-mail. (#".$usuario.")";
				//Montando horario para banimento
				$databloqueio = date('Y-m-d H:i:s', strtotime('+1 day'));;
				//insere o bloqueio
				Capsule::table('tblbannedips')->insert(['ip' => $ipusuario, 'reason' => $bloqueioreason, 'expires' => $databloqueio,]);
				$unirdatahorainfo = date('d/m/Y H:i:s', strtotime('+1 day'));;
				//cria o resultado em modal
				return '<div id="bloqueiologinavisa" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title"><i class="fa fa-lock" aria-hidden="true"></i> Login Avisa</h4>
				      </div>
				      <div class="modal-body">
						<h2><center><i class="fa fa-check-square" aria-hidden="true"></i> Acesso Bloqueado com Sucesso!</center></h2>
						<br/>
						<p>O acesso não identificado em sua conta foi bloqueado, confira algumas informações logo a baixo:</p>
						<p>IP Bloqueado: '.$ipusuario.'</p>
						<p>Expiração: '.$unirdatahorainfo.'</p>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				      </div>
				    </div>
				  </div>
				</div>
				<script>
					$(document).ready(function(){
					$("#bloqueiologinavisa").modal("show");
					});
				</script>';
			}
			//Adiciona o código na área certa da página para exibição
			add_hook("ClientAreaFooterOutput",1,"bloquear_login_avisa");
		}
	}
}
//Adicionando os Hook
add_hook("ClientLogin",1,"login_avisa_cliente");
add_hook("AdminLogin",1,"login_avisa_admin");
add_hook("EmailPreSend",1,"login_avisa_email");
add_hook("ClientAreaPageLogin",1,"login_avisa_bloqueio");
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
 $versao = "0.4";
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
