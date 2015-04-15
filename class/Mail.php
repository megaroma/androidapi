<?php
class Mail {
	public static $from = "MobileAPI <noreply@wolverine.com>";
	public static $reply_to = "Admin <admin@wolverine.com>";

	public static function send($to, $subject, $text) {

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		$headers[] = "From: ".self::$from;
		$headers[] = "Reply-To: ".self::$reply_to;
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();

		mail($to, $subject, $text, implode("\r\n", $headers));

	}

}