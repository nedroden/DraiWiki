<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

namespace DraiWiki\src\core\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\errors\DatabaseException;
use PDOException;

class ModificationQuery extends Query {

    public function execute() : void {
        try {
            $pendingQuery = $this->connection->prepare($this->query);
        }
        catch (PDOException $e) {
            if ($this->hasTemplate)
                (new DatabaseException($e->getMessage(), $this->toString()))->trigger();

            die('Could not prepare query.');
        }
        try {
            foreach ($this->params as $paramKey => $paramValue) {
                $pendingQuery->bindValue(':' . $paramKey, $paramValue);
            }
            $pendingQuery->execute();
        }
        catch (PDOException $e) {
            if ($this->hasTemplate)
                (new DatabaseException($e->getMessage(), $this->toString()))->trigger();

            die('Could not execute query.');
        }
    }
}