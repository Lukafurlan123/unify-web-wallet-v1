<?php

namespace unify;

use unify\database\Database;

/**
 * Class    Router
 * @package unify
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Router {

    /**
     * Array that holds all routes
     * @var array
     */
    private $routes = array();

    /**
     * Holds parameters of current page we're
     * visiting.
     * @var
     */
    private $params;

    /**
     *
     * Function used to add new route to array of all routes.
     * @param $requestMethod
     * @param $path
     * @param $function
     */
    public function route($requestMethod, $path, $requiresLogin, $function)
    {
        array_push($this->routes, array("path" => $path, "requests" => explode("/", substr($path, 1)), "func" => $function, "requiresLogin" => $requiresLogin, "requestMethod" => $requestMethod));
    }

    /**
     *
     * When this function is ran, it will detect on what page
     * user is on, and it will run route code.
     */
    public function run()
    {
        if (isset($_GET['param'])) {
            $this->params = explode("/", substr(@$_GET['param'], 1));
            $result = "" . $this->findRoute($this->params);
            if ($result != "err") {
                $database = new Database();
                if($this->routes[$result]['requiresLogin']) {
                    if(!Core::authenticateUser($database)) {
                        header("Location: " . Configuration::LOGIN_PATH);
                        return;
                    }
                }

                if ($this->checkRequestMethod($this->routes[$result]["requestMethod"])) {
                    $response = (object)$this->getResponseParams($result);
                    $this->routes[$result]['func']($response, $database);
                }
            } else {
                header('Location: ' . Configuration::DASHBOARD_URL);
            }
        } else {
            header('Location: ' . Configuration::DASHBOARD_URL);
        }
    }

    /**
     *
     * Checks which request method is used and checks if its
     * alloud on current route. Request ethods that are not allowed will
     * return error page.
     * @param $requestMethods
     * @return bool
     */
    public function checkRequestMethod($requestMethods)
    {
        if (in_array($_SERVER['REQUEST_METHOD'], $requestMethods)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * This will return array of parameters that were grabbed from
     * route.
     * @param $routeID
     * @return array
     */
    public function getResponseParams($routeID)
    {
        $tempArr = array();

        if ($this->countParams($this->routes[$routeID]["requests"], true) > 0) {
            foreach ($this->routes[$routeID]["requests"] as $key => $val) {
                if (substr($val, 0, 1) == ":") {
                    $split = explode("{", $val);
                    if (count($split) > 1) {
                        $tempArr[substr($split[0], 1)] = $this->sanitize($this->params[$key], substr($split[1], 0, -1));
                    } else {
                        $tempArr[substr($split[0], 1)] = $this->params[$key];
                    }
                }
            }
        }

        return $tempArr;
    }

    /**
     *
     * This will sanitize parameters grabbed from route with method
     * that was set for each parameter.
     * @param $value
     * @param $method
     * @return mixed
     */
    public function sanitize($value, $method)
    {
        switch ($method) {
            case "i":
                return preg_replace("/[^0-9]/", "", $value);
                break;
            case "s":
                return preg_replace("/[^0-9a-zA-Z_\s]/", "", $value);
                break;
            default:
                return $value;
                break;
        }
    }

    /**
     *
     * Finds route you are currently on and returns its number. If
     * route cant be found, it returns error.
     * @param $value
     * @return int|string
     */
    public function findRoute($value)
    {
        for ($i = 0; $i < count($this->routes); $i++) {
            if(count($value) != count($this->routes[$i]["requests"])) {
                continue;
            }
            $j = 0;
            for ($k = 0; $k < count($this->routes[$i]["requests"]); $k++) {
                if (substr($this->routes[$i]["requests"][$k], 0, 1) != ":") {
                    if ($this->routes[$i]["requests"][$k] == $value[$k]) {
                        $j++;
                    }
                }
            }
            if ($j == $this->countParams($this->routes[$i]["requests"])) {
                if (count($this->routes[$i]["requests"]) == count($value)) {
                    return $i;
                }
            }
        }
        return "err";
    }

    /**
     *
     * Method used to count parameters in route.
     * @param $array
     * @param bool $paramType
     * @return int
     */
    public function countParams($array, $paramType = false)
    {
        $i = 0;
        if ($paramType) {
            foreach ($array as $row) {
                if (substr($row, 0, 1) == ":") {
                    $i++;
                }
            }
        } else {
            foreach ($array as $row) {
                if (substr($row, 0, 1) != ":") {
                    $i++;
                }
            }
        }
        return $i;
    }

    /**
     * Prints out arraay in json format with pretty print
     * enabled.
     * @param $array
     */
    public function printJson($array)
    {
        print_r(json_encode($array, JSON_PRETTY_PRINT));
    }

}