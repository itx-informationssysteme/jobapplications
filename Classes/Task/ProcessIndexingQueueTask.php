<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Task;

use ITX\Jobapplications\Service\IndexingQueueProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class ProcessIndexingQueueTask extends AbstractTask
{
    public int $limit = 50;

    public function execute(): bool
    {
        /** @var IndexingQueueProcessor $processor */
        $processor = GeneralUtility::makeInstance(IndexingQueueProcessor::class);

        try {
            $result = $processor->process($this->limit);
        } catch (\Throwable $e) {
            throw $e;
        }

        return true;
    }

    public function getAdditionalInformation(): string
    {
        return sprintf('Batch size: %d', $this->limit);
    }
}
