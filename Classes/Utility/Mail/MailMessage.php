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
			return $this->to($addresses,$name);
		}

		/**
		 * @inheritDoc
		 */
		public function setBlindcopies(array $addresses): MailInterface
		{
			return $this->bcc($addresses);
		}

		/**
		 * @inheritDoc
		 */
		public function setReply(array $addresses): MailInterface
		{
			return $this->replyTo($addresses);
		}

		/**
		 * @inheritDoc
		 *
		 * Objects are ignored
		 */
		public function setContent(string $content, $objects = []): MailInterface
		{
			switch ($this->contentType)
			{
				case self::CONTENT_TYPE_HTML:
					$this->html($content);
					break;
				case self::CONTENT_TYPE_TEXT:
					$this->text(strip_tags($content));
					break;
				default:
					$this->html($content);
					$this->text(strip_tags($content));
					break;
			}
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
		public function setContentType(string $contentType): MailInterface
		{
			$this->contentType = $contentType;

			return $this;
		}
	}