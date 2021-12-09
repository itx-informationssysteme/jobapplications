<?php

	namespace ITX\Jobapplications\Service;

	use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
	use TYPO3\CMS\Core\Resource\Exception\FileOperationErrorException;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientUserPermissionsException;
	use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
	use TYPO3\CMS\Core\Resource\ResourceStorage;
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Extbase\Object\ObjectManager;

	/**
	 * This file is part of the "jobapplications" Extension for TYPO3 CMS.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.txt file that was distributed with this source code.
	 */
	class ApplicationFileService
	{
		public const APP_FILE_FOLDER = "applications/";

		/**
		 * Helper function to generate the folder for an application
		 *
		 * @param $applicationObject
		 *
		 * @return string
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
		 */

		public function getApplicantFolder(\ITX\Jobapplications\Domain\Model\Application $applicationObject): string
		{
			return self::APP_FILE_FOLDER.(new \TYPO3\CMS\Core\Resource\Driver\LocalDriver)
					->sanitizeFileName($applicationObject->getFirstName()."_".$applicationObject->getLastName()
									   ."_".hash("md5", $applicationObject->getFirstName()."|"
													  .$applicationObject->getLastName()
													  .$applicationObject->getUid()));
		}

		/**
		 * Deletes the entire Folder
		 *
		 * @param $folderPath string
		 *
		 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
		 */
		public function deleteApplicationFolder(string $folderPath): void
		{
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

			/* @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
			$storageRepository = $objectManager->get(\TYPO3\CMS\Core\Resource\StorageRepository::class);
			$storage = $storageRepository->findByUid(1);
			if (!$storage instanceof ResourceStorage) {
				throw new FileNotFoundException("Could not find fileadmin with uid 1");
			}

			/** @var \TYPO3\CMS\Core\Resource\Folder|null $folder */
			$folder = null;
			if ($storage->hasFolder($folderPath))
			{
				$folder = $storage->getFolder($folderPath);
			}

			if ($folder instanceof \TYPO3\CMS\Core\Resource\Folder)
			{
				try
				{
					$storage->deleteFolder($folder, true);
				}
				catch (FileOperationErrorException | InsufficientFolderAccessPermissionsException
				| InsufficientUserPermissionsException | InvalidPathException $e)
				{
				}
			}
		}
	}