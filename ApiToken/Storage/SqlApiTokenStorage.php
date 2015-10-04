<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Storage;

use Bukatov\ApiTokenBundle\ApiToken\ApiToken;
use Bukatov\ApiTokenBundle\ApiToken\ApiTokenInterface;
use Doctrine\DBAL\Connection;

class SqlApiTokenStorage implements ApiTokenStorageInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var
     */
    protected $tableName;

    public function __construct(Connection $connection, $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    public function get($token)
    {
        $result = $this->connection->fetchAssoc(sprintf('SELECT * FROM %s WHERE token = :token', $this->tableName), ['token' => $token]);
        $data = $result ? json_decode($result, true) : null;

        return $data ? ApiToken::fromArray($data) : null;
    }

    public function set(ApiTokenInterface $token, $ttl = 0)
    {
        $this->connection->insert($this->tableName, ['token' => $token->getToken(), 'data' => json_encode($token->toArray())]);
    }

    public function delete($token)
    {
        $this->connection->delete($this->tableName, ['token' => $token]);
    }
}