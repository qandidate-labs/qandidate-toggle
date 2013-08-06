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

        $this->assertTrue($manager->remove('some-feature'));
        $this->assertFalse($manager->active('some-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_exposes_all_toggles()
    {
        $toggle     = new Toggle('some-feature',       array());
        $toggle2    = new Toggle('some-other-feature', array());

        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($toggle);
        $manager->add($toggle2);

        $all = $manager->all();
        $this->assertCount(2, $all);
        $this->assertEquals($all['some-feature'], $toggle);
        $this->assertEquals($all['some-other-feature'], $toggle2);
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
