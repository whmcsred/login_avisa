# Login Avisa - WHMCS.RED
Login Avisa desenvolvido pela WHMCS.RED vem com o proposito de ao cliente logar em sua conta no WHMCS ser avisado via e-mail como os dados nocivos como IP, Hostname e data completa com horário do login, podendo assim identificar facilmente qualquer login não permitido na conta. <br/>

# Requisitos para instalar
Para funcionar você deverá ter: <br/>
- WHMCS 7 ou superior <br/>
- PHP 5.6 ou superior <br/>
- PDO e Mysqli instalados <br/>
- Template de e-mail criado (Criar em opções->Modelos de Email <br/>

# Como instalar
Para instalar é muito simples, antes de tudo edite seu arquivo (login_avisa.php)! <br/>
Linhas para editar: <br/>
- Linha 13: Informe qual é o nome único de seu template de e-mail criado para alerta de login a clientes <br/>
- Linha 15: Informe o usuário administrador de seu whmcs (é necessário para a API) <br/>
- Linha 54: Informe qual é o nome único de seu template de e-mail criado para alertas a administradores <br/>
- Linha 56: Informe o usuário administrador de seu whmcs (é necessário para a API) </br>
Após edita-lo você deverá enviar para /includes/hooks/ <br/>

# Informações para criação do E-mail personalizado
Para você exibir no e-mail personalizado que você criou os campos como IP, Hostname, Data e Horário você deverá usar as seguintes TAG's. <br/>
- Para exibir o IP: {$ip} <br/>
- Para exibir o Hostname: {$hostname} <br/>
- Para exibir a Data: {$data_atual} <br/>
- Para exibir o Horário: {$horario} <br/>

Para você estar criando seu e-mail personalizado vá até: Opções -> Modelos de E-mail<br/>
Para o alerta de login a clientes você pode estar cadastrando diretamente um personalizado na categoria Geral, para administrador será necessário uma pequena mudança, você deverá também criar na Categoria Geral, mas logo após deverá acessar seu banco de dados na tabela:<br/>
tblemailtemplates<br/>
Após encontrar a tabela procure pelo email cadastrado, e altere o definido para aviso de login para administradores, altere o type de "general" para "admin", assim seu modelo de e-mail estará acessivel, lembrando que você deve efetuar essa mudança apenas para o template de email para o aviso de login a administrador!<br/>
As TAGs personalizadas descritas acima funcionaram da mesma forma ao email de administrador.<br/>

# Considerações
Espero que seja útil para seu dia a dia, caso tenha dúvidas convido a conhecer nosso fórum também: http://forum.whmcs.red <br/>
Caso desejar conhecer novos módulos, notícias e tutoriais acesse: http://whmcs.red <br/>

Módulo desenvolvido por Luciano Zanita - WHMCS.RED <br/>
