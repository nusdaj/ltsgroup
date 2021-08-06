<?php

require_once dirname(__FILE__).'/omise-plugin/helpers/charge.php';
require_once dirname(__FILE__).'/omise-plugin/helpers/currency.php';
require_once dirname(__FILE__).'/omise-plugin/helpers/transfer.php';
require_once dirname(__FILE__).'/omise-php/lib/Omise.php';

// Define version of Omise-OpenCart
if (!defined('OMISE_OPENCART_VERSION'))
    define('OMISE_OPENCART_VERSION', '2.3');

// Just mockup
$datetime = new DateTime('now');
$datetime->format('Y-m-d\TH:i:s\Z');
if (!defined('OMISE_OPENCART_RELEASED_DATE'))
    define('OMISE_OPENCART_RELEASED_DATE', $datetime->format('Y-m-d\TH:i:s\Z'));

$opencart_version = defined('VERSION') ? " OpenCart/".VERSION : "";

// Define 'OMISE_USER_AGENT_SUFFIX'
if (!defined('OMISE_USER_AGENT_SUFFIX'))
    define('OMISE_USER_AGENT_SUFFIX', "OmiseOpenCart/".OMISE_OPENCART_VERSION.$opencart_version);

// Define 'OMISE_API_VERSION'
if(!defined('OMISE_API_VERSION'))
    define('OMISE_API_VERSION', '2019-07-27');


// dummy class
class Omise { }

?>