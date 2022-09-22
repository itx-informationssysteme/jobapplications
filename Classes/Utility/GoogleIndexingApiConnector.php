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
	use JsonException;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Core\Environment;
	use TYPO3\CMS\Core\Http\RequestFactory;
	use TYPO3\CMS\Core\Messaging\AbstractMessage;
	use TYPO3\CMS\Core\Messaging\FlashMessage;
	use TYPO3\CMS\Core\Messaging\FlashMessageService;
	use TYPO3\CMS\Core\Service\FlexFormService;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Domain\Model\Category;
	use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
	use TYPO3\CMS\Extbase\Object\ObjectManager;
	/**
	 * Class GoogleIndexingApiConnector
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	class GoogleIndexingApiConnector
	{
		protected array $googleConfig;

		protected array $backendConfiguration;

		/** @var boolean */
		protected bool $supressFlashMessages;

		protected RequestFactory $requestFactory;
		protected TtContentRepository $ttContentRepository;
		protected PostingRepository $postingRepository;
		protected FlashMessageService $flashMessageService;
		protected FlexFormService $flexFormService;

		/**
		 * GoogleIndexingApiConnector constructor.
		 *
		 * @throws JsonException
		 */
		public function __construct(RequestFactory    $requestFactory, TtContentRepository $ttContentRepository,
									PostingRepository $postingRepository, FlashMessageService $flashMessageService,
									FlexFormService   $flexFormService)
		{
			$this->requestFactory = $requestFactory;
			$this->ttContentRepository = $ttContentRepository;
			$this->postingRepository = $postingRepository;
			$this->flashMessageService = $flashMessageService;
			$this->flexFormService = $flexFormService;

			$this->backendConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
														->get('jobapplications');
			if ($this->backendConfiguration['key_path'] !== '')
			{
				$fileName = GeneralUtility::getFileAbsFileName($this->backendConfiguration['key_path']);
				if (file_exists($fileName))
				{
					$this->googleConfig = json_decode(file_get_contents(
														  $fileName
													  ), true, 512, JSON_THROW_ON_ERROR);
				}
			}
		}

		public function setSupressFlashMessages(bool $shouldSupress)
		{
			$this->supressFlashMessages = $shouldSupress;
		}

		/**
		 * Sends requests to Google Indexing API
		 *
		 * @param              $uid
		 * @param bool         $delete
		 * @param Posting|null $specificPosting
		 *
		 * @return bool
		 * @throws \Exception
		 */
		public function updateGoogleIndex($uid, bool $delete, Posting $specificPosting = null): ?bool
		{
			if (Environment::getContext()->isDevelopment() && $this->backendConfiguration['indexing_api_dev'] === "0")
			{
				return false;
			}

			/** @var Posting $posting */
			if ($specificPosting instanceof Posting)
			{
				$posting = $specificPosting;
			}
			else
			{
				$query = $this->postingRepository->createQuery();
				$query->getQuerySettings()->setIgnoreEnableFields(true)
					  ->setRespectStoragePage(false);
				$query->matching(
					$query->equals('uid', $uid)
				);

				$posting = $query->execute()->getFirst();

				if (!$posting instanceof Posting)
				{
					$this->sendFlashMessage("Posting ".$uid." is not accessible (anymore). No request sent.");

					return false;
				}
			}

			//$uriBuilder = new FrontendUriBuilder();

			//$uriBuilder = new UriBuilder();


			/** @var QueryResult $contentElements */
			$contentElements = $this->ttContentRepository->findByListType("jobapplications_frontend");

			$contentElements = $contentElements->toArray();

			if (count($contentElements) === 0)
			{
				return false;
			}

			$detailViewUid = (int)$this->findBestPluginPageFit($contentElements, $posting);

			/*$url = $uriBuilder->setController("Posting")
							  ->setAction("show")
							  ->setPageId($detailViewUid)
							  ->setPlugin("detailview")
							  ->setArguments(['posting' => $posting])
							  ->setExtensionName("jobapplications")
							  ->build();*/




			//$objectManager = GeneralUtility::makeInstance(TYPO3\CMS\Extbase\Object\ObjectManager::class);
			//$uriBuilder = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);

			$uriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)
											->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);

			DebugUtility::debug($uriBuilder);

			//$uriBuilder->injectConfigurationManager();
			//$uriBuilder->initializeObject();

			$url = $uriBuilder
				->reset()
				->setTargetPageUid($detailViewUid)
				->setArgumentPrefix("tx_jobapplications_detailview")
				->uriForFrontend('show', ['posting' => $posting], "Posting", "Jobapplications",  "DetailView");








			/*$url = $uriBuilder
				->reset()
				->setTargetPageUid($detailViewUid)
				->setArgumentPrefix("tx_jobapplications_detailview")
				->setArguments([
					'posting' => $posting
				   	])
				->buildFrontendUri();*/


			DebugUtility::debug($url);


			if ($delete === true)
			{
				$result = $this->makeRequest($url, true);
			}
			else
			{
				$result = $this->makeRequest($url, false);
			}

			if ($result && $delete === false)
			{
				$this->sendFlashMessage("Successfully requested Google to crawl this posting. URL generated: ".$url);
			}

			if ($result && $delete === true)
			{
				$this->sendFlashMessage("Successfully requested Google to remove this posting from their index. URL generated: ".$url);
			}

			return $result;
		}

		/**
		 * Helper method for sending Flash Messages
		 *
		 * @param string $msg
		 * @param string $header
		 * @param bool   $error
		 */
		private function sendFlashMessage(string $msg, string $header = "", bool $error = false): void
		{
			$debug = $this->backendConfiguration['indexing_api_debug'];

			if ($debug === "0")
			{
				return;
			}

			$type = AbstractMessage::OK;

			if ($error)
			{
				$type = AbstractMessage::WARNING;
			}

			/** @var FlashMessage $message */
			$message = GeneralUtility::makeInstance(FlashMessage::class, $msg, $header, $type, true);

			$messageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
			// @extensionScannerIgnoreLine
			$messageQueue->addMessage($message);
		}

		/**
		 * Finds the first plugin fit for posting display
		 *
		 * @param $contentElements array
		 * @param $posting         Posting
		 *
		 * @return mixed
		 */
		private function findBestPluginPageFit(array $contentElements, Posting $posting)
		{
			$postingCategories = $posting->getCategories()->toArray();

			$fallback = $this->flexFormService->convertFlexFormContentToArray($contentElements[0]->getPiFlexform())['settings']['detailViewUid'];

			$result = null;

			if (count($postingCategories) > 0)
			{
				/** @var Category $postingCategory */
				foreach ($postingCategories as $postingCategory)
				{
					foreach ($contentElements as $contentElement)
					{
						$flexformArray = $this->flexFormService->convertFlexFormContentToArray($contentElement->getPiFlexform());

						$categories = explode(",", $flexformArray['settings']['categories']);
						$postingCategoryUid = $postingCategory->getUid();

						if (in_array($postingCategoryUid, $categories, true))
						{
							if (count($categories) === 1)
							{
								$result = $flexformArray['settings']['detailViewUid'];
								break;
							}

							if (count($categories) > 1)
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
					$flexformArray = $this->flexFormService->convertFlexFormContentToArray($contentElement->getPiFlexform(), "lDEF");

					$categories = explode(",", $flexformArray['settings']['categories']);

					if (count($categories) === 0)
					{
						$result = $flexformArray['settings']['detailViewUid'];
						break;
					}
				}
			}

			return $result ?: $fallback;
		}

		/**
		 * @param string $url
		 * @param bool   $deleteInsteadOfUpdate
		 *
		 * @return bool true success, false something went wrong
		 * @throws JsonException
		 */
		public function makeRequest(string $url, bool $deleteInsteadOfUpdate): ?bool
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
				"body" => json_encode($actualBody, JSON_THROW_ON_ERROR),
				"http_errors" => false
			];

			$response = $this->requestFactory->request($indexingRequestUrl, 'POST', $additionalOptions);

			if ($response->getStatusCode() === 200)
			{
				return true;
			}

			$this->sendFlashMessage($response->getBody()->getContents(), "Error trying to send crawl request to Google", true);

			return false;
		}

		/**
		 * Authenticates with Google OAuth API
		 *
		 * @return string Bearer token
		 * @throws JsonException
		 */
		private function makeAuthReq(): string
		{
			// Authentication
			$jwtHeader = [
				"alg" => "RS256",
				"typ" => "JWT"
			];

			$jwtHeaderBase64 = base64_encode(json_encode($jwtHeader, JSON_THROW_ON_ERROR));

			$jwtClaimSet = [
				"iss" => $this->googleConfig["client_email"],
				"scope" => "https://www.googleapis.com/auth/indexing",
				"aud" => $this->googleConfig['token_uri'],
				"exp" => time() + (60 * 60),
				"iat" => time()
			];

			$jwtClaimSetBase64 = base64_encode(json_encode($jwtClaimSet, JSON_THROW_ON_ERROR));

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

			$token = $signatureInput.".".$signatureSignedBase64;

			$accessTokenRequestData = [
				"grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
				"assertion" => $token
			];

			$accessRequest = [
				"headers" => [
					"Content-Type" => "application/json"
				],
				"body" => json_encode($accessTokenRequestData, JSON_THROW_ON_ERROR),
				"http_errors" => false
			];

			$accessTokenUrl = "https://oauth2.googleapis.com/token";

			$accessResponse = $this->requestFactory->request($accessTokenUrl, 'POST', $accessRequest);

			if ($accessResponse->getStatusCode() !== 200)
			{
				$this->sendFlashMessage($accessResponse->getBody()->getContents(), "Error trying to send auth request to Google", true);

				return "";
			}

			$accessRepsonseJson = json_decode($accessResponse->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

			$accessToken = $accessRepsonseJson["access_token"];
			$sessionData = [
				"indexingAccessToken" => [
					"token" => $accessToken,
					"validUntil" => time() + (int)$accessRepsonseJson["expires_in"]
				]
			];

			$GLOBALS["BE_USER"]->setAndSaveSessionData("tx_jobapplications", $sessionData);

			if (!$accessToken)
			{
				$this->sendFlashMessage($accessResponse->getBody()->getContents(), "Error retrieving access token", true);

				return "";
			}

			return $accessToken;
		}
	}
