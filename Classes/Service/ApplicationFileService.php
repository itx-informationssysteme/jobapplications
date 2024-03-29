<?php

	namespace ITX\Jobapplications\Service;

	use ITX\Jobapplications\Domain\Model\Application;
	use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
	use TYPO3\CMS\Core\Resource\Exception\FileOperationErrorException;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
	use TYPO3\CMS\Core\Resource\Exception\InsufficientUserPermissionsException;
	use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
	use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
	use TYPO3\CMS\Core\Resource\Folder;
	use TYPO3\CMS\Core\Resource\ResourceStorageInterface;
	use TYPO3\CMS\Core\Resource\StorageRepository;
	use TYPO3\CMS\Extbase\Domain\Model\FileReference;

	/**
	 * This file is part of the "jobapplications" Extension for TYPO3 CMS.
	 *
	 * For the full copyright and license information, please read the
	 * LICENSE.txt file that was distributed with this source code.
	 */
	class ApplicationFileService
	{
		public const APP_FILE_FOLDER = "applications/";

		protected StorageRepository $storageRepository;

		public function __construct(StorageRepository $storageRepository)
		{
			$this->storageRepository = $storageRepository;
		}

		/**
		 * Helper function to generate the folder for an application
		 *
		 * @param $applicationObject
		 *
		 * @return string
		 * @throws InvalidFileNameException
		 */
		public function getApplicantFolder(Application $applicationObject): string
		{
			return self::APP_FILE_FOLDER.(new LocalDriver)
					->sanitizeFileName($applicationObject->getFirstName()."_".$applicationObject->getLastName()
									   ."_".hash("md5", $applicationObject->getFirstName()."|"
													  .$applicationObject->getLastName()
													  .$applicationObject->getUid()));
		}

		/**
		 * Helper function to get the correct file storage
		 *
		 * @param $applicationObject
		 *
		 * @return string
		 */
		public function getFileStorage(Application $applicationObject): ?int
		{
			if ($applicationObject->getFiles() !== null && $applicationObject->getFiles()->count() > 0)
			{
				/** @var FileReference $file */
				$file = $applicationObject->getFiles()->toArray()[0];

				return $file->getOriginalResource()->getStorage()->getUid();
			}

			if ($applicationObject->getCv() !== null && $applicationObject->getCv()->count() > 0)
			{
				/** @var FileReference $file */
				$file = $applicationObject->getCv()->toArray()[0];

				return $file->getOriginalResource()->getStorage()->getUid();
			}

			if ($applicationObject->getCoverLetter() !== null && $applicationObject->getCoverLetter()->count() > 0)
			{
				/** @var FileReference $file */
				$file = $applicationObject->getCoverLetter()->toArray()[0];

				return $file->getOriginalResource()->getStorage()->getUid();
			}

			if ($applicationObject->getTestimonials() !== null && $applicationObject->getTestimonials()->count() > 0)
			{
				/** @var FileReference $file */
				$file = $applicationObject->getTestimonials()->toArray()[0];

				return $file->getOriginalResource()->getStorage()->getUid();
			}

			if ($applicationObject->getOtherFiles() !== null && $applicationObject->getOtherFiles()->count() > 0)
			{
				/** @var FileReference $file */
				$file = $applicationObject->getOtherFiles()->toArray()[0];

				return $file->getOriginalResource()->getStorage()->getUid();
			}

			return null;
		}

		/**
		 * Deletes the entire Folder
		 *
		 * @param $folderPath  string
		 * @param $fileStorage int|null
		 *
		 * @throws InsufficientFolderAccessPermissionsException
		 */
		public function deleteApplicationFolder(string $folderPath, ?int $fileStorage): void
		{
			if ($fileStorage === null)
			{
				return;
			}

			$storage = $this->storageRepository->findByUid($fileStorage);
			if (!$storage instanceof ResourceStorageInterface)
			{
				throw new \RuntimeException(sprintf("Resource storage with uid %d could not be found.", $fileStorage));
			}

			/** @var Folder|null $folder */
			$folder = null;
			if ($storage->hasFolder($folderPath))
			{
				$folder = $storage->getFolder($folderPath);
			}

			if ($folder instanceof Folder)
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