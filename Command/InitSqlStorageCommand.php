<?php

namespace Bukatov\ApiTokenBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitSqlStorageCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('api-token:init-sql-storage')
            ->setDescription('Creates table in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableName = $this->getContainer()->getParameter('bukatov_api_token.storage.sql.table_name');
        $connection = $this->getContainer()->get('database_connection');

        $sql = <<<SQL
CREATE TABLE `%s` (
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`key`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

        $stmt = $connection->prepare(sprintf($sql, $tableName));

        if ($stmt->execute()) {
            $output->writeln(sprintf('<info>Table "%s" successfully created</info>', $tableName));
        } else {
            $output->writeln(sprintf('<error>Error while creating table "%s"</error>', $tableName));
        }
    }
}