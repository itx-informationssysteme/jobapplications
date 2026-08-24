<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Hooks;

use ITX\Jobapplications\Service\IndexingQueueService;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class DataHandlerIndexingHook
{
    private const TABLE = 'tx_jobapplications_domain_model_posting';

    public function __construct(
        private readonly IndexingQueueService $queueService,
        private readonly ExtensionConfiguration $extensionConfiguration,
    ) {}

    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        int|string $id,
        array $fieldArray,
        DataHandler $dataHandler
    ): void {
        if ($table !== self::TABLE || !$this->isEnabled()) {
            return;
        }

        $uid = $status === 'new'
            ? ($dataHandler->substNEWwithIDs[$id] ?? null)
            : (int)$id;

        if ($uid === null) {
            return;
        }

        $uid = (int)$uid;

        // A save that sets hidden=1 removes the posting from the index;
        // any other save (incl. un-hiding) (re-)indexes it.
        $action = (($fieldArray['hidden'] ?? 0) == 1)
            ? IndexingQueueService::ACTION_DELETE
            : IndexingQueueService::ACTION_INDEX;

        $this->queueService->enqueue($uid, $action);
    }

    public function processCmdmap_deleteAction(
        string $table,
        int|string $uid,
        array $record,
        bool &$recordWasDeleted,
        DataHandler $dataHandler
    ): void {
        if ($table !== self::TABLE || !$this->isEnabled()) {
            return;
        }

        $this->queueService->enqueue((int)$uid, IndexingQueueService::ACTION_DELETE);
    }

    private function isEnabled(): bool
    {
        return (bool)($this->extensionConfiguration->get('jobapplications', 'indexing_api') ?? false);
    }
}
