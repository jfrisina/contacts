<?php
/**
 * add all files and functions that will be used on every page
 */
// Imports
 require 'db.php';
 require __DIR__ .'/Contact.php';
 require __DIR__ .'/kint.phar'; // current working directory of this

 // add alias for kint
 function kint(...$v) {
 d( ...$v );
 }
 Kint::$aliases[] = 'kint';

