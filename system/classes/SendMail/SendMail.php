<?php

/**
 * ����� ������������ ��� �������� ����� � ����������
 * @created   by PhpStorm
 * @package   SendMail.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     14.05.2015
 * @time      :     15:47
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


/**
 * Class SendMail
 */
class SendMail
{

	const CONTENT_TYPE_PLAIN = 1;
	const CONTENT_TYPE_HTML  = 2;

	const CONTENT_ENCODING_BASE64            = 1;
	const CONTENT_ENCODING_QUOTED_PRINTTABLE = 2;

	private $_params = [

		'email'            => '',
		'from_name'        => '',
		'from_email'       => '',
		'subject'          => '��� ����',
		'message'          => '',
		'notify'           => false,
		'priority'         => '3 (Normal)',
		'files'            => [],
		'charset'          => 'utf-8',
		'content_type'     => 'plain',
		'content_encoding' => 'quoted-printable',
		'time_limit'       => 30

	];


	private $_rgContentType = [
		1 => 'plain',
		2 => 'html'
	];

	private $_rgContentEncoding = [
		1 => 'base64',
		2 => 'quoted-printable'
	];


	private $_error = true;
	private $_error_text = '<br><span style="color:#F00;">';


	/**
	 * ����� � ��� �����������.
	 *
	 * @param        $fromEmail
	 * @param string $fromName
	 */
	public function __construct($fromEmail, $fromName = '')
		{
			$this->_params['from_email'] = $fromEmail;
			$this->_params['from_name'] = $fromName;
		}


	/**
	 * ����� � ��� �����������.
	 *
	 * @param        $email
	 * @param string $name
	 *
	 * @return SendMail
	 */
	public static function from($email, $name = '')
		{
			return new self($email, $name);
		}


	/**
	 * ������� (����� ������ �������).
	 *
	 * @param        $email
	 * @param string $name
	 *
	 * @return $this
	 */
	public function to($email, $name = '')
		{
			$this->_params['email'] = is_array($email) ? $email : [[$email, $name]];

			return $this;
		}


	/**
	 * ���� ���������.
	 */
	public function subject($subject)
		{
			$this->_params['subject'] = $subject;

			return $this;
		}


	/**
	 * ���� ���������.
	 */
	public function message($message)
		{
			$this->_params['message'] = $message;

			return $this;
		}


	/**
	 * ���� �� �������������� ����� (����� ������).
	 *
	 * @param $files
	 *
	 * @return $this
	 */
	public function files($files)
		{
			$this->_params['files'] = is_array($files) ? $files : [$files];

			return $this;
		}


	/**
	 * ��������� (�� ��������� utf-8).
	 *
	 * @param string $charset
	 *
	 * @return $this
	 */
	public function charset($charset = 'utf-8')
		{
			$this->_params['charset'] = $charset;

			return $this;
		}


	/**
	 * ��������� ������.
	 * true, ���� ������. �� ��������� false.
	 *
	 * @param bool $important
	 *
	 * @return $this
	 */
	public function important($important = false)
		{
			if ($important) {
				$this->_params['priority'] = '1 (High)';
			}

			return $this;
		}


	/**
	 * ���������� � ���������.
	 * �� ��������� false.
	 *
	 * @param bool $notify
	 *
	 * @return $this
	 */
	public function notify($notify = false)
		{
			$this->_params['notify'] = $notify;

			return $this;
		}


	/**
	 * ��� ��������� (�� ��������� text/plain).
	 *
	 * @param int $content_type
	 *
	 * @return $this
	 */
	public function content_type($content_type = 1)
		{
			$this->_params['content_type'] = (!isset($this->_rgContentType[$content_type]) ? $this->_rgContentType[1] :
				$this->_rgContentType[$content_type]);

			return $this;
		}


	/**
	 * ��� ����������� ��������� (�� ��������� 'quoted-printable').
	 *
	 * @param int $encoding
	 *
	 * @return $this
	 */
	public function content_encoding($encoding = 2)
		{
			$this->_params['content_encoding'] =
				(!isset($this->_rgContentEncoding[$encoding]) ? $this->_rgContentEncoding[2] :
					$this->_rgContentEncoding[$encoding]);

			return $this;
		}


	/**
	 * set_time_limit (�� ��������� == 30�.).
	 *
	 * @param int $time_limit
	 *
	 * @return $this
	 */
	public function time_limit($time_limit = 30)
		{
			$this->_params['time_limit'] = $time_limit;

			return $this;
		}


	/**
	 * �������� �����.
	 */
	public function send()
		{
			if ($this->_error_email() === false) {
				throw new Exception($this->_error_text);
			} else {
				$this->_send();
			}
		}

