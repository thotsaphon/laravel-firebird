<?php
/**
 * Created by PhpStorm.
 * User: Thotsaphon
 * Date: 20/5/2561
 * Time: 0:37
 */

namespace Firebird;

use PDO as BasePDO;

class PDO extends BasePDO
{
    private $dict;

    public function __construct ($dsn, $username, $passwd, $options = []) {
        parent::__construct($dsn, $username, $passwd, $options);
        $this->dict = array();
    }

    public function mergeDictionary(array $values) {
        $this->dict = array_merge($this->dict, $values);
    }

    public function lastInsertId($name = 'id') {
        return $this->dict[$name];
    }
}