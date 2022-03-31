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

	namespace ITX\Jobapplications\Controller;

	use ITX\Jobapplications\Utility\UploadFileUtility;
	use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
	use TYPO3\CMS\Core\Core\Environment;
	use TYPO3\CMS\Core\Http\HtmlResponse;
	use TYPO3\CMS\Core\Http\UploadedFile;
	use TYPO3\CMS\Core\Utility\GeneralUtility;

	/**
	 * Class AjaxController
	 *
	 * @package ITX\Jobapplications\Controller
	 */
	class AjaxController
	{
		protected $fileTempPath;

		/**
		 * CustomerServerAssignment constructor.
		 */
		public function __construct()
		{
			$this->fileTempPath = Environment::getVarPath().DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.'jobapplications'.DIRECTORY_SEPARATOR;
		}

		/**
		 * @param \Psr\Http\Message\ServerRequestInterface $request
		 * @param \Psr\Http\Message\ResponseInterface      $response
		 *
		 * @return \Psr\Http\Message\ResponseInterface
		 */
		public function uploadAction(
			\Psr\Http\Message\ServerRequestInterface $request
		): \Psr\Http\Message\ResponseInterface
		{

			$response = new HtmlResponse('');

			$fileSizeLimit = 0;
			$errorBit = false;
			$responseContent = '';

			/** @var ExtensionConfiguration $extconf */
			$extconf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class);
			$extConfLimit = $extconf->get('jobapplications', 'customFileSizeLimit');
			$allowedFileTypesString = $extconf->get('jobapplications', 'allowedFileTypes');
			$allowedFileTypes = $allowedFileTypesString !== '' ? explode(',', $allowedFileTypesString) : [];

			if (count($request->getUploadedFiles()) > 1)
			{
				throw new \RuntimeException('Endpoint only accepts one file per request');
			}

			if ($extConfLimit !== '')
			{
				$fileSizeLimit = (int)$extConfLimit > (int)GeneralUtility::getMaxUploadFileSize() ?
					GeneralUtility::getMaxUploadFileSize() : (int)$extConfLimit;
			}
			else
			{
				$fileSizeLimit = (int)GeneralUtility::getMaxUploadFileSize();
			}

			if (empty($request->getUploadedFiles()))
			{
				$responseContent = 'file_error';
				$errorBit = true;
			}
			/**
			 * @var string             $uploadType
			 * @var UploadedFile|array $file
			 */
			foreach ($request->getUploadedFiles()['tx_jobapplications_applicationform'] as $uploadType => $file)
			{
				// In case upload field is multiple file field
				if (is_array($file))
				{
					$file = $file[0];
					if ($file === null)
					{
						throw new \RuntimeException('Could not find upload file.');
					}
				}

				// Check against allowed filetypes
				if (!empty($allowedFileTypes) && !in_array('.'.pathinfo($file->getClientFilename())['extension'], $allowedFileTypes, true))
				{
					$responseContent = 'file_type';
					$errorBit = true;
					break;
				}

				// Check against size limit
				if ($file->getSize() > $fileSizeLimit * 1024)
				{
					$responseContent = 'file_size';
					$errorBit = true;
					break;
				}

				// Check against php file errors
				if ($file->getError() !== 0)
				{
					$responseContent = 'file_error';
					$errorBit = true;
					break;
				}

				$uploadFileUtil = new UploadFileUtility();
				$uniqueFileIdentifier = $uploadFileUtil->handleFile($file);

				$responseContent = $uniqueFileIdentifier;
			}

			$response->getBody()->write($responseContent);

			return $response->withStatus($errorBit ? 400 : 200);
		}

		public function revertAction(
			\Psr\Http\Message\ServerRequestInterface $request
		): \Psr\Http\Message\ResponseInterface
		{
			$response = new HtmlResponse('');
			$body = $request->getBody();
			if (strlen($body) === 23)
			{
				$utility = new UploadFileUtility();
				$utility->deleteFolder($body);
			}

			return $response;
		}
	}