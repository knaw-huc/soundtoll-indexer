<?php
include('db.class.php');
include('functions.php');

define('PASSAGES', 10000);
define("MAPPING_URL", "http://localhost:9200/sont");
define('INDEX_URL', 'http://localhost:9200/sont/_doc');
define("INDEX_PLACES_URL", 'http://localhost:9200/sont/port');
define("MAPPING_FILE", "mapping.json");

indexPassages(PASSAGES);