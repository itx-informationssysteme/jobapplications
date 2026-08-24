<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IndexingQueueService
{
    public const ACTION_INDEX = 'index';
    public const ACTION_DELETE = 'delete';

    private const TABLE = 'tx_jobapplications_indexing_queue';

    public function __construct(private readonly ConnectionPool $connectionPool) {}

    public function enqueue(int $postingUid, string $action): void
    {
        $connection = $this->connectionPool->getConnectionForTable(self::TABLE);

        $connection->delete(self::TABLE, ['posting_uid' => $postingUid]);

        $now = $GLOBALS['EXEC_TIME'] ?? time();
        $connection->insert(self::TABLE, [
            'pid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'posting_uid' => $postingUid,
            'action' => $action,
            'attempts' => 0,
            'last_error' => '',
            'last_attempt_tstamp' => 0,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchPending(int $limit = 50, int $maxAttempts = 5): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE);

        return $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->lt('attempts', $queryBuilder->createNamedParameter($maxAttempts, Connection::PARAM_INT))
            )
            ->orderBy('crdate', 'ASC')
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function markSucceeded(int $queueUid): void
    {
        $this->connectionPool->getConnectionForTable(self::TABLE)
                             ->delete(self::TABLE, ['uid' => $queueUid]);
    }

    public function markFailed(int $queueUid, int $attempts, string $error): void
    {
        $this->connectionPool->getConnectionForTable(self::TABLE)
                             ->update(
                                 self::TABLE,
                                 [
                                     'attempts' => $attempts + 1,
                                     'last_error' => substr($error, 0, 4000),
                                     'last_attempt_tstamp' => $GLOBALS['EXEC_TIME'] ?? time(),
                                 ],
                                 ['uid' => $queueUid]
                             );
    }

    public static function create(): self
    {
        return GeneralUtility::makeInstance(self::class);
    }
}
