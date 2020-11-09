<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Account\Firm;
use Advecs\Billing\Account\User;
use Advecs\Billing\Exception\MySQLException;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\Search\Search;
use mysqli;
use mysqli_result;

class MySQLStorage implements StorageInterface
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
     * @param int $id
     * @param int $type
     * @return Account
     * @throws MySQLException
     */
    public function getAccount(int $id, int $type = Account::TYPE_USER): Account
    {
        $tableName = 'billing_account';

        $sql = 'SELECT ';
        $sql .= 'account_balance, ';
        $sql .= 'account_balance_bonus, ';
        $sql .= 'id ';
        $sql .= 'FROM ' . $tableName . ' ';
        $sql .= 'WHERE id_account = "%d" ';
        $sql .= 'AND id_type = "%d" ';
        $sql = sprintf($sql, $id, $type);
        $row = $this->getRow($sql);
        if ($row) {
            $account = intval($row['id']);
            $balance = floatval($row['account_balance']);
            $balance_bonus = floatval($row['account_balance_bonus']);
        } else {
            $time = time();
            $sql = 'INSERT INTO ' . $tableName . ' (id_type, id_account, account_add, account_update) ';
            $sql .= 'VALUES ("%d", "%d", "%d", "%d")';
            $sql = sprintf($sql, $type, $id, $time, $time);
            $account = $this->insert($sql);
            $balance = 0;
            $balance_bonus = 0;
        }

        if ($type === Account::TYPE_FIRM) {
            return new Firm($account, $balance);
        }
        return (new User($account, $balance))
            ->setBalanceBonus($balance_bonus);
    }

    /**
     * @param Posting $hPostingCredit
     * @return bool
     * @throws MySQLException
     */
    public function addRuble(Posting $hPostingCredit): bool
    {
        try {
            $hAccount = $hPostingCredit->getTo();
            $hAccount->changeBalance($hPostingCredit->getAmount());

            $this->query('SET AUTOCOMMIT = 0');
            $this->savePosting($hAccount, $hPostingCredit);
            $this->saveBalance($hAccount);
            $this->query('COMMIT');
            $this->query('SET AUTOCOMMIT = 1');
        } catch (MySQLException $hException) {
            $this->query('ROLLBACK');
            $this->query('SET AUTOCOMMIT = 1');
            throw $hException;
        }
        return true;
    }

    /**
     * @param Posting $hPostingCredit
     * @return bool
     * @throws MySQLException
     */
    public function addBonus(Posting $hPostingCredit): bool
    {
        try {
            /** @var User $hUser */
            $hUser = $hPostingCredit->getTo();
            $hUser->changeBalanceBonus($hPostingCredit->getAmount());

            $this->query('SET AUTOCOMMIT = 0');
            $this->savePostingBonus($hUser, $hPostingCredit);
            $this->saveBalanceBonus($hUser);
            $this->query('COMMIT');
            $this->query('SET AUTOCOMMIT = 1');
        } catch (MySQLException $hException) {
            $this->query('ROLLBACK');
            $this->query('SET AUTOCOMMIT = 1');
            throw $hException;
        }
        return true;
    }

    public function transferRuble(Posting $hPostingCredit): bool
    {
        return true;
    }

    public function getPosting(Search $hSearch): array
    {
        return [];
    }

    public function reCountRuble(Account $hAccount): float
    {
        return 0;
    }

    public function reCountBonus(Account $hAccount): float
    {
        return 0;
    }

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return int
     * @throws MySQLException
     */
    protected function savePosting(Account $hAccount, Posting $hPosting)
    {
        $tableName = 'billing_posting';
        $sql = 'INSERT INTO ' . $tableName . ' (id_account, posting_amount, posting_comment, posting_day, posting_add) ';
        $sql .= 'VALUES ("%d", "%f", "%s", "%d", "%f")';
        $sql = sprintf($sql,
            $hAccount->getId(),
            $hPosting->getAmount(),
            $hPosting->getComment(),
            strtotime(date('Y-m-d')),
            microtime(true)
        );
        return $this->insert($sql);
    }

    /**
     * @param User $hUser
     * @param Posting $hPosting
     * @return int
     * @throws MySQLException
     */
    protected function savePostingBonus(User $hUser, Posting $hPosting)
    {
        $tableName = 'billing_posting_bonus';
        $sql = 'INSERT INTO ' . $tableName . ' (id_account, posting_amount, posting_comment, posting_day, posting_add) ';
        $sql .= 'VALUES ("%d", "%f", "%s", "%d", "%f")';
        $sql = sprintf($sql,
            $hUser->getId(),
            $hPosting->getAmount(),
            $hPosting->getComment(),
            strtotime(date('Y-m-d')),
            microtime(true)
        );
        return $this->insert($sql);
    }

    /**
     * @param Account $hAccount
     * @return bool
     * @throws MySQLException
     */
    protected function saveBalance(Account $hAccount)
    {
        $tableName = 'billing_account';
        $sql = 'UPDATE ' . $tableName . ' ';
        $sql .= 'SET account_balance = "' . $hAccount->getBalance() . '" ';
        $sql .= 'WHERE id_account = "' . $hAccount->getId() . '" ';
        return $this->update($sql);
    }

    /**
     * @param User $hAccount
     * @return bool
     * @throws MySQLException
     */
    protected function saveBalanceBonus(User $hAccount)
    {
        $tableName = 'billing_account';
        $sql = 'UPDATE ' . $tableName . ' ';
        $sql .= 'SET account_balance_bonus = "' . $hAccount->getBalanceBonus() . '" ';
        $sql .= 'WHERE id_account = "' . $hAccount->getId() . '" ';
        return $this->update($sql);
    }

    // ---------- функции для работы с базой ----------

    /**
     * @param string $sql
     * @return array
     * @throws MySQLException
     */
    public function getRow(string $sql): array
    {
        $result = $this->getQueryResult($sql);
        if ($result) {
            return mysqli_fetch_assoc($result) ?? [];
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
     * @param $sql
     * @return bool
     * @throws MySQLException
     */
    public function query($sql): bool
    {
        $result = $this->getQueryResult($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * @param string $sql
     * @return mysqli_result|true|false|null
     * @throws MySQLException
     */
    protected function getQueryResult(string $sql)
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
        $this->query('SET NAMES utf8');
        return true;
    }

    public function __destruct()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}