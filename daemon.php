<?php

chdir(dirname(__DIR__));

require_once('vendor/autoload.php');
require_once('config.php');
require_once('SimpleReceiver.php');

use Acme\AmqpWrapper\SimpleReceiver;

$receiver = new SimpleReceiver();
$receiver->listen();