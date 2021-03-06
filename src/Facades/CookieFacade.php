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

        /**
         * Facade to return instance of Cookie
         * 
         * @param string $key Key into $_COOKIE of Cookie
         * @param array $cookies Variable $_SESSION
         * @param string $nonce Nonce to Crypto
         * @param string $pathKeys Path to save keys of Crypto
         * @param string $forceNewInstance Force create new instance
         * @return FcPhp\Cookie\Interfaces\ICookie
         */
        public static function getInstance(string $key, array $cookies, string $nonce = null, string $pathKeys = null, bool $forceNewInstance = false) :ICookie
        {
            if(!self::$instance instanceof ICookie || $forceNewInstance) {
                $crypto = null;
                if(!empty($nonce)) {
                    if(empty($pathKeys)) {
                        throw new PathKeyNotFoundException();
                    }
                    $crypto =  new Crypto($nonce);
                    self::$instance = new Cookie($key, $cookies, $crypto, $pathKeys);
                }else{
                    self::$instance = new Cookie($key, $cookies);
                }
            }
            return self::$instance;
        }
    }
}
