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
	 * Interface MailInterface
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	interface MailInterface
	{
		/** @var string E-Mail Content-Type HTML */
		public const CONTENT_TYPE_HTML = 'html';

		/** @var string E-Mail Content-Type Text */
		public const CONTENT_TYPE_TEXT = 'text';

		/** @var string E-Mail Content-Type Both */
		public const CONTENT_TYPE_BOTH = 'both';

		/**
		 * @param array  $addresses
		 * @param string $name
		 *
		 * @return MailInterface
		 */
		public function setTo(array $addresses, string $name = ''): MailInterface;

		/**
		 * @param array  $addresses
		 * @param string $name
		 *
		 * @return MailInterface
		 */
		public function setRecipient(array $addresses, string $name = ''): MailInterface;

		/**
		 * @param array $addresses
		 *
		 * @return MailInterface
		 */
		public function setBlindcopies(array $addresses): MailInterface;

		/**
		 * @param string $subject
		 *
		 * @return MailInterface
		 */
		public function setSubject(string $subject): MailInterface;

		/**
		 * @param array  $addresses
		 * @param string $name
		 *
		 * @return MailInterface
		 */
		public function setFrom(array $addresses, $name = ''): MailInterface;

		/**
		 * @param array $addresses
		 *
		 * @return MailInterface
		 */
		public function setReply(array $addresses): MailInterface;

		/**
		 * @param string $content Generated Text accessible in fluid via text
		 * @param array  $objects Assigned Object for Fluid Template with index
		 *
		 * @return MailInterface
		 */
		public function setContent(string $content, array $objects = []): MailInterface;

		/**
		 * @param string $url
		 *
		 * @return MailInterface
		 */
		public function addAttachment(string $url): MailInterface;

		/**
		 * @return bool
		 */
		public function send(): bool;

		/**
		 * @param string $contentType MailInterface::CONTENT_TYPE_HTML | MailInterface::CONTENT_TYPE_TEXT | MailInterface::CONTENT_TYPE_BOTH
		 *
		 * @return MailInterface
		 */
		public function setContentType(string $contentType): MailInterface;

	}