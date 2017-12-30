<?php

namespace unify\modules;

use unify\Configuration;

/**
 * Class    UnifyPrice
 * @package unify\modules
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class UnifyPrice {

    /**
     * Shows current unify price. Text color
     * depends on percentage change in past 24
     * hours. if price went up it shows green
     * and if it went down it shows red
     */
    public static function showPrice()
    {
        $data = json_decode(file_get_contents(Configuration::API_LOCATION));

        if ($data[0]->percent_change_24h > 0) {
            echo '
            <span class="green">&uarr; $' . $data[0]->price_usd . '</span>
            ';
        } else {
            echo '
            <span class="red">&darr; $' . $data[0]->price_usd . '</span>
            ';
        }
    }

    /**
     * Returns current price of unify in dollars
     *
     * @return mixed
     */
    public static function getPrice()
    {
        $data = json_decode(file_get_contents(Configuration::API_LOCATION));
        return $data[0]->price_usd;
    }

}