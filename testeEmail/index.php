<?php
error_reporting( E_ALL );
ini_set('display_errors', 1);

include "bibliotecas/PHPMailer/PHPMailerAutoload.php";
// Conexão ao banco de dados (MySQL)
// E-mail para notificação
define("EMAIL_NOTIFICACAO", "emaildestinatario");
function preparar_corpo_email($tarefa,$anexos= array())
{
	ob_start();
	include "template_email.php";
	$corpo = ob_get_contents();
	ob_end_clean();
	return $corpo;
}

function enviar_email($tarefa,$anexos= array())
{
	$email = new PHPMailer(true); // Esta é a criação do objeto
	$email->isSMTP();
	$email->SMTPDebug = 2;
	$email->Host = "smtp.gmail.com";
	$email->Port = 465;
	$email->SMTPSecure = 'ssl';
	$email->SMTPAuth = true;
	$email->Username = "emailremetente";
	$email->Password = "senharemetente";
	$mail->SingleTo = true;
	$email->setFrom("emailremetente", "Avisador de Tarefas");
	// Digitar o e-mail do destinatário;
	$email->addAddress(EMAIL_NOTIFICACAO);
	// Digitar o assunto do e-mail;
	$email->Subject = "Aviso de tarefa: {$tarefa['nome']}";
	// Escrever o corpo do e-mail;
	$corpo = preparar_corpo_email($tarefa, $anexos);
	$email->msgHTML($corpo);
	foreach ($anexos as $anexo) {
		$email->addAttachment("anexos/{$anexo['arquivo']}");
	}
	$email->send();
	echo 'enviou!';
}

if (isset($_POST['biscuits'])){
	$tarefa = array(
		'nome' => "Tarefa de teste",
		'concluida' => "Ainda não",
		'descricao' =>
		'Descrição de teste',
		'prazo' => "Prazo de Teste",
		'prioridade' => "Pouca Prioridade");
	enviar_email($tarefa);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Envio de Email</title>
</head>
<body>
	<form method="POST" action="index.php">
		<input type="hidden" name="biscuits" value="this biscuits">
		<button type="submit">Enviar Email</button>
	</form>
</body>
</html>
