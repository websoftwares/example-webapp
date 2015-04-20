<?php

namespace Websoftwares\Tests\domain\Mail;

use Websoftwares\Domain\Mail\MailMessageEntity;

/**
 * Class MailMessageEntityTest.
 */
class MailMessageEntityTest extends \PHPUnit_Framework_TestCase
{
    public $data;
    public $mailMessageEntity;

    public function setUp()
    {
        $this->data = [
                'subject' => 'Hello World',
                'to' => 'boris@Websoftwar.es',
                'from' => 'boris@Websoftwar.es',
                'body' => 'Welcome you, thanks!',
            ];
        $this->mailMessageEntity = new MailMessageEntity($this->data);
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Mail\MailMessageEntity',
            $this->mailMessageEntity);
    }

    public function testGetItterator()
    {
        $this->assertInstanceOf(
            '\ArrayIterator',
            $this->mailMessageEntity->getIterator());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitFailsException()
    {
        $this->mailMessageEntity->init();
    }

    public function testInitSucceeds()
    {
        $actual = $this->mailMessageEntity->init($this->data);

        $this->assertAttributeEquals('Hello World', 'subject', $this->mailMessageEntity);
        $this->assertAttributeEquals('boris@Websoftwar.es', 'to', $this->mailMessageEntity);
        $this->assertAttributeEquals('boris@Websoftwar.es', 'from', $this->mailMessageEntity);
        $this->assertAttributeEquals('Welcome you, thanks!', 'body', $this->mailMessageEntity);
    }

    public function testAttributesSucceeds()
    {
        $this->assertAttributeEquals('Hello World', 'subject', $this->mailMessageEntity);
        $this->assertAttributeEquals('boris@Websoftwar.es', 'to', $this->mailMessageEntity);
        $this->assertAttributeEquals('boris@Websoftwar.es', 'from', $this->mailMessageEntity);
        $this->assertAttributeEquals('Welcome you, thanks!', 'body', $this->mailMessageEntity);
    }
}
