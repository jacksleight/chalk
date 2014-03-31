<?php
namespace App;

class Contact extends \Js\Entity
{
	protected $name;
	protected $emailAddress;
	protected $subject;
	protected $message;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'name' => array(
					'type'		=> 'string',
				),
				'emailAddress' => array(
					'type'		=> 'string',
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator\EmailAddress(),
					)),
				),
				'subject' => array(
					'type'		=> 'string',
					'values'	=> array('sales', 'billing', 'support'),
				),
				'message' => array(
					'type'		=> 'text',
				),
			),
		);
	}

	public function sendEmail()
	{
		$emailAddress = static::$_helper->config->emailAddress;
		$data = array_merge($this->toArray(), array(
			'dateTime' => static::$_helper->locale->dateTime(new \DateTime()),
		));

		$body = array();
		foreach ($data as $name => $value) {
			$body[static::$_helper->locale->message("label_{$name}")] = $value;
		}
		$body = \Js\array_implode_pairs("\n\n", ":\n\t", $body);

		$message = Swift_Message::newInstance()
			->setSubject($this->subject)
			->setFrom($emailAddress)
			->setReplyTo($this->emailAddress, $this->name)
			->setTo($emailAddress)
			->setBody($this->message);
		static::$_helper->swift->send($message);
	}
}
