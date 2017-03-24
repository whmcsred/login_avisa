# Login Avisa - WHMCS.RED
Login Avisa desenvolvido pela WHMCS.RED vem com o proposito de ao cliente logar em sua conta no WHMCS ser avisado via e-mail como os dados nocivos como IP, Hostname e data completa com horário do login, podendo assim identificar facilmente qualquer login não permitido na conta. <br/>

# Requisitos para instalar
Para funcionar você deverá ter: <br/>
- WHMCS 7 ou superior <br/>
- PHP 5.6 ou superior <br/>
- PDO e Mysqli instalados <br/>
- Template de e-mail criado (Criar em opções->Modelos de Email <br/>

# Como instalar
Para instalar é muito simples, vamos começar por algumas configurações básicas <br/>
Lembre-se de ter em mãos algumas informações: <br/>
- Usuário administrador do whmcs <br/>
- Acesso ao phpmyadmin de seu banco de dados do whmcs <br/>

Você precisa estar criando seu e-mail personalizado para isto vá até: Opções -> Modelos de E-mail<br/>
Para o alerta de login a clientes você pode estar cadastrando diretamente um personalizado na categoria Geral, para administrador será necessário uma pequena mudança, você deverá também criar na Categoria Geral, mas logo após deverá acessar seu banco de dados na tabela:<br/>
tblemailtemplates<br/>
Após encontrar a tabela procure pelo email cadastrado, e altere o definido para aviso de login para administradores, altere o type de "general" para "admin", assim seu modelo de e-mail estará acessivel, lembrando que você deve efetuar essa mudança apenas para o template de email para o aviso de login a administrador!<br/>
As TAGs personalizadas descritas acima funcionaram da mesma forma ao email de administrador.<br/>
<br/>
Após ter feito a configuração do e-mail devemos estar criando um campo personalizavel para preenchimento do cliente se ele deseja alerta de login no e-mail ou não. Para isto vamos até: <br/>
- Opções -> Campos Personalizados <br/>
Após acessar adicione um novo campo com as seguintes informações: <br/>
- Nome do Campo: Notificação de Login (você pode alterar) <br/>
- Tipo do Campo: Lista de Opções <br/>
- Descrição: Descreva da forma que bem entender <br/>
- Selecionar Opções: Sim, Não                (Não alterar) <br/>
<Br/>
Agora que você já criou eu campo personalizado você precisa descobrir qual é o ID dele, existe algumas maneiras fácil de você poder fazer isso, para isso vá até seu phpmyadmin de seu banco de dados do whmcs, e va até a tabela: tblcustomfields<br/>
Após isso procure pelo "fieldname" o nome do campo que você criou e na mesma linha identifique o ID, esse será seu ID do campo, use ele para configurar o arquivo do login avisa logo a baixo a explicação.<br/>
<br/>
Agora vamos configurar o arquivo login_avisa.php<br/>
Linhas para editar: <br/>
- Linha 13: Informe qual é o usuário administrador do WHMCS <br/>
- Linha 14: Informe qual é o nome único de seu template de e-mail criado para alerta de login a clientes <br/>
- Linha 15: Informe qual é o ID do campo personalizado criado para receber alerta ou não <br/>
- Linha 69: Informe o usuário administrador de seu whmcs (é necessário para a API) <br/>
- Linha 70: Informe qual é o nome único de seu template de e-mail criado para alertas a administradores </br>
Após edita-lo você deverá enviar para /includes/hooks/ <br/>

# Informações para criação do E-mail personalizado
Para você exibir no e-mail personalizado que você criou os campos como IP, Hostname, Data e Horário você deverá usar as seguintes TAG's. <br/>
- Para exibir o IP: {$ip} <br/>
- Para exibir o Hostname: {$hostname} <br/>
- Para exibir a Data: {$data_atual} <br/>
- Para exibir o Horário: {$horario} <br/>
- Para exibir o Pais: {$pais} <br/>
- Para exibir código do Pais: {$pais_codigo} <br/>
- Para exibir o estado: {$estado} <br/>
- Para exibir código do estado: {$estado_codigo} <br/>
- Para exibir a cidade: {$cidade} <br/>
- Para exibir o cep: {$cep} <br/>
- Para exibir o ISP: {$isp} <br/>
- Para exibir a organização associada ao ip: {$organizacao} <br/>
- Para exibir a AS do ip: {$as} <br/>
- Para exibir latitude: {$latitude} <br/>
- Para exibir longitude: {$longitude} <br/>
- Para exibir apenas a hash de bloqueio: {$hashloginavisa} <br/>
- Para exibir link completo para bloqueio: {$hashloginavisa_link} <br/>

# Considerações
Espero que seja útil para seu dia a dia, caso tenha dúvidas convido a conhecer nosso fórum também: http://forum.whmcs.red <br/>
Caso desejar conhecer novos módulos, notícias e tutoriais acesse: http://whmcs.red <br/>

Módulo desenvolvido por Luciano Zanita - WHMCS.RED <br/>

