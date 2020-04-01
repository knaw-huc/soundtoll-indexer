<?php
include('db.class.php');
include('functions.php');

define('PASSAGES', 10000);
define('INDEX_URL', 'http://localhost:9200/sont/_doc');
define("INDEX_PLACES_URL", 'http://localhost:9200/sont/port');

indexPassages(PASSAGES);