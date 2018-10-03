<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Command;

use Ruwork\Reminder\Manager\ReminderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class RemindCommand extends Command
{
    use LockableTrait;

    protected static $defaultName = 'ruwork:reminder:remind';

    private $reminder;

    /**
     * {@inheritdoc}
     */
    public function __construct(ReminderInterface $reminder)
    {
        parent::__construct();
        $this->reminder = $reminder;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addArgument('provider', InputArgument::REQUIRED)
            ->addOption('time', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('<error>The command is already running in another process.</error>');

            return 0;
        }

        if (null !== $time = $input->getOption('time')) {
            $time = new \DateTimeImmutable($time);
        }

        $this->reminder->remind($input->getArgument('provider'), $time);

        $output->writeln('<info>Done.</info>');

        $this->release();

        return 0;
    }
}
