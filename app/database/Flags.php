<?php
namespace unify\database;

/**
 * Class    Flags
 * @package unify\database
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Flags {

    /**
     * Works for all query types, queries
     * will execute with their normal
     * settings.
     *
     *
     * @example SELECT query type will use
     * fetchAll function
     */
    const NORMAL = 1;

    /**
     * Works only with SELECT query type and
     * uses PDO::FETCH_OBJ flag with fetch
     * function
     */
    const FETCH_ONE = 2;

    /**
     * Works only with SELECT query type and
     * uses PDO::FETCH_BOTH flag with fetch
     * function to select both column name
     * and column number from database
     */
    const FETCH_BOTH = 3;

    /**
     * Represents enabled authenticator type
     */
    const AUTHENTICATOR_ENABLE = 1;

    /**
     * Represents disabled authenticator type
     */
    const AUTHENTICATOR_DISBALE = 0;

}