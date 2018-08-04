<?php

namespace FcPhp\Cookie\Interfaces
{
    use FcPhp\Crypto\Interfaces\ICrypto;
    
    interface ICookie
    {
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
        public function __construct(string $key, array $cookies, ?ICrypto $crypto = null, string $pathKeys = null, string $customKey = null);

        /**
         * Method to set new information into Cookie
         *
         * @param string $key Key into $_COOKIE of Cookie
         * @param string $content Content to insert into Cookie
         * @param string $customKey Custom key to generate crypt-key
         * @return void
         */
        public function set(string $key, $content, string $customKey = null) :void;

        /**
         * Method to get information into Cookie
         *
         * @param string $key Key into $_COOKIE of Cookie
         * @return mixed
         */
        public function get(string $key = null);
    }
}
