<?php 
if ( ! function_exists('key_gen'))
{
	function key_gen($len) {

		$alphabet = "abcdefghijklmnopqrstuwxyz01234567890";

		$key = array (); // remember to declare $pass as an array

		$alphaLength = strlen ( $alphabet ) - 1; // put the length -1 in cache

		for($i = 0; $i < $len; $i ++) {

			$n = rand ( 0, $alphaLength );

			$key [] = $alphabet [$n];

		}

		return implode ( $key ); // turn the array into a string
	}  
}