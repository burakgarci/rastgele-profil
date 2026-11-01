<?php

$ayar = require __DIR__ . '/../bootstrap.php';

RastgeleProfil\Saglik::rapor($ayar)->gonder();
