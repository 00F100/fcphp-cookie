<?php

use FcPhp\Cookie\Facades\CookieFacade;
use FcPhp\Cookie\Cookie;
use FcPhp\Crypto\Crypto;
use FcPhp\Cookie\Interfaces\ICookie;
use PHPUnit\Framework\TestCase;

class CookieIntegrationTest extends TestCase
{
	private $cookies = [];
	private $instance;

	public function setUp()
	{
		// $nonce = Crypto::getNonce();
		$nonce = 'NIS6TpQmJMv19AdoBTPeB4BZgj8lnToR';
		$this->instance = CookieFacade::getInstance('key-cookie', $this->cookies, $nonce, 'tests/var/keys');
	}

	public function testInstance()
	{
		$this->assertTrue($this->instance instanceof ICookie);
	}

	public function testSet()
	{
		$this->instance->set('config.item', 'content');
		$this->assertEquals($this->instance->get('config.item'), 'content');
	}

	public function testSetNonCrypto()
	{
		$instance = new Cookie('key-cookie', $this->cookies);
		$instance->set('config.item', 'content');
		$instance->set('config.item2', 'content');
		$this->assertEquals($instance->get('config.item'), 'content');
	}

	/**
     * @expectedException FcPhp\Cookie\Exceptions\PathNotPermissionFoundException
     */
	public function testPathNoPermission()
	{
		// $nonce = Crypto::getNonce();
		$nonce = 'NIS6TpQmJMv19AdoBTPeB4BZgj8lnToR';
		$crypto =  new Crypto($nonce);
		$instance = new Cookie('key-cookie', $this->cookies, $crypto, '/root/crypto');
		$instance->set('config.item', 'content');
		$this->assertEquals($instance->get('config.item'), 'content');
	}

	/**
     * @expectedException FcPhp\Cookie\Exceptions\PathKeyNotFoundException
     */
	public function testPathNoPath()
	{
		// $nonce = Crypto::getNonce();
		$nonce = 'NIS6TpQmJMv19AdoBTPeB4BZgj8lnToR';
		$crypto =  new Crypto($nonce);
		$instance = new Cookie('key-cookie', $this->cookies, $crypto);
		$instance->set('config.item', 'content');
		$this->assertEquals($instance->get('config.item'), 'content');
		unlink('tests/var');
	}

	public function testNonCrypto()
	{
		$value = base64_encode(serialize(['config' => ['item' => 'content']]));
		$cookies = [
			'key-cookie-1' => $value
		];
		$instance = new Cookie('key-cookie-1', $cookies);
		$this->assertEquals($instance->get('config.item'), 'content');
	}

	public function testCryptCookieHas()
	{
		$value = 'NzRrwwBf1aW43q4955iHle6Qb1OLyrP2gSYIeBcIXxddTJjyQABN3qWiPvtugqRZ9aSsjFV4668Jc8JT8w6pSw9C';
		$cookies = [
			'key-cookie-2' => $value
		];
		$nonce = 'NIS6TpQmJMv19AdoBTPeB4BZgj8lnToR';
		$crypto =  new Crypto($nonce);
		$instance = new Cookie('key-cookie-2', $cookies, $crypto, 'tests/var/keys');
		$this->assertEquals($instance->get('config.item'), 'content');
	}

	public function testCreateNewKeyCrypto()
	{
		$cookies = [];
		$nonce = Crypto::getNonce();
		$crypto =  new Crypto($nonce);
		$instance = new Cookie('key-cookie-3', $cookies, $crypto, 'tests/var/keys');
		$instance->set('config.item', 'content', 'new-key');
		$this->assertEquals($instance->get('config.item', 'new-key'), 'content');
	}

}