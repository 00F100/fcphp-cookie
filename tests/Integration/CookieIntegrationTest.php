<?php

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
		$nonce = Crypto::getNonce();
		$crypto =  new Crypto($nonce);
		$this->instance = new Cookie($this->cookies, $crypto, 'tests/var/keys');
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
		$instance = new Cookie($this->cookies);
		$instance->set('config.item', 'content');
		$instance->set('config.item2', 'content');
		$this->assertEquals($instance->get('config.item'), 'content');
	}

	/**
     * @expectedException FcPhp\Cookie\Exceptions\PathNotPermissionFoundException
     */
	public function testPathNoPermission()
	{
		$nonce = Crypto::getNonce();
		$crypto =  new Crypto($nonce);
		$instance = new Cookie($this->cookies, $crypto, '/root/crypto');
		$instance->set('config.item', 'content');
		$this->assertEquals($instance->get('config.item'), 'content');
	}

	/**
     * @expectedException FcPhp\Cookie\Exceptions\PathKeyNotFoundException
     */
	public function testPathNoPath()
	{
		$nonce = Crypto::getNonce();
		$crypto =  new Crypto($nonce);
		$instance = new Cookie($this->cookies, $crypto);
		$instance->set('config.item', 'content');
		$this->assertEquals($instance->get('config.item'), 'content');
		unlink('tests/var');
	}
}