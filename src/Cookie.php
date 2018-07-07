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
		/**
		 * @const Key to cookie into $_COOKIE
		 */
		const COOKIE_KEY_HASH = 'fcphp-cookie';

		/**
		 * @const Time to live of cache
		 */
		const COOKIE_TTL = 84000;

		/**
		 * @var FcPhp\Crypto\Interfaces\ICrypto
		 */
		private $crypto;

		/**
		 * @var string Path to save keys of crypto
		 */
		private $pathKeys;

		/**
		 * @var array Cookies to manipulate
		 */
		private $cookies = [];

		/**
		 * @var string Key into $_COOKIE variable
		 */
		private $key;

		/**
		 * Method to construct instance of Cookie
		 *
		 * @param string $key Key into $_COOKIE of Cookie
		 * @param array $cookies Variable $_SESSION
		 * @param FcPhp\Crypto\Interfaces\ICrypto $crypto Instance of Crypto to encode content of Cookie
		 * @param string $pathKeys Path to save keys of Crypto
		 * @param string $customKey Custom key to generate crypt-key
		 * @return void
		 */
		public function __construct(string $key, array $cookies, ?ICrypto $crypto = null, string $pathKeys = null, string $customKey = null)
		{
			$this->key = $key;
			if($crypto instanceof ICrypto) {
				$this->crypto = $crypto;
				if(empty($pathKeys)) {
					throw new PathKeyNotFoundException();
				}
				$this->pathKeys = $pathKeys;
			}
			if(isset($cookies[$this->key])) {
				if($crypto instanceof ICrypto) {
					$keyCrypto = $this->getKey(md5(!empty($customKey) ? $customKey : self::COOKIE_KEY_HASH));
					$this->cookies = $this->crypto->decode($keyCrypto, $cookies[$this->key]);
				}else{
					$this->cookies = unserialize(base64_decode($cookies[$this->key]));
				}
			}
		}

		/**
		 * Method to set new information into Cookie
		 *
		 * @param string $key Key into $_COOKIE of Cookie
		 * @param string $content Content to insert into Cookie
		 * @param string $customKey Custom key to generate crypt-key
		 * @return void
		 */
		public function set(string $key, $content, string $customKey = null) :void
		{
			array_dot($this->cookies, $key, $content);
			if($this->crypto instanceof ICrypto) {
				$keyCrypto = $this->getKey(md5(!empty($customKey) ? $customKey : self::COOKIE_KEY_HASH));
				$value = $this->crypto->encode($keyCrypto, $this->cookies);
			}else{
				$value = base64_encode(serialize($this->cookies));
			}
			try {
				setcookie($this->key, $value, time() + self::COOKIE_TTL);
			} catch(Exception $e) { }
		}

		/**
		 * Method to get information into Cookie
		 *
		 * @param string $key Key into $_COOKIE of Cookie
		 * @return mixed
		 */
		public function get(string $key = null)
		{
			return array_dot($this->cookies, $key);
		}

		/**
		 * Method to generate crypt-key or load into cache
		 *
		 * @param string $hash Hash to generate crypt-key
		 * @return string
		 */
		private function getKey(string $hash) :string
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