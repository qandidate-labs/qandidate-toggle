<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function it_renames_a_toggle()
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($this->createToggleMock());

        $this->assertTrue($manager->rename($this->createToggleMock(true, 'other-feature'), 'some-feature'));
        $this->assertFalse($manager->active('some-feature', new Context()));
        $this->assertTrue($manager->active('other-feature', new Context()));
    }

    /**
     * @test
     * @expectedException OutOfBoundsException
     */
    public function it_returns_exception_if_toggle_name_matches_original_name()
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($this->createToggleMock());

        $this->assertTrue($manager->rename($this->createToggleMock(), 'some-feature'));
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

    public function createToggleMock($active = true, $getName = 'some-feature')
    {
        $toggleMock = $this->getMockBuilder('Qandidate\Toggle\Toggle')
            ->disableOriginalConstructor()
            ->getMock();

        $toggleMock->expects($this->any())
            ->method('activeFor')
            ->will($this->returnValue($active));

        $toggleMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($getName));

        return $toggleMock;
    }
}
