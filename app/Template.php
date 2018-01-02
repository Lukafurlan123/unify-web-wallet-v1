<?php

namespace unify;

use unify\modules\UnifyPrice;

/**
 * Abstract template class which will be extended
 * in views and used to construct full page from
 * different parts.
 *
 * Class Template
 * @package unify
 * @author  Luka Furlan <luka.furlan9@gmail.com>
 */
abstract class Template {

    /**
     * Variable that holds database connection
     * if its set.
     *
     * @var null
     */
    protected $database = null;

    /**
     * Variable that holds unify price
     *
     * @var int
     */
    protected $unifyPrice = 0;

    /**
     * Represents title of website. Whatever
     * is set here will be set between
     * <title> tags
     *
     * @return string
     */
    public abstract function title();

    /**
     * Represents active navigation link.
     * It can be set to "home", "send",
     * "history", "settings", "addresses",
     * "login", "register"
     *
     * @return string
     */
    public abstract function active();

    /**
     * Represents main content of website
     * it can be set to anything
     *
     * @return mixed
     */
    public abstract function body();

    /**
     * This will contain location to custom javascript
     * file which will be loaded in footer of website
     *
     * @return mixed
     */
    public abstract function customScript();

    /**
     * Constructs parts of web page to complete
     * page which includes head, body and footer.
     * Head has set title and active navigation menu
     *
     * Template constructor.
     * @param bool $login
     * @param null $database
     */
    public function __construct($login = false, $database = null)
    {
        $this->unifyPrice = UnifyPrice::getPrice();

        if($database) {
            $this->database = $database;
        }

        if($login) {
            if(Core::authenticateUser($database)) {
                header("Location: " . Configuration::DASHBOARD_URL);
                return;
            }
            $this->loginHead($this->title(), $this->active());
            $this->body();
            $this->footer();
        } else {
            $this->head($this->title(), $this->active());
            $this->body();
            $this->footer();
        }
    }

    public function head($title, $active)
    {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>'.$title.'</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/css/custom.css">
            <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        </head>
        <body>
        
        <main class="container">
            <section id="logo">
                <h1><a href="dashboard"><img src="assets/images/icon.png" class="logo-icon"></a></h1>
            </section>
            <section id="unify-price">
                ';
        UnifyPrice::showPrice();
        echo '
            </section>
            <section id="content">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <ul class="nav navbar-nav">';
                        if($active == "home") {
                            echo '<li class="active"><a href="dashboard">Home</a></li>';
                        } else {
                            echo '<li><a href="dashboard">Home</a></li>';
                        }
                        if($active == "send") {
                            echo '<li class="active"><a href="send">Send</a></li>';
                        } else {
                            echo '<li><a href="send">Send</a></li>';
                        }
                        if($active == "history") {
                            echo '<li class="active"><a href="history">Transaction History</a></li>';
                        } else {
                            echo '<li><a href="history">Transaction History</a></li>';
                        }
                        if($active == "addresses") {
                            echo '<li class="active"><a href="addresses">Addresses</a></li>';
                        } else {
                            echo '<li><a href="addresses">Addresses</a></li>';
                        }
        if($active == "settings") {
            echo '<li class="active"><a href="settings">Settings</a></li>';
        } else {
            echo '<li><a href="settings">Settings</a></li>';
        }
                        echo '
                        </ul>
                        <ul class="nav navbar-nav navbar-right navbar-right-custom">
                            <li><a href="mailto:support@unify.today">Contact Us</a></li>
                            <li><a href="logout">Log Out</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="inner-container">
        ';
    }

    public function loginHead($title, $active)
    {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>'.$title.'</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/css/custom.css">
            <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        </head>
        <body>
        
        <main class="container">
            <section id="logo">
                <h1><a href="dashboard"><img src="assets/images/icon.png" class="logo-icon"></a></h1>
            </section>
            <section id="unify-price">
                ';
        UnifyPrice::getPrice();
        echo '
            </section>
            <section id="content">
            <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <ul class="nav navbar-nav">';
                        if($active == "login") {
                            echo '<li class="active"><a href="login">Sign in</a></li>';
                        } else {
                            echo '<li><a href="login">Sign in</a></li>';
                        }
                        if($active == "register") {
                            echo '<li class="active"><a href="register">Create an account</a></li>';
                        } else {
                            echo '<li><a href="register">Create an account</a></li>';
                        }

                        echo '
                        </ul>
                        <ul class="nav navbar-nav navbar-right" style="position: relative; left: 0px;">
                            <li><a href="mailto:support@unify.today">Contact Us</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="inner-container">
        ';
    }

    public function footer()
    {
        echo '
                </div>
            </section>
        </main>
        <br>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        ';
        if($this->customScript()) {
            echo '<script src="assets/js/'.$this->customScript().'"></script>';
        }
        echo '
        </body>
        </html>
        ';
    }

}