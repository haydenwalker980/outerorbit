<?php
    $config = array();

    $config['pr_title'] = "OuterOrbit";
    $config['db_user'] = "root";
    $config['db_pass'] = "";
    $config['db_name'] = "spacemy2";
    $config['db_host'] = "127.0.0.1";

    $config['recaptcha_secret'] = "";
    $config['recaptcha_sitekey'] = "";

    $config['steam_web_id'] = "";

    require($_SERVER['DOCUMENT_ROOT'] . '/vendor/smith197/steamauthentication/steamauth/steamauth.php');
?>
