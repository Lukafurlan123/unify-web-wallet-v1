<?php
namespace unify\database;

use PDO;
use unify\Configuration;

/**
 * Class    Database
 * @package unify\database
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Database
{

    /**
     * Protected variable that holds MySQL
     * connection
     *
     * @var PDO
     */
    protected $connection;

    /**
     * Types of queries supported by database
     * class. Each query type has its own method
     * name assigned which is later used to
     * call that method
     *
     * @var array
     */
    private $queryTypes = array(
        "SELECT" => "selectionHandler",
        "INSERT" => "insertionHandler",
        "UPDATE" => "updateHandler"
    );

    /**
     * Database constructor. This method starts
     * MySQL connection by using PDO library.
     */
    public function __construct()
    {
        $this->connection = new PDO("mysql:host=" . Configuration::MYSQL_HOST . ";dbname=" . Configuration::MYSQL_DATABASE . "", "" . Configuration::MYSQL_USERNAME . "", "" . Configuration::MYSQL_PASSWORD . "");
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * This method starts query building process
     * by calling method which is assigned to
     * specific query type.
     *
     * @param null $queryType
     * @param $parameters
     * @return string
     */
    public function buildQuery($queryType = null, $parameters)
    {
        /*
         * Checks if query type exists.
         */
        if ($this->queryTypes[$queryType] != null) {
            /*
             * If query exists this code calls method
             * by using its name and assigns parameters
             * to it
             */
            if (method_exists($this, $this->queryTypes[$queryType])) {
                $method = $this->queryTypes[$queryType];
                return $this->$method($parameters);
            }
        } else {
            /*
             * If query type is non existant this returns
             * error in json format
             */
            return json_encode(array("ERROR" => "Invalid Query Type"));
        }

    }

    /**
     * Function assigned to SELECT query type
     * it takes parameters and builds SELECT
     * query.
     *
     * @param $parameters
     * @return array|PDOStatement|string|stdClass
     */
    public function selectionHandler($parameters)
    {

        if (!isset($parameters["table_name"])) {
            return json_encode(array("ERROR" => "Invalid table name specified"));
        }

        $count = 0;

        $query = null;
        $query .= "SELECT ";

        if (isset($parameters["columns"])) {
            if (is_array($parameters["where"])) {
                foreach ($parameters["columns"] as $param) {
                    $query .= $param;
                    $count++;
                    if ($count < count($parameters["columns"])) {
                        $query .= ", ";
                    }
                }
            } else {
                $query .= $parameters["columns"];
            }
        } else {
            $query .= "* ";
        }

        $count = 0;

        $query .= " FROM " . Configuration::MYSQL_PREFIX . $parameters["table_name"] . " WHERE ";

        if (isset($parameters["where"])) {
            if (is_array($parameters["where"])) {
                foreach ($parameters["where"] as $param) {
                    $query .= $param;
                    $count++;
                    if ($count < count($parameters["where"])) {
                        $query .= " AND ";
                    }
                }
            } else {
                $query .= $parameters["where"];
            }
        }

        if (isset($parameters["order"])) {
            $query .= " ORDER BY " . $parameters["order"];
        }
        if (isset($parameters["limit"])) {
            $query .= " LIMIT " . $parameters["limit"];
        }

        if (isset($parameters["group"])) {
            $query .= " ORDER BY " . $parameters["group"];
        }

        if (isset($parameters["having"])) {
            $query .= " HAVING " . $parameters["having"];
        }

        if ($parameters["prepare"] != null) {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($parameters["prepare"]);
            if (isset($parameters["flag"])) {
                if ($parameters["flag"] == 1) {
                    $data = $stmt->fetchAll();
                } else if ($parameters["flag"] == 2) {
                    $data = $stmt->fetch(PDO::FETCH_OBJ);
                } else if ($parameters["flag"] == 3) {
                    $data = $stmt->fetch(PDO::FETCH_BOTH);
                }
            } else {
                $data = $stmt->fetchAll();
            }
            return $data;
        } else {
            return $this->connection->query($query);
        }

    }

    /**
     * Function assigned to INSERT query type,
     * it builds INSERT query from given parameters
     *
     * @param $parameters
     * @return bool|string
     */
    public function insertionHandler($parameters)
    {

        if (!isset($parameters["table_name"])) {
            return json_encode(array("ERROR" => "Invalid table name specified"));
        }

        $count = 0;
        $query = null;

        $query .= "INSERT INTO " . Configuration::MYSQL_PREFIX . $parameters["table_name"] . " (";

        foreach ($parameters["columns"] as $key => $value) {
            $query .= $key;
            $count++;
            if ($count < count($parameters["columns"])) {
                $query .= ", ";
            }
        }

        $count = 0;
        $query .= ") VALUES (";

        foreach ($parameters["columns"] as $key => $value) {
            $query .= ":" . $key;
            $count++;
            if ($count < count($parameters["columns"])) {
                $query .= ", ";
            }
        }

        $count = 0;
        $query .= ") ON DUPLICATE KEY UPDATE ";

        foreach ($parameters["columns"] as $key => $value) {
            $query .= $key . " = :" . $key;
            $count++;
            if ($count < count($parameters["columns"])) {
                $query .= ", ";
            }
        }

        $bindArray = array();

        foreach ($parameters["columns"] as $key => $value) {
            $bindArray[":".$key] = $value;
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($bindArray);

        return true;
    }

    /**
     * @param $parameters
     * @return string
     */
    public function updateHandler($parameters)
    {

        if (!isset($parameters["table_name"])) {
            return json_encode(array("ERROR" => "Invalid table name specified"));
        }

        $count = 0;
        $query = null;

        $query .= "UPDATE " . Configuration::MYSQL_PREFIX . $parameters["table_name"] . " SET ";

        if (is_array($parameters["columns"])) {
            foreach ($parameters["columns"] as $key => $value) {
                $query .= $value . " = :" . $value;
                $count++;
                if ($count < count($parameters["columns"])) {
                    $query .= ", ";
                }
            }
        } else {
            $query .= $parameters["columns"] . " = :" . $parameters["columns"];
        }

        $query .= " WHERE ";

        if (isset($parameters["where"])) {
            if (is_array($parameters["where"])) {
                foreach ($parameters["where"] as $param) {
                    $query .= $param;
                    $count++;
                    if ($count < count($parameters["where"])) {
                        $query .= " AND ";
                    }
                }
            } else {
                $query .= $parameters["where"];
            }
        }

        if (isset($parameters["order"])) {
            $query .= " ORDER BY " . $parameters["order"];
        }
        if (isset($parameters["limit"])) {
            $query .= " LIMIT " . $parameters["limit"];
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($parameters["prepare"]);

    }

}

