<?
require("PHPMailer.php" );

class PHPMailSender {

	static public function sendMail($recipient, $subject, $body) {

		$ret = new StdClass;

		if ( empty($recipient) || empty($subject) || empty($body)) {
			$ret->status = 0;
			$ret->info = "Some fields are empty.";
			return $ret;
		}
		if ( empty($recipient->name) || empty($recipient->addr) ) {
			$ret->status = 0;
			$ret->info = "Format not correct.";
			return $ret;
		}

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "mail.xyzpedia.org";
		$mail->SMTPAuth = true;
		#$mail->Username = "admin@xyzpedia.org";
		$mail->Username = "root";
		$mail->Password = "410475438";

		$mail->From = "admin@xyzpedia.org";
		$mail->FromName = "小宇宙百科";
		$mail->AddAddress($recipient->addr, $recipient->name);
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz");
		$mail->IsHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $body;
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

		if( $mail->Send() ) {
			$ret->status = 1;
		} else {
			$ret->status = 0;
			$ret->info = $mail->ErrorInfo;
		}
		return $ret;
	}
}
?>
