<?php

function p($str) {
	echo $str . PHP_EOL;
}

function cfg($key) {
	return SSpy_Config::get($key);
}
