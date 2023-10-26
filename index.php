<?php

require __DIR__.'/vendor/autoload.php';

include 'bootstrap/app.php';

$obRouter->run()->sendResponse();
