<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Service;

use ITX\Jobapplications\Domain\Repository\PostingRepository;
use ITX\Jobapplications\Utility\GoogleIndexingApiConnector;

class IndexingQueueProcessor
{
    public const DEFAULT_MAX_ATTEMPTS = 5;

    public function __construct(
        private readonly IndexingQueueService $queueService,
        private readonly GoogleIndexingApiConnector $connector,
        private readonly PostingRepository $postingRepository,
    ) {}

    /**
     * @return array{processed: int, succeeded: int, failed: int, log: string[]}
     */
    public function process(int $limit = 50, int $maxAttempts = self::DEFAULT_MAX_ATTEMPTS, bool $dryRun = false): array
    {
        $jobs = $this->queueService->fetchPending($limit, $maxAttempts);

        $result = ['processed' => count($jobs), 'succeeded' => 0, 'failed' => 0, 'log' => []];

        foreach ($jobs as $job) {
            $queueUid = (int)$job['uid'];
            $postingUid = (int)$job['posting_uid'];
            $isDelete = $job['action'] === IndexingQueueService::ACTION_DELETE;

            if ($dryRun) {
                $result['log'][] = sprintf(
                    'Would %s posting #%d (queue #%d, attempt %d)',
                    $isDelete ? 'DELETE' : 'INDEX',
                    $postingUid,
                    $queueUid,
                    (int)$job['attempts'] + 1
                );
                continue;
            }

            try {
                $posting = $isDelete ? null : $this->postingRepository->findByUid($postingUid);

                if (!$isDelete && $posting === null) {
                    // Posting is gone; nothing left to index. Don't retry forever.
                    $this->queueService->markSucceeded($queueUid);
                    $result['succeeded']++;
                    continue;
                }

                $ok = $this->connector->updateGoogleIndex($postingUid, $isDelete, $posting);

                if ($ok) {
                    $this->queueService->markSucceeded($queueUid);
                    $result['succeeded']++;
                } else {
                    $this->queueService->markFailed($queueUid, (int)$job['attempts'], 'Connector returned false');
                    $result['failed']++;
                }
            } catch (\Throwable $e) {
                $this->queueService->markFailed($queueUid, (int)$job['attempts'], $e->getMessage());
                $result['failed']++;
                $result['log'][] = sprintf('Job #%d failed: %s', $queueUid, $e->getMessage());
            }
        }

        return $result;
    }
}
