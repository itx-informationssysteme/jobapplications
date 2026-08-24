<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Task;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class ProcessIndexingQueueAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule): array
    {
        if (empty($taskInfo['jobapplications_limit'])) {
            $taskInfo['jobapplications_limit'] = ($task instanceof ProcessIndexingQueueTask)
                ? $task->limit
                : 50;
        }

        return [
            'jobapplications_limit' => [
                'code' => sprintf(
                    '<input type="number" min="1" max="500" class="form-control" name="tx_scheduler[jobapplications_limit]" id="jobapplications_limit" value="%d">',
                    (int)$taskInfo['jobapplications_limit']
                ),
                'label' => 'Max jobs per run',
            ],
        ];
    }

    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule): bool
    {
        $limit = (int)($submittedData['jobapplications_limit'] ?? 0);

        if ($limit < 1 || $limit > 500) {
            $this->addMessage('Max jobs per run must be between 1 and 500.', ContextualFeedbackSeverity::ERROR);
            return false;
        }

        return true;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task): void
    {
        if ($task instanceof ProcessIndexingQueueTask) {
            $task->limit = (int)$submittedData['jobapplications_limit'];
        }
    }
}
