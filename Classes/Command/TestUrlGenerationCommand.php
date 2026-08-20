<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Command;

use ITX\Jobapplications\Domain\Repository\PostingRepository;
use ITX\Jobapplications\Domain\Repository\TtContentRepository;
use ITX\Jobapplications\Utility\GoogleIndexingApiConnector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Service\FlexFormService;

/**
 * TEMPORARY debug command for GoogleIndexingApiConnector.
 *
 * Default mode (no flags): PREVIEW ONLY. Subclasses the connector,
 * overriding its public makeRequest() to capture the URL instead of
 * sending it. Exercises the real posting lookup, FlexForm parsing, and
 * UriBuilderJobapplications routing — everything except the actual
 * HTTP call. Safe to run repeatedly: no network call, no Google API
 * quota used, works whether or not key_path is configured, works with
 * URLs on domains that aren't live/verified yet.
 *
 * --live: exercises the REAL connector, including the actual network
 * call to Google if key_path is configured. Only use this once you've
 * confirmed the previewed URL looks correct and you're ready to
 * consume real API quota / actually submit to Google.
 *
 * Usage:
 *   bin/typo3 jobapplications:testurlgeneration 123
 *   bin/typo3 jobapplications:testurlgeneration 123 --delete
 *   bin/typo3 jobapplications:testurlgeneration 123 --live
 */
class TestUrlGenerationCommand extends Command
{
    public function __construct(
        private readonly PostingRepository $postingRepository,
        private readonly RequestFactory $requestFactory,
        private readonly TtContentRepository $ttContentRepository,
        private readonly FlashMessageService $flashMessageService,
        private readonly FlexFormService $flexFormService,
        private readonly GoogleIndexingApiConnector $realConnector,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('DEBUG ONLY: previews (default) or actually sends (--live) the URL GoogleIndexingApiConnector builds for a posting.');
        $this->addArgument('postingUid', InputArgument::REQUIRED, 'UID of an existing Posting record');
        $this->addOption('delete', null, InputOption::VALUE_NONE, 'Test the DELETE/de-index path instead of INDEX');
        $this->addOption('live', null, InputOption::VALUE_NONE, 'DANGER: actually call Google via the real connector, instead of just previewing the URL');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $uid = (int)$input->getArgument('postingUid');
        $delete = (bool)$input->getOption('delete');
        $live = (bool)$input->getOption('live');

        $posting = $delete ? null : $this->postingRepository->findByUid($uid);
        if (!$delete && $posting === null) {
            $output->writeln("<error>No posting found for uid $uid.</error>");
            return Command::FAILURE;
        }

        return $live
            ? $this->runLive($output, $uid, $delete, $posting)
            : $this->runPreview($output, $uid, $delete, $posting);
    }

    private function runPreview(OutputInterface $output, int $uid, bool $delete, mixed $posting): int
    {
        $connector = new class (
            $this->requestFactory,
            $this->ttContentRepository,
            $this->postingRepository,
            $this->flashMessageService,
            $this->flexFormService,
        ) extends GoogleIndexingApiConnector {
            public ?string $capturedUrl = null;

            public function makeRequest(string $url, bool $deleteInsteadOfUpdate): ?bool
            {
                // Deliberately does NOT touch the network — just
                // captures the URL that would have been sent.
                $this->capturedUrl = $url;
                return true;
            }
        };

        try {
            $connector->updateGoogleIndex($uid, $delete, $posting);
        } catch (\Throwable $e) {
            $output->writeln('<error>EXCEPTION while building the URL:</error>');
            $output->writeln(get_class($e) . ': ' . $e->getMessage());
            $output->writeln($e->getTraceAsString());
            return Command::FAILURE;
        }

        if ($connector->capturedUrl === null) {
            $output->writeln('<comment>No URL was built — updateGoogleIndex() returned early');
            $output->writeln('(e.g. posting not found/inaccessible, or no jobapplications_frontend');
            $output->writeln('content element exists on any page).</comment>');
            return Command::FAILURE;
        }

        $output->writeln('<info>[PREVIEW] URL that would be sent to Google (no network call made):</info>');
        $output->writeln($connector->capturedUrl);
        $output->writeln('');
        $output->writeln('Check that this URL points to your real, live domain, resolves to the');
        $output->writeln('correct posting, and uses the expected speaking-URL format. Re-run with');
        $output->writeln('--live once you\'re ready to actually submit it to Google.');

        return Command::SUCCESS;
    }

    private function runLive(OutputInterface $output, int $uid, bool $delete, mixed $posting): int
    {
        $output->writeln('<comment>--live: this will make a REAL call to Google if key_path is configured.</comment>');
        $output->writeln("Calling updateGoogleIndex($uid, " . ($delete ? 'true' : 'false') . ')...');

        try {
            $result = $this->realConnector->updateGoogleIndex($uid, $delete, $posting);

            $output->writeln('<info>No exception thrown.</info>');
            $output->writeln('Connector returned: ' . var_export($result, true));
            $output->writeln('');
            $output->writeln('(Flash message detail is not readable here under CLI. For the actual');
            $output->writeln(' Google response body, use test-google-publish.php instead, which');
            $output->writeln(' bypasses TYPO3 entirely and prints the raw HTTP response.)');

            return $result ? Command::SUCCESS : Command::FAILURE;
        } catch (\Throwable $e) {
            $output->writeln('<error>EXCEPTION:</error>');
            $output->writeln(get_class($e) . ': ' . $e->getMessage());
            $output->writeln($e->getTraceAsString());

            return Command::FAILURE;
        }
    }
}
