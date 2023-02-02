<?php

require __DIR__ . '/../vendor/autoload.php';  # load Composer autoloader

Tester\Environment::setup();               # initialization of Nette Tester

// and other configurations (just an example, in our case they are not needed)
date_default_timezone_set('Europe/Prague');
define('TMP_DIR', '/tmp/app-tests');

function test(string $description, Closure $fn): void
{
	echo $description, "\n";
	$fn();
}