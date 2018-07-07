<?php

namespace FcPhp\Cookie\Interfaces
{
	use FcPhp\Crypto\Interfaces\ICrypto;
	
	interface ICookie
	{
		public function __construct(string $key, array $cookies, ?ICrypto $crypto = null, string $pathKeys = null, string $customKey = null);

		public function set(string $key, $content, string $customKey = null) :void;

		public function get(string $key = null);
	}
}