	/**
	 * @return bool
	 */
	private function _error_email()
		{
			if (empty($this->_params['email'])) {
				$this->_error = false;
				$this->_error_text .= '�� ������ ����� ����������: email()<br>';
			}

			if (empty($this->_params['from_email'])) {
				$this->_error = false;
				$this->_error_text .= '�� ������ ����� �����������: from($email, [$name])<br>';
			}

			$this->_error_text .= '</span>';

			return $this->_error;
		}

	/**
	 * @throws Exception
	 */
	private function _send()
		{
			if ($this->_params['from_name']) {
				$this->_params['from_name'] = $this->_encodeMimeheader($this->_params['from_name']);
				$header = "From: ".$this->_params['from_name']." <".$this->_params['from_email'].">\r\n";

				if ($this->_params['notify']) {
					$header .=
						"Disposition-Notification-To: ".$this->_params['from_name']." <".$this->_params['from_email'].
						">\r\n";
				}
			} else {
				$header = "From: ".$this->_params['from_email']."\r\n";

				if ($this->_params['notify']) {
					$header .= "Disposition-Notification-To: ".$this->_params['from_email']."\r\n";
				}
			}

			$header .= ("Reply-To: ".$this->_params['from_email']."\r\n"."X-Priority: ".$this->_params['priority'].
						"\r\n"."MIME-Version: 1.0\r\n");


			// ���� ���� ������������ �����
			if (!empty($this->_params['files'])) {
				$bound = md5(uniqid(time())); // �����������

				$header .= ("Content-Type: multipart/mixed; boundary=\"".$bound."\"\r\n".
							"This is a multi-part message in MIME format.\r\n");

				$message = ("--".$bound."\r\n"."Content-Type: text/".$this->_params['content_type']."; charset=".
							$this->_params['charset']."\r\n"."Content-Transfer-Encoding: ".
							$this->_params['content_encoding']."\r\n\r\n".
							$this->_strEncoding($this->_params['message'])."\r\n\r\n");

				$finfo = null;

				if (function_exists("finfo_open") && function_exists("finfo_file")) {
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
				}

				foreach ($this->_params['files'] as $file_name) {
					if (!file_exists($file_name)) {
						throw new Exception('���� <b>'.$file_name.'</b> �� ������!');
					}

					$mimeType = $finfo ? finfo_file($finfo, $file_name) :
						(function_exists('mime_content_type') ? mime_content_type($file_name) :
							'application/octet-stream');

					$name = preg_replace('~.*([^/|\\\]+)$~U', '$1', $file_name);
					$name = $this->_encodeMimeheader(iconv('cp1251', 'UTF-8', $name));

					$message .= ("--".$bound."\r\n"."Content-Type: ".$mimeType."; name=".$name."\r\n".
								 "Content-Transfer-Encoding: base64\r\n"."Content-Disposition: attachment; filename=\"".
								 $name."\"; size=".filesize($file_name).";\r\n\r\n".
								 chunk_split(base64_encode(file_get_contents($file_name)))."\r\n");
				}

				$message .= $bound."--";

				if ($finfo) {
					finfo_close($finfo);
				}
			} else // ���� ��� ������
			{
				$header .= ("Content-type: text/".$this->_params['content_type']."; charset=".$this->_params['charset'].
							"\r\n"."Content-Transfer-Encoding: ".$this->_params['content_encoding']."\r\n");

				$message = $this->_strEncoding($this->_params['message']);
			}

			$this->_params['subject'] = $this->_encodeMimeheader($this->_params['subject']);
			set_time_limit($this->_params['time_limit']);

			// �������� ���������
			foreach ($this->_params['email'] as $email) {
				if (is_array($email)) {
					$email = empty($email[1]) ? $email[0] : $this->_encodeMimeheader($email[1]).' <'.$email[0].'>';
				}

				@mail($email, $this->_params['subject'], $message, $header);
			}
		}

	/**
	 * @param $str
	 *
	 * @return string
	 * @throws Exception
	 */
	private function _strEncoding($str)
		{
			if ($this->_params['content_encoding'] == 'base64') {
				return base64_encode($str);
			}

			if ($this->_params['content_encoding'] == 'quoted-printable') {
				if (function_exists('quoted_printable_encode')) {
					return quoted_printable_encode($str);
				}

				if (function_exists('imap_8bit')) {
					return imap_8bit($str);
				}

				throw new Exception('��� ����������� ������ � quoted-printable ��������� ���� �� 2� �������:<br>'.
									'quoted_printable_encode<br>imap_8bit<br><br>'.
									'� ��������� ������ ������������� ��������� � base64.<br>'.'��������� '.__CLASS__.
									'::CONTENT_ENCODING_BASE64');
			}

			return false;
		}

	/**
	 * @param $str
	 *
	 * @return string
	 */
	private function _encodeMimeheader($str)
		{
			return '=?'.$this->_params['charset'].'?B?'.base64_encode($str).'?=';
		}
}