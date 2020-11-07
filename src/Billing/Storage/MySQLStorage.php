<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Exception\MySQLException;
use mysqli;
use mysqli_result;

class MySQLStorage
{
    protected $host = '';
    protected $user = '';
    protected $password = '';
    protected $database = '';
    protected $port = 3306;

    /** @var mysqli */
    protected $connection;

    /**
     * MySQLStorage constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param int $port
     */
    public function __construct(string $host, string $user, string $password, string $database, int $port = 3306)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }

    /**
     * @param string $sql
     * @return array
     * @throws MySQLException
     */
    public function getRow(string $sql): array
    {
        $result = $this->getQueryResult($sql);
        if ($result) {
            return mysqli_fetch_assoc($result);
        }
        return [];
    }

    /**
     * @param string $sql
     * @return array
     * @throws MySQLException
     */
    public function getRows(string $sql): array
    {
        $result = $this->getQueryResult($sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $items = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $items[] = $row;
                }
                return $items;
            }
        }
        return [];
    }

    /**
     * @param string $sql
     * @return int
     * @throws MySQLException
     */
    public function insert(string $sql): int
    {
        $result = $this->getQueryResult($sql);
        if ($result) {
            return intval(mysqli_insert_id($this->connection));
        }
        return 0;
    }

    /**
     * @param string $sql
     * @return bool
     * @throws MySQLException
     */
    public function update(string $sql): bool
    {
        $result = $this->getQueryResult($sql);
        if ($result) {
            $errorNumber = mysqli_errno($this->connection);
            $affectedRows = mysqli_affected_rows($this->connection);
            if ($affectedRows > 0) {
                return true;
            } elseif ($affectedRows == 0 && $errorNumber == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $sql
     * @return mysqli_result
     * @throws MySQLException
     */
    protected function getQueryResult(string $sql): mysqli_result
    {
        $result = null;
        if (!$this->connection) {
            $this->connect();
        }
        $result = mysqli_query($this->connection, $sql);
        $error = mysqli_error($this->connection);
        if ($error != '') {
            throw (new MySQLException('ошибка при выполнении запроса'))
                ->setSQL($sql)
                ->setError($error)
                ->setErrorNumber(mysqli_errno($this->connection));
        }
        return $result;
    }

    /**
     * @return bool
     * @throws MySQLException
     */
    protected function connect(): bool
    {
        $this->connection = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->database,
            $this->port
        );
        if (!$this->connection) {
            throw (new MySQLException('ошибка соединения с базой'))
                ->setError(mysqli_connect_error())
                ->setErrorNumber(mysqli_connect_errno());
        }
        return true;
    }

    public function __destruct()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}