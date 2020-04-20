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

	use Symfony\Component\Mailer\Exception\TransportException;
	use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
	use Symfony\Component\Mime\Address;
	use Symfony\Component\Mime\Header\Headers;
	use Symfony\Component\Mime\Part\AbstractPart;
	use TYPO3\CMS\Core\Mail\FluidEmail;
	use TYPO3\CMS\Core\Mail\Mailer;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Fluid\View\TemplatePaths;

	/**
	 * Class MailFluid
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	class MailFluid extends FluidEmail implements MailInterface
	{
		/**
		 * @param iterable|string $message
		 */
		public function __construct(TemplatePaths $templatePaths = null, Headers $headers = null, AbstractPart $body = null)
		{
			parent::__construct($templatePaths, $headers, $body);
			$this->format(self::FORMAT_BOTH);
		}

		/**
		 * @inheritDoc
		 */
		public function setRecipient(array $addresses, string $name = ''): MailInterface
		{
			foreach ($addresses as $key => $address)
			{
				$addressObject = $this->addressArrayToObject($key, $address);
				$this->addTo($addressObject);
			}

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function setSubject(string $subject): MailInterface
		{
			return $this->subject($subject);
		}

		/**
		 * @inheritDoc
		 */
		public function setReply(array $addresses, $name = ''): MailInterface
		{
			foreach ($addresses as $key => $address)
			{
				$addressObject = $this->addressArrayToObject($key, $address);

				$this->addReplyTo($addressObject);
			}

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function setContent(string $contents, $objects = []): MailInterface
		{
			$assign = array_merge([
									  'text' => $contents
								  ], $objects);

			return $this->assignMultiple($assign);
		}

		/**
		 * @inheritDoc
		 */
		public function addAttachment(string $url): MailInterface
		{
			return $this->attachFromPath($url);
		}

		/**
		 * @inheritDoc
		 * @throws TransportExceptionInterface
		 */
		public function send(): bool
		{
			/** @var Mailer $mailer */
			$mailer = GeneralUtility::makeInstance(Mailer::class);

			$mailer->send($this);

			return true;
		}

		/**
		 * @inheritDoc
		 */
		public function setBlindcopies(array $addresses): MailInterface
		{
			foreach ($addresses as $key => $address)
			{
				$addressObject = $this->addressArrayToObject($key, $address);

				$this->addBcc($addressObject);
			}

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function setTo(array $addresses, string $name = ''): MailInterface
		{
			foreach ($addresses as $key => $address)
			{
				$addressObject = $this->addressArrayToObject($key, $address);

				$this->addTo($addressObject);
			}

			return $this;
		}

		/**
		 * @inheritDoc
		 */
		public function setFrom(array $addresses, $name = ''): MailInterface
		{
			foreach ($addresses as $key => $address)
			{
				$addressObject = $this->addressArrayToObject($key, $address);

				$this->addFrom($addressObject);
			}

			return $this;
		}

		/**
		 * @param $key
		 * @param $address
		 *
		 * @return Address
		 */
		private function addressArrayToObject($key, $address): Address
		{
			$addressObject = null;
			if (is_int($key))
			{
				$addressObject = new Address($address);
			}
			else
			{
				$addressObject = new Address($key, $address);
			}

			return $addressObject;
		}

		/**
		 * @inheritDoc
		 */
		public function setContentType(string $contentType): MailInterface
		{
			if ($contentType === self::CONTENT_TYPE_TEXT)
			{
				$contentType = 'plain';
			}
			$this->format($contentType);

			return $this;
		}
	}