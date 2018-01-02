<?php

namespace unify\views;

use unify\Template;

/**
 * Class    Transactions
 * @package unify\views
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
class Transactions extends Template {


    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public function title()
    {
        return "Unify wallet - Transactions";
    }

    /**
     * Represents active navigation link.
     * It can be set to "home", "send",
     * "history", "settings", "addresses",
     * "login", "register"
     *
     * @return string
     */
    public function active()
    {
        return "history";
    }

    /**
     * Represents main content of website
     * it can be set to anything
     *
     * @return mixed
     */
    public function body()
    {

        $transactions = \unify\modules\Transactions::getTransactions($this->database);

        echo '
        <table class="table table-bordered table-striped" id="alist">
        <thead>
            <tr>
                <td>Time</td>
                <td>Address</td>
                <td>Type</td>
                <td>Amount</td>
                <td>Fee</td>
                <td>Confirmations</td>
            </tr>
        </thead>
        <tbody>
        ';
        foreach($transactions as $transaction) {

            if($transaction['category'] == "send") {
                $tx_type = '<span style="color: #ad1a20;">Sent</span>';
            } else {
                $tx_type = '<span style="color: #29a315;">Received</span>';
            }

            echo '
            <tr>
                <td>'.date('n/j/Y h:i a',$transaction['time']).'</td>
                <td>'.$transaction['address'].'</td>
                <td>'.$tx_type.'</td>
                <td>'.abs($transaction['amount']).'</td>
                ';
            if(isset($transaction['fee'])) {
                echo '<td>'.$transaction['fee'].'</td>';
            } else {
                echo '<td></td>';
            }
            echo '
                <td>'.$transaction['confirmations'].'</td>
            </tr>';

        }
        echo '
        </tbody>
        </table>
        ';
    }

    /**
     * This will contain location to custom javascript
     * file which will be loaded in footer of website
     *
     * @return mixed
     */
    public function customScript()
    {
        return "custom/transactions.js";
    }
}