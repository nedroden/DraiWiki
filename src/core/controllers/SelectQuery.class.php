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

use PDOException;

class SelectQuery extends Query {

    public function __construct($query) {
    	parent::__construct($query);
    	$this->setPrefix();
    }

    public function execute() {
        try {
            $pendingQuery = $this->connection->prepare($this->query);
        }
        catch (PDOException $e) {
            die('Could not execute query: ' . $e->getMessage());
        }
        try {
            foreach ($this->params as $paramKey => $paramValue) {
                $pendingQuery->bindValue(':' . $paramKey, $paramValue);
            }
            $pendingQuery->execute();
            return $pendingQuery->fetchAll();
        }
        catch (PDOException $e) {
            die('Could not execute query: ' . $e->getMessage());
        }
    }
}
