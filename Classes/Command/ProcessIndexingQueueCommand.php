<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Command;

use ITX\Jobapplications\Service\IndexingQueueProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessIndexingQueueCommand extends Command
{
    public function __construct(private readonly IndexingQueueProcessor $processor)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Processes pending Google Indexing API jobs for job postings.');
        $this->addOption(
            'limit',
            'l',
            InputOption::VALUE_REQUIRED,
            'Maximum number of queue entries to process in this run',
            50
        );
        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            'List pending jobs and what would happen, without calling the Google API or modifying the queue'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->processor->process(
            (int)$input->getOption('limit'),
            IndexingQueueProcessor::DEFAULT_MAX_ATTEMPTS,
            (bool)$input->getOption('dry-run')
        );

        foreach ($result['log'] as $line) {
            $output->writeln($line);
        }

        if ($result['processed'] === 0) {
            $output->writeln('<info>No pending indexing jobs.</info>');
            return Command::SUCCESS;
        }

        $output->writeln(sprintf(
            '<info>Processed %d job(s): %d succeeded, %d failed.</info>',
            $result['processed'],
            $result['succeeded'],
            $result['failed']
        ));

        return $result['failed'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
