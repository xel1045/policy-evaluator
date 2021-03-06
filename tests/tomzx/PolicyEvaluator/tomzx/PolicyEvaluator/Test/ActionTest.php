<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Action;

class ActionTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $action = new Action('service:action');
        $this->assertNotNull($action);
    }

    public function testInitializeWithWildcard()
    {
        $action = new Action('*');
        $this->assertNotNull($action);
    }

    public function testInitializeWithArray()
    {
        $action = new Action(['service1:action1', 'service2:action2']);
        $this->assertNotNull($action);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid service prefix for action "service".
     */
    public function testInitializeWithInvalidActionShouldThrowAnException()
    {
        $action = new Action('service');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid service prefix for action "service2".
     */
    public function testInitializeWithInvalidActionInArrayShouldThrowAnException()
    {
        $action = new Action(['service1:action2', 'service2']);
    }

    public function testMatch()
    {
        $action = new Action('urn:test:test');
        $actual = $action->matches('urn:test:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcard()
    {
        $action = new Action('urn:test:te*');
        $actual = $action->matches('urn:test:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcardOnly()
    {
        $action = new Action('*');
        $actual = $action->matches('urn:test:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcardAndNoMatch()
    {
        $action = new Action('urn:test:te*');
        $actual = $action->matches('urn:test:fail');
        $this->assertFalse($actual);
    }

    // TODO(tom@tomrochette.com): Not sure how a wildcard query should work
    public function testMatchWithWildcardRequest()
    {
        $action = new Action('urn:test:test');
        $actual = $action->matches('urn:test:*');
        $this->assertFalse($actual);
    }

    public function testMatchWithShorterRequestString()
    {
        $action = new Action('urn:test:test');
        $actual = $action->matches('urn:test:tes');
        $this->assertFalse($actual);
    }

    public function testMatchWithLongerRequestString()
    {
        $action = new Action('urn:test:test');
        $actual = $action->matches('urn:test:tests');
        $this->assertFalse($actual);
    }

    public function testMatchDoesNotMatchRegex()
    {
        $action = new Action('urn:test:te[st]+');
        $actual = $action->matches('urn:test:test');
        $this->assertFalse($actual);
    }
}
