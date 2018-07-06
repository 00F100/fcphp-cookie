<?php

namespace FcPhp\Cookie\Facades
{
	use FcPhp\Crypto\Crypto;
	use FcPhp\Cookie\Cookie;
	use FcPhp\Cookie\Interfaces\ICookie;
	use FcPhp\Cookie\Exceptions\PathKeyNotFoundException;

	class CookieFacade
	{
		private static $instance;

		public function getInstance(array $cookies, string $nonce = null, string $pathKeys = null) :ICookie
		{
			if(!self::$instance instanceof ICookie) {
				$crypto = null;
				if(!empty($nonce)) {
					if(empty($pathKeys)) {
						throw new PathKeyNotFoundException();
					}
					$crypto =  new Crypto($nonce);
					self::$instance = new Cookie($cookies, $crypto, $pathKeys);
				}else{
					self::$instance = new Cookie($cookies);
				}
			}
			return self::$instance;
		}
	}
}