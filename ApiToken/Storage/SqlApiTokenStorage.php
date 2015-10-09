<?php

namespace Bukatov\ApiTokenBundle\ApiToken\Storage;

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

    public function get($key)
    {
        $sql = sprintf('SELECT * FROM %s WHERE `key` = :key AND `expires_at` > :current_date_time', $this->tableName);

        $currentDateTime = new \DateTime();

        $params = [
            'key' => $key,
            'current_date_time' => $currentDateTime->format('Y-m-d H:i:s')
        ];

        $result = $this->connection->fetchAssoc($sql, $params);

        $apiToken = isset($result['value']) ? unserialize($result['value']) : null;
        $apiToken = $apiToken instanceof ApiTokenInterface ? $apiToken : null;

        return $apiToken;
    }

    public function set($key, ApiTokenInterface $token, $lifetime = 0)
    {
        $sql = sprintf('INSERT INTO %s (`key`, `value`, expires_at) VALUES (:key, :value, :expires_at) ON DUPLICATE KEY UPDATE `key` = :key, `expires_at` = :expires_at', $this->tableName);

        $expiresAt = new \DateTime();
        $expiresAt->add(\DateInterval::createFromDateString(sprintf('%s seconds', intval($lifetime))));

        $params = [
            'key' => $key,
            'value' => serialize($token),
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ];

        $this->connection->executeQuery($sql, $params);
    }

    public function delete($key)
    {
        $this->connection->delete($this->tableName, ['key' => $key]);
    }
}