<?php
	namespace ITX\Jobs\Validator;

	use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

	/**
	 * Class PasswordValidator
	 *
	 * @package ITX\Jobs\Validator
	 */
	class FileValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
	{
		/**
		 * Object Manager
		 *
		 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
		 * @inject
		 */
		protected $objectManager;
		/**
		 * Is valid
		 *
		 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
		 * @return boolean
		 */
		protected function isValid($file) {
			\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($_FILES);
			\TYPO3\CMS\Core\Utility\DebugUtility::debug('HELLO?');
			return false;
		}
	}
