<?php

	namespace ITX\Jobs\Task;

	/**
	 * Class CleanUpApplicationsAdditionalFieldProvider
	 *
	 * @package ITX\Jobs\TaskAdditionalFieldProvider
	 */
	class CleanUpApplicationsAdditionalFieldProvider extends \TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider
	{
		/**
		 * This method is used to define new fields for adding or editing a task
		 * In this case, it adds an email field
		 *
		 * @param array $taskInfo Reference to the array containing the info used in the add/edit form
		 * @param AbstractTask|null $task When editing, reference to the current task. NULL when adding.
		 * @param SchedulerModuleController $schedulerModule Reference to the calling object (Scheduler's BE module)
		 * @return array Array containing all the information pertaining to the additional fields
		 */
		public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule)
		{
			$currentSchedulerModuleAction = $schedulerModule->getCurrentAction();

			// Write the code for the field
			$fieldID = 'task_months';
			$fieldCode = '<input type="number" min="1" max="12" name="tx_scheduler[months]" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo['months']) . '" size="30">';
			$additionalFields = [];
			$additionalFields[$fieldID] = [
				'code' => $fieldCode,
				'label' => 'LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf:task.months.label',
				'cshKey' => 'csh',
				'cshLabel' => $fieldID
			];
			return $additionalFields;
		}
		/**
		 * This method checks any additional data that is relevant to the specific task
		 * If the task class is not relevant, the method is expected to return TRUE
		 *
		 * @param array	 $submittedData Reference to the array containing the data submitted by the user
		 * @param SchedulerModuleController $schedulerModule Reference to the calling object (Scheduler's BE module)
		 * @return bool TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
		 */
		public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule)
		{
			$submittedData['months'] = trim($submittedData['months']);
			if (empty($submittedData['months'])) {
				$this->addMessage(
					$this->getLanguageService()->sL('LLL:EXT:jobs/Resources/Private/Language/locallang_backend.xlf:task.months.empty'),
					FlashMessage::ERROR
				);
				$result = false;
			} else {
				$result = true;
			}
			return $result;
		}
		/**
		 * This method is used to save any additional input into the current task object
		 * if the task class matches
		 *
		 * @param array $submittedData Array containing the data submitted by the user
		 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the current task object
		 */
		public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task)
		{
			$task->months = $submittedData['months'];
		}
		/**
		 * @return LanguageService|null
		 */
		protected function getLanguageService(): ?LanguageService
		{
			return $GLOBALS['LANG'] ?? null;
		}
	}