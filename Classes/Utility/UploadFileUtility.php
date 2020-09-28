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

	use TYPO3\CMS\Core\Core\Environment;
	use TYPO3\CMS\Core\Http\UploadedFile;
	use TYPO3\CMS\Core\Utility\DebugUtility;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Form\Domain\Exception\IdentifierNotValidException;

	/**
	 * Class UploadFileUtility
	 *
	 * @package ITX\Jobapplications\Utility
	 */
	class UploadFileUtility
	{
		/** @var string $varPath */
		protected $fileTempPath;

		public function __construct()
		{
			$this->fileTempPath = Environment::getVarPath().DIRECTORY_SEPARATOR.'typo3temp'.DIRECTORY_SEPARATOR.'jobapplications'.DIRECTORY_SEPARATOR;
		}

		public function handleFile(UploadedFile $file): string
		{
			$uniqueFileIdentifier = uniqid('', true);

			$filePath = $this->fileTempPath.$uniqueFileIdentifier.DIRECTORY_SEPARATOR;

			if (!mkdir($filePath, 0777, true) && !is_dir($filePath))
			{
				throw new \RuntimeException('Directory was not created.');
			}

			// TYPO3 v9 compatibility
			$version = Typo3VersionUtility::getMajorVersion();

			if ($version < 10)
			{
				$file->moveTo($filePath);
				$files = GeneralUtility::getFilesInDir($filePath);
				if (is_array($files) && count($files) !== 1)
				{
					throw new \RuntimeException("Too many or not enough files in unique identifier dir!");
				}

				$renameResult = rename($filePath.reset($files), $filePath.$file->getClientFilename());
				if (!$renameResult)
				{
					throw new \RuntimeException("Could not rename file: ".$filePath.files[0]);
				}
			}
			else
			{
				$file->moveTo($filePath.$file->getClientFilename());
			}

			return $uniqueFileIdentifier;
		}

		/**
		 * @param $uniqueIdentifier
		 *
		 * @throws IdentifierNotValidException
		 */
		public function getFilePath(string $uniqueIdentifier): string
		{
			$filePath = $this->fileTempPath.$uniqueIdentifier.DIRECTORY_SEPARATOR;
			if (strlen($uniqueIdentifier) !== 23)
			{
				throw new IdentifierNotValidException('Invalid identifier');
			}

			if (!GeneralUtility::validPathStr($filePath))
			{
				throw new \RuntimeException("Trying to get Filepath: Path not valid");
			}

			$files = GeneralUtility::getFilesInDir($filePath);
			$i = 0;
			$returnPath = '';
			foreach ($files as $file)
			{
				if ($i > 0)
				{
					throw new \RuntimeException('Not allowed to have more than one file in unique folder.');
				}
				$returnPath = $filePath.$file;
				$i++;
			}

			if (empty($returnPath))
			{
				throw new \RuntimeException('Folder has to have at least one file.');
			}

			return $returnPath;
		}

		/**
		 * @param $uniqueIdentifier
		 *
		 * @throws IdentifierNotValidException
		 */
		public function getFileName(string $uniqueIdentifier): string
		{
			$filePath = $this->fileTempPath.$uniqueIdentifier.DIRECTORY_SEPARATOR;

			if (strlen($uniqueIdentifier) !== 23)
			{
				throw new IdentifierNotValidException('Invalid identifier');
			}

			if (!GeneralUtility::validPathStr($filePath))
			{
				throw new \RuntimeException("Trying to get Filename: Path not valid");
			}

			$files = GeneralUtility::getFilesInDir($filePath);
			$i = 0;
			$fileName = '';
			foreach ($files as $file)
			{
				if ($i > 0)
				{
					throw new \RuntimeException('Not allowed to have more than one file in unique folder.');
				}
				$fileName = $file;
			}

			if (empty($fileName))
			{
				throw new \RuntimeException('Folder has to have at least one file.');
			}

			return $fileName;
		}

		public function deleteFolder(string $uniqueIdentifier): bool
		{
			$folderPath = $this->fileTempPath.$uniqueIdentifier;
			if (!GeneralUtility::validPathStr($folderPath))
			{
				throw new \RuntimeException("Trying to get Filename: Path not valid");
			}

			return GeneralUtility::rmdir($folderPath, true);
		}
	}