<?php

namespace Qandidate\Toggle;

use Qandidate\Toggle\ToggleCollection\InMemoryCollection;

class ToggleManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_false_if_toggle_not_added_to_manager_before()
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $this->assertFalse($manager->active('foo', new Context()));
    }

    /**
     * @test
     */
    public function it_returns_the_value_of_the_toggle_if_available()
    {
        $manager = new ToggleManager(new InMemoryCollection());
        $manager->add($this->createToggleMock());

        $this->assertTrue($manager->active('some-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_updates_a_toggle()
    {
        $manager = new ToggleManager(new InMemoryCollection());
        $manager->add($this->createToggleMock());
        $manager->update($this->createToggleMock(false));

        $this->assertFalse($manager->active('some-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_removes_a_toggle()
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($this->createToggleMock());
        $manager->remove('some-feature');

        $this->assertFalse($manager->active('some-feature', new Context()));
    }

    public function createToggleMock($active = true)
    {
        $toggleMock = $this->getMockBuilder('Qandidate\Toggle\Toggle')
            ->disableOriginalConstructor()
            ->getMock();

        $toggleMock->expects($this->any())
            ->method('activeFor')
            ->will($this->returnValue($active));

        $toggleMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('some-feature'));

        return $toggleMock;
    }
}
