<?php

namespace Autoload;

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../src/Autoload/Autoload.php';

use Autoload\Autoload;

/**
 * Test class for Autoload.
 * Generated by PHPUnit on 2010-09-24 at 14:34:13.
 */
class AutoloadTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Autoload
   */
  protected $object;

  /**
   * @ver Reflection
   */
  protected $reflector;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->object = new Autoload;
    
    $this->reflector = new \ReflectionObject($this->object);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    $this->object->unregister();
  }

  public function testSetNamespaceSeparator() {
    $this->object->setNamespaceSeparator('test');

    $ns = $this->reflector->getProperty('namespaceSeparator');
    $ns->setAccessible('true');

    $this->assertEquals('test', $ns->getValue($this->object));
  }

  public function testGetNamespaceSeparator() {
    $this->assertEquals('\\', $this->object->getNamespaceSeparator());
  }

  public function testSetFileExtension() {
    $this->object->setFileExtension('test');

    $fe = $this->reflector->getProperty('fileExtension');
    $fe->setAccessible('true');

    $this->assertEquals('test', $fe->getValue($this->object));
  }

  public function testGetFileExtension() {
    $this->assertEquals('.php', $this->object->getFileExtension());
  }

  public function testGetRegisteredNamespaces() {
    $this->object->registerNamespace('test');
    $this->assertArrayHasKey('test', $this->object->getRegisteredNamespaces());
  }

  public function testRegisterNamespace() {
    $this->object->registerNamespace('test');

    $ns = $this->reflector->getProperty('namespaces');
    $ns->setAccessible('true');

    $this->assertArrayHasKey('test', $ns->getValue($this->object));
  }

  public function testRegisterNamespaces() {
    $this->object->registerNamespaces(array(
      'test'  => null,
      'test2' => null
    ));

    $ns = $this->reflector->getProperty('namespaces');
    $ns->setAccessible('true');

    $this->assertArrayHasKey('test', $ns->getValue($this->object));
    $this->assertArrayHasKey('test2', $ns->getValue($this->object));
  }

  public function testRegister() {
    $this->object->register();

    $loaders = \spl_autoload_functions();

    $this->assertType('object', $loaders[0][0]);
    $this->assertEquals('Autoload\Autoload', get_class($loaders[0][0]));
  }

  public function testUnregister() {
    $this->object->unregister();

    $loaders = \spl_autoload_functions();

    $this->assertEquals(array(), $loaders);
  }

  public function testLoadClass() {
    $this->object->registerNamespace('Mock', dirname(__FILE__).'/../');
    $this->object->loadClass('Mock\Mock');

    $this->assertContains(realpath(dirname(__FILE__).'/../Mock').'/Mock.php', \get_included_files());
  }

  public function testNoClass() {
    $this->setExpectedException('Autoload\Exception\AutoloadException');
    
    $this->object->loadClass('Mock');
  }

  public function testNoFile() {
    $this->setExpectedException('Autoload\Exception\AutoloadException');

    $this->object->registerNamespace('Mock', dirname(__FILE__).'/../');

    $this->object->loadClass('Mock\Mock2.php');
  }
}

?>