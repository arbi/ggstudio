<?php

$dirs = [
	'/var/www/ggs/api/Controller',
	'/var/www/ggs/api/Service',
	'/var/www/ggs/api/Config'
];

foreach ($dirs as $dir) {
	$directory = new RecursiveDirectoryIterator($dir);
	$flattened = new RecursiveIteratorIterator($directory);

	$files = new RegexIterator(
		$flattened, 
		'#^(?:[A-Z]:)?(?:/(?!\.Trash)[^/]+)+/[^/]+\.(?:php)$#Di'
	);

	foreach($files as $file) {
	    require $file;
	}
}