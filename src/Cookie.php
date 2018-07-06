<?php

namespace FcPhp\Cookie
{
	use Exception;
	use FcPhp\Crypto\Crypto;
	use FcPhp\Cookie\Interfaces\ICookie;
	use FcPhp\Crypto\Interfaces\ICrypto;
	use FcPhp\Cookie\Exceptions\PathKeyNotFoundException;
	use FcPhp\Cookie\Exceptions\PathNotPermissionFoundException;

	class Cookie implements ICookie
	{
		const COOKIE_KEY = 'fcphp-cookie';
		const COOKIE_KEY_HASH = 'fcphp-cookie';
		const COOKIE_TTL = 84000;
		private $crypto;
		private $pathKeys;
		private $cookies = [];

		public function __construct(array $cookies, ?ICrypto $crypto = null, string $pathKeys = null)
		{
			if($crypto instanceof ICrypto) {
				$this->crypto = $crypto;
				if(empty($pathKeys)) {
					throw new PathKeyNotFoundException();
				}
				$this->pathKeys = $pathKeys;
			}
			if(isset($cookies[self::COOKIE_KEY])) {
				if($crypto instanceof ICrypto) {
					$key = $this->getKey(md5(self::COOKIE_KEY_HASH));
					$this->cookies = $this->crypto->decode($key, $cookies[self::COOKIE_KEY]);
				}else{
					$this->cookies = base64_decode(unserialize($cookies[self::COOKIE_KEY]));
				}
			}
		}

		public function set(string $key, $content)
		{
			array_dot($this->cookies, $key, $content);
			if($this->crypto instanceof ICrypto) {
				$key = $this->getKey(md5(self::COOKIE_KEY_HASH));
				$value = $this->crypto->encode($key, $this->cookies);
			}else{
				$value = base64_encode(serialize($this->cookies));
			}
			try {
				setcookie(self::COOKIE_KEY, $value, time() + self::COOKIE_TTL);
			} catch(Exception $e) { }
		}

		public function get(string $key = null)
		{
			return array_dot($this->cookies, $key);
		}

		private function getKey(string $hash)
		{
			if(!is_dir($this->pathKeys)) {
				try {
					mkdir($this->pathKeys, 0755, true);
				} catch (Exception $e) {
					throw new PathNotPermissionFoundException($this->pathKeys, 500, $e);
				}
			}
			$filePath = $this->pathKeys . '/' . $hash . '.key';
			if(file_exists($filePath)) {
				return file_get_contents($filePath);
			}
			$key = Crypto::getKey();
			$fopen = fopen($filePath, 'w');
			fwrite($fopen, $key);
			fclose($fopen);
			return $key;
		}
	}
}