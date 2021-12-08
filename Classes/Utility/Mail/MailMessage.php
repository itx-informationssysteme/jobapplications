<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2019
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

	namespace ITX\Jobapplications\Utility\Mail;

	/**
	 * Class MailMessage
	 *
	 * Implementation of Mail Interface and wrapper for \TYPO3\CMS\Core\Mail\MailMessage
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	class MailMessage extends \TYPO3\CMS\Core\Mail\MailMessage implements MailInterface
	{
		protected $contentType;

		/**
		 * @inheritDoc
		 */
		public function setRecipient(array $addresses, string $name = ''): MailInterface
		{
			return $this->to($addresses, $name);
		}

		/**
		 * Set the to addresses of this message.
		 *
		 * If multiple recipients will receive the message an array should be used.
		 * Example: array('receiver@domain.org', 'other@domain.org' => 'A name')
		 *
		 * If $name is passed and the first parameter is a string, this name will be
		 * associated with the address.
		 *
		 * @param string|array $addresses
		 * @param string       $name optional
		 *
		 * @return \TYPO3\CMS\Core\Mail\MailMessage
		 */
		public function to($addresses, $name = ''): MailInterface
		{
			$this->setTo($addresses, $name);

			return $this;
		}

		/**
		 * Set the from address of this message.
		 *
		 * You may pass an array of addresses if this message is from multiple people.
		 *
		 * If $name is passed and the first parameter is a string, this name will be
		 * associated with the address.
		 *
		 * @param string|array $addresses
		 * @param string       $name optional
		 *
		 * @return \TYPO3\CMS\Core\Mail\MailMessage
		 */
		public function from($addresses, $name = ''): MailInterface
		{
			$this->setFrom($addresses, $name);

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function setBlindcopies(array $addresses): MailInterface
		{
			$this->setBcc($addresses);

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function setReply(array $addresses): MailInterface
		{
			$this->setReplyTo($addresses);

			return $this;
		}

		/**
		 * @inheritDoc
		 *
		 * Objects and Content Type are ignored
		 */
		public function setContent(string $content, $objects = []): MailInterface
		{
			$this->setBody($content);

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function addAttachment(string $url): MailInterface
		{
			return $this->attach(\Swift_Attachment::fromPath($url));
		}

		/**
		 * @inheritDoc
		 */
		public function setContentType($contentType): MailInterface
		{
			switch ($contentType)
			{
				case self::CONTENT_TYPE_TEXT:
					parent::setContentType('text/plain');
					break;
				default:
					parent::setContentType('text/html');
					break;
			}

			return $this;
		}

		/**
		 * Set the subject of this message.
		 *
		 * @param string $subject
		 *
		 * @return $this
		 */
		public function addSubject($subject): MailInterface
		{
			$this->setSubject($subject);

			return $this;
		}

		public function send(): bool
		{
			$result = parent::send();

			return $result !== 0;
		}
	}