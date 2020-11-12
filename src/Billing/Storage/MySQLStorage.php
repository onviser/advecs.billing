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
    protected $mysqli;

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
     * @param int $idExternal
     * @param int $type
     * @return Account
     * @throws MySQLException
     */
    public function getAccount(int $idExternal, int $type = Account::TYPE_USER): Account
    {
        $tableName = 'billing_account';

        $sql = 'SELECT ';
        $sql .= 'account_balance, ';
        $sql .= 'account_balance_bonus, ';
        $sql .= 'id_account ';
        $sql .= 'FROM ' . $tableName . ' ';
        $sql .= 'WHERE id_external = "%d" ';
        $sql .= 'AND id_type = "%d" ';
        $sql = sprintf($sql, $idExternal, $type);
        $row = $this->getRow($sql);
        if ($row) {
            $account = intval($row['id_account']);
            $balance = floatval($row['account_balance']);
            $balance_bonus = floatval($row['account_balance_bonus']);
        } else {
            $time = time();
            $sql = 'INSERT INTO ' . $tableName . ' (id_type, id_external, account_add, account_update) ';
            $sql .= 'VALUES ("%d", "%d", "%d", "%d")';
            $sql = sprintf($sql, $type, $idExternal, $time, $time);
            $account = $this->insert($sql);
            $balance = 0;
            $balance_bonus = 0;
        }

        if ($type === Account::TYPE_FIRM) {
            return new Firm($account, $balance, $balance_bonus);
        }
        return new User($account, $balance, $balance_bonus);
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
            $hAccount = $hPostingCredit->getTo();
            $hAccount->changeBalanceBonus($hPostingCredit->getAmount());

            $this->query('SET AUTOCOMMIT = 0');
            $this->savePostingBonus($hAccount, $hPostingCredit);
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
    public function transferRuble(Posting $hPostingCredit): bool
    {
        try {
            $this->query('SET AUTOCOMMIT = 0');

            $hFrom = $hPostingCredit->getFrom();
            $hTo = $hPostingCredit->getTo();

            // списание
            $hPostingDebit = (new Posting(-1 * $hPostingCredit->getAmount(), $hPostingCredit->getComment()))
                ->setFrom($hFrom)
                ->setTo($hTo);
            $this->savePosting($hFrom, $hPostingDebit);
            $hFrom->changeBalance($hPostingDebit->getAmount());
            $this->saveBalance($hFrom);

            // зачисление
            $this->savePosting($hTo, $hPostingCredit);
            $hTo->changeBalance($hPostingCredit->getAmount());
            $this->saveBalance($hTo);

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
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPosting(Search $hSearch): array
    {
        $where = [];
        if ($hSearch->getAccount() != 0) {
            $where[] = 'id_account = "' . intval($hSearch->getAccount()) . '"';
        }
        if ($hSearch->getAmountFrom() > 0) {
            $where[] = 'ABS(posting_amount) >= "' . floatval($hSearch->getAmountFrom()) . '"';
        }
        if ($hSearch->getAmountTo() > 0) {
            $where[] = 'ABS(posting_amount) <= "' . floatval($hSearch->getAmountTo()) . '"';
        }
        if ($hSearch->getTimeFrom() > 0) {
            $where[] = 'posting_add >= "' . intval($hSearch->getTimeFrom()) . '"';
        }
        if ($hSearch->getTimeTo() > 0) {
            $where[] = 'posting_add <= "' . intval($hSearch->getTimeTo()) . '"';
        }
        if ($hSearch->getComment() !== '') {
            $where[] = 'posting_comment LIKE "%' . strval($hSearch->getComment()) . '%"';
        }

        $tableName = 'billing_posting';
        $sql = 'SELECT ';
        $sql .= '* ';
        $sql .= 'FROM ' . $tableName . ' ';
        if (count($where)) {
            $sql .= 'WHERE ' . implode(' AND ', $where);
        }
        $sql .= 'ORDER BY id DESC ';
        $sql .= 'LIMIT ' . $hSearch->getOffset() . ', ' . $hSearch->getLimit();
        echo $sql . PHP_EOL;

        return [];
    }

    public function getPostingBonus(Search $hSearch): array
    {
        return [];
    }

    /**
     * @param Account $hAccount
     * @return bool
     * @throws MySQLException
     */
    public function reCount(Account $hAccount): bool
    {
        $balance = 0;
        $tableName = 'billing_posting';
        $sql = 'SELECT ';
        $sql .= 'SUM(posting_amount) AS amount ';
        $sql .= 'FROM ' . $tableName . ' ';
        $sql .= 'WHERE id_account = "' . $hAccount->getId() . '"';
        $row = $this->getRow($sql);
        if ($row) {
            $balance = floatval($row['amount']);
        }
        $hAccount->setBalance($balance);

        $balanceBonus = 0;
        $tableNameBonus = 'billing_posting_bonus';
        $sql = 'SELECT ';
        $sql .= 'SUM(posting_amount) AS amount ';
        $sql .= 'FROM ' . $tableNameBonus . ' ';
        $sql .= 'WHERE id_account = "' . $hAccount->getId() . '"';
        $row = $this->getRow($sql);
        if ($row) {
            $balanceBonus = floatval($row['amount']);
        }
        $hAccount->setBalanceBonus($balanceBonus);

        $this->saveBalance($hAccount);

        return true;
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
        return $this->savePostingCommon($hAccount, $hPosting, $tableName);
    }

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return int
     * @throws MySQLException
     */
    protected function savePostingBonus(Account $hAccount, Posting $hPosting)
    {
        $tableName = 'billing_posting_bonus';
        return $this->savePostingCommon($hAccount, $hPosting, $tableName);
    }

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @param $tableName
     * @return int
     * @throws MySQLException
     */
    protected function savePostingCommon(Account $hAccount, Posting $hPosting, $tableName)
    {
        $insert = [
            'id_account'      => '"%d"',
            'id_from'         => '"%d"',
            'id_to'           => '"%d"',
            'posting_amount'  => '"%f"',
            'posting_comment' => '"%s"',
            'posting_day'     => '"%d"',
            'posting_add'     => '"%f"'
        ];
        $sql = 'INSERT INTO ' . $tableName . ' (' . implode(', ', array_keys($insert)) . ') ';
        $sql .= 'VALUES (' . implode(', ', $insert) . ')';
        $sql = sprintf($sql,
            intval($hAccount->getId()),
            $hPosting->getAmount() > 0 && $hPosting->getFrom() ? $hPosting->getFrom()->getId() : 0, // зачисление с какого то счета
            $hPosting->getAmount() < 0 && $hPosting->getTo() ? $hPosting->getTo()->getId() : 0, // перевод на какой то счет
            floatval($hPosting->getAmount()),
            strval($hPosting->getComment()),
            $hPosting->getDay(),
            $hPosting->getTime()
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
        $sql .= 'SET account_balance = "' . $hAccount->getBalance() . '", ';
        $sql .= 'account_balance_bonus = "' . $hAccount->getBalanceBonus() . '" ';
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
            return intval(mysqli_insert_id($this->mysqli));
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
            $errorNumber = mysqli_errno($this->mysqli);
            $affectedRows = mysqli_affected_rows($this->mysqli);
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
        if (!$this->mysqli) {
            $this->connect();
        }
        $result = mysqli_query($this->mysqli, $sql);
        $error = mysqli_error($this->mysqli);
        if ($error != '') {
            throw (new MySQLException('ошибка при выполнении запроса'))
                ->setSQL($sql)
                ->setError($error)
                ->setErrorNumber(mysqli_errno($this->mysqli));
        }
        return $result;
    }

    /**
     * @return bool
     * @throws MySQLException
     */
    protected function connect(): bool
    {
        $this->mysqli = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->database,
            $this->port
        );
        if (!$this->mysqli) {
            throw (new MySQLException('ошибка соединения с базой'))
                ->setError(mysqli_connect_error())
                ->setErrorNumber(mysqli_connect_errno());
        }
        $this->query('SET NAMES utf8');
        return true;
    }

    public function __destruct()
    {
        if ($this->mysqli) {
            mysqli_close($this->mysqli);
        }
    }
}