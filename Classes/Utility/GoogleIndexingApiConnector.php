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

	namespace ITX\Jobapplications\Utility;

	use ITX\Jobapplications\Domain\Model\Posting;
	use ITX\Jobapplications\Domain\Repository\PostingRepository;
	use ITX\Jobapplications\Domain\Repository\TtContentRepository;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Exception;
	use TYPO3\CMS\Core\Http\RequestFactory;
	use TYPO3\CMS\Core\Service\FlexFormService;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Domain\Model\Category;
	use TYPO3\CMS\Extbase\Object\ObjectManager;
	use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

	/**
	 * Class GoogleIndexingApiConnector
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	class GoogleIndexingApiConnector
	{
		/** @var ObjectManager */
		protected $objectManager;

		/** @var array */
		protected $googleConfig;

		/** @var RequestFactory */
		protected $requestFactory;

		/** @var array */
		protected $backendConfiguration;

		/** @var boolean */
		protected $supressFlashMessages;

		/**
		 * GoogleIndexingApiConnector constructor.
		 *
		 * @param bool $supressFlashMessages
		 */
		public function __construct($supressFlashMessages = false)
		{
			$this->backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
																				->get('jobapplications');
			$this->googleConfig = json_decode(file_get_contents(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->backendConfiguration['key_path'])), true);
			$this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			$this->requestFactory = $this->objectManager->get(RequestFactory::class);

			$this->supressFlashMessages = $supressFlashMessages;
		}

		/**
		 * Finds the first plugin fit for posting display
		 *
		 * @param $contentElements array
		 * @param $posting         Posting
		 */
		private function findBestPluginPageFit($contentElements, $posting)
		{

			/** @var FlexFormService $flexformService */
			$flexformService = $this->objectManager->get(FlexFormService::class);

			$postingCategories = $posting->getCategories()->toArray();

			$fallback = $flexformService->convertFlexFormContentToArray($contentElements[0]->getPiFlexform(), "lDEF")['settings']['detailViewUid'];

			$result = null;

			if (count($postingCategories) > 0)
			{
				/** @var Category $postingCategory */
				foreach ($postingCategories as $postingCategory)
				{
					foreach ($contentElements as $contentElement)
					{
						$flexformArray = $flexformService->convertFlexFormContentToArray($contentElement->getPiFlexform(), "lDEF");

						$categories = explode(",", $flexformArray['settings']['categories']);
						$postingCategoryUid = $postingCategory->getUid();

						if (in_array($postingCategoryUid, $categories))
						{
							if (count($categories) === 1)
							{
								$result = $flexformArray['settings']['detailViewUid'];
								break;
							}
							else if (count($categories) > 1)
							{
								$fallback = $flexformArray['settings']['detailViewUid'];
							}
						}
					}
				}
			}
			else
			{
				foreach ($contentElements as $contentElement)
				{
					$flexformArray = $flexformService->convertFlexFormContentToArray($contentElement->getPiFlexform(), "lDEF");

					$categories = explode(",", $flexformArray['settings']['categories']);

					if (count($categories) === 0)
					{
						$result = $flexformArray['settings']['detailViewUid'];
						break;
					}
				}
			}

			return $result ? $result : $fallback;
		}

		/**
		 * @param $command
		 * @param $table
		 * @param $uid
		 * @param $value
		 *
		 * @throws \Exception
		 */
		public function updateGoogleIndex($uid, $delete = false, $specificPosting = null)
		{
			/** @var PostingRepository $postingRepository */
			$postingRepository = $this->objectManager->get(\ITX\Jobapplications\Domain\Repository\PostingRepository::class);
			/** @var TtContentRepository $ttContentRepository */
			$ttContentRepository = $this->objectManager->get(\ITX\Jobapplications\Domain\Repository\TtContentRepository::class);

			/** @var Posting $posting */
			if ($specificPosting instanceof Posting) {
				$posting = $specificPosting;
			} else {
				$posting = $postingRepository->findByUid($uid);
				if (!$posting instanceof Posting)
				{
					throw new \Exception("Posting ".$uid." is not accessible (anymore).");
				}
			}

			/** @var FrontendUriBuilder $uriBuilder */
			$uriBuilder = new FrontendUriBuilder();

			/** @var QueryResult $contentElements */
			$contentElements = $ttContentRepository->findByListType("jobapplications_frontend");

			$contentElements = $contentElements->toArray();

			if (count($contentElements) === 0)
			{
				return false;
			}

			$detailViewUid = (int)$this->findBestPluginPageFit($contentElements, $posting);

			$url = $uriBuilder->setController("Posting")
							  ->setAction("show")
							  ->setPageId($detailViewUid)
							  ->setPlugin("detailview")
							  ->setArguments(['posting' => $posting])
							  ->setExtensionName("jobapplications")
							  ->build();

			if ($delete === true)
			{
				$result = $this->makeRequest($url, true);
			}
			else
			{
				$result = $this->makeRequest($url);
			}

			if ($result)
			{
				$this->sendFlashMessage("Successfully requested Google to crawl this posting. URL generated: ". $url);
			}

			return $result;
		}

		/**
		 * @param string $url
		 *
		 * @return bool true success, false something went wrong
		 */
		public function makeRequest(string $url, $deleteInsteadOfUpdate = false)
		{
			$accessToken = "";

			if (!$this->googleConfig)
			{
				$this->sendFlashMessage("Misconfigured config file path OR wrong file format.", "", true);

				return false;
			}

			$sessionData = $GLOBALS["BE_USER"]->getSessionData("tx_jobapplications");

			if ($sessionData['indexingAccessToken'])
			{
				$storedTokenData = $sessionData['indexingAccessToken'];

				$timeLeft = (int)$storedTokenData['validUntil'] - time();

				if ($timeLeft > 10)
				{
					$accessToken = $storedTokenData['token'];
				}
			}

			if ($accessToken === "")
			{

				$autTokenResult = $this->makeAuthReq();
				if ($autTokenResult !== "")
				{
					$accessToken = $autTokenResult;
				}
				else
				{
					return false;
				}
			}

			if ($deleteInsteadOfUpdate)
			{
				$type = "URL_DELETED";
			}
			else
			{
				$type = "URL_UPDATED";
			}

			$actualBody = [
				"url" => $url,
				"type" => $type
			];

			$indexingRequestUrl = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
			$additionalOptions = [
				'headers' =>
					[
						"Content-Type" => "application/json",
						'Authorization' => "Bearer ".$accessToken
					],
				"body" => json_encode($actualBody),
				"http_errors" => false
			];

			$response = $this->requestFactory->request($indexingRequestUrl, 'POST', $additionalOptions);

			if ($response->getStatusCode() === 200)
			{
				return true;
			}
			else
			{
				$this->sendFlashMessage($response->getBody()->getContents(), "Error trying to send crawl request to Google", true);

				return false;
			}
		}

		/**
		 * @return string
		 */
		private function makeAuthReq()
		{
			// Authentication
			$jwtHeader = [
				"alg" => "RS256",
				"typ" => "JWT"
			];

			$jwtHeaderBase64 = base64_encode(json_encode($jwtHeader));

			$jwtClaimSet = [
				"iss" => $this->googleConfig["client_email"],
				"scope" => "https://www.googleapis.com/auth/indexing",
				"aud" => $this->googleConfig['token_uri'],
				"exp" => time() + (60 * 60),
				"iat" => time()
			];

			$jwtClaimSetBase64 = base64_encode(json_encode($jwtClaimSet));

			$signatureInput = $jwtHeaderBase64.".".$jwtClaimSetBase64;

			$signatureSigned = "";

			$formattedKey = $this->googleConfig['private_key'];

			$keyRes = openssl_pkey_get_private((string)$formattedKey);

			if (!openssl_sign($signatureInput, $signatureSigned, $keyRes, "sha256WithRSAEncryption"))
			{
				$this->sendFlashMessage("Misconfigured config file path OR wrong file format.", "", true);

				return "";
			}

			$signatureSignedBase64 = base64_encode($signatureSigned);

			$token .= $signatureInput.".".$signatureSignedBase64;

			$accessTokenRequestData = [
				"grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
				"assertion" => $token
			];

			$accessRequest = [
				"headers" => [
					"Content-Type" => "application/json"
				],
				"body" => json_encode($accessTokenRequestData),
				"http_errors" => false
			];

			$accessTokenUrl = "https://oauth2.googleapis.com/token";

			$accessResponse = $this->requestFactory->request($accessTokenUrl, 'POST', $accessRequest);

			if ($accessResponse->getStatusCode() !== 200)
			{
				$this->sendFlashMessage($accessResponse->getBody()->getContents(), "Error trying to send auth request to Google", true);

				return "";
			}
			else
			{
				$accessRepsonseJson = json_decode($accessResponse->getBody()->getContents(), true);

				$accessToken = $accessRepsonseJson["access_token"];
				$sessionData = [
					"indexingAccessToken" => [
						"token" => $accessToken,
						"validUntil" => time() + (int)$accessRepsonseJson["expires_in"]
					]
				];

				$GLOBALS["BE_USER"]->setAndSaveSessionData("tx_jobapplications", $sessionData);
			}

			if (!$accessToken)
			{
				$this->sendFlashMessage($accessResponse->getBody()->getContents(), "Error retrieving access token", true);

				return "";
			}

			return $accessToken;
		}

		/**
		 * Helper method for sending Flash Messages
		 *
		 * @param string $msg
		 * @param string $header
		 * @param bool   $error
		 */
		private function sendFlashMessage(string $msg, string $header = "", bool $error = false)
		{
			$debug = $this->backendConfiguration['indexing_api_debug'];

			if ($debug === "0" || $this->suppressFlashMessages)
			{
				return;
			}

			$type = \TYPO3\CMS\Core\Messaging\FlashMessage::OK;

			if ($error)
			{
				$type = \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING;
			}

			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class,$msg,$header,$type,true);

			$flashMessageService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
			$messageQueue = $flashMessageService->getMessageQueueByIdentifier();
			$messageQueue->addMessage($message);
		}
	}