<?php

namespace Bukatov\ApiTokenBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SqlStorageCleanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api-token:storage:sql:clean')
            ->setDescription('Deletes expired tokens')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableName = $this->getContainer()->getParameter('bukatov_api_token.storage.sql.table_name');
        $connection = $this->getContainer()->get('database_connection');

        $sql = 'DELETE FROM %s WHERE expires_at <= NOW()';

        $stmt = $connection->prepare(sprintf($sql, $tableName));
        $stmt->execute();
        $count = $stmt->rowCount();

        $output->writeln(sprintf('<info>Done! %s expired tokens was deleted</info>', $count));
    }
}