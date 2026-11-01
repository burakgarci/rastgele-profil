<?php

$ayar = require __DIR__ . '/bootstrap.php';
set_time_limit((int) $ayar['zaman_asimi']);

(new RastgeleProfil\Api($ayar))->isle($_GET, $_SERVER)->gonder();
