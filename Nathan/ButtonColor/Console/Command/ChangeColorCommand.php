<?php

declare(strict_types=1);

namespace Nathan\ButtonColor\Console\Command;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeColorCommand extends Command
{
    private const string CONFIG_PATH = 'design/head/includes';

    /**
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param State $state
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
        private readonly State $state,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('color:change')
            ->setDescription('Change button color across the store via CSS variable injection.')
            ->addArgument('color', InputArgument::REQUIRED, 'Hex color code (e.g., 000000)')
            ->addArgument('storeId', InputArgument::REQUIRED, 'Store ID');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $color = ltrim($input->getArgument('color'), '#');
        $storeId = (int)$input->getArgument('storeId');

        if (!preg_match('/^[a-fA-F0-9]{6}$/', $color)) {
            $output->writeln('<error>Invalid color code. Use 6-digit hex format (e.g., 000000).</error>');
            return Command::FAILURE;
        }

        try {
            $this->state->setAreaCode('adminhtml');
        } catch (LocalizedException $e) {
            $this->logger->error('[ChangeColorCommand]: ' . $e->getMessage());
        }

        $style = '<style>:root { --button-background: #' . $color . '; }</style>';

        $this->config->saveConfig(self::CONFIG_PATH, $style, 'stores', $storeId);
        $output->writeln("<info>Button color updated to #{$color} for store ID {$storeId}.</info>");
        return Command::SUCCESS;
    }
}
