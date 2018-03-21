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

        $toggle = $this->createToggleMock();

        $manager->add($toggle);

        $toggle->expects($this->once())
            ->method('rename')
            ->with('other-feature');

        $toggle->expects($this->at(1))
            ->method('getName')
            ->will($this->returnValue('other-feature'));

        $this->assertTrue($manager->rename('some-feature', 'other-feature'));
        $this->assertFalse($manager->active('some-feature', new Context()));
        $this->assertTrue($manager->active('other-feature', new Context()));
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Could not rename toggle foo to some-feature, a toggle with name some-feature already exists
     */
    public function it_throws_if_new_name_is_already_in_use()
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $toggle = $this->createToggleMock();

        $manager->add($toggle);

        $manager->rename('foo', 'some-feature');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Could not rename toggle foo to some-feature, toggle with name foo does not exists
     */
    public function it_throws_when_to_be_renamed_toggle_doesnt_exists()
    {
        $manager = new ToggleManager(new InMemoryCollection());
        $manager->rename('foo', 'some-feature');
    }

    /**
     * @test
     */
    public function it_exposes_all_toggles()
    {
        $toggle  = new Toggle('some-feature', array());
        $toggle2 = new Toggle('some-other-feature', array());

        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($toggle);
        $manager->add($toggle2);

        $all = $manager->all();
        $this->assertCount(2, $all);
        $this->assertEquals($all['some-feature'], $toggle);
        $this->assertEquals($all['some-other-feature'], $toggle2);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_when_given_name_does_not_exists()
    {
        $collection = new InMemoryCollection();
        $collection->set('foo', new Toggle('foo-feature', array()));
        $manager = new ToggleManager($collection);

        $manager->get('bar');
    }

    /**
     * @test
     */
    public function it_returns_toggle_if_toggle_with_given_name_exists()
    {
        $collection = new InMemoryCollection();
        $collection->set('foo', new Toggle('foo-feature', array()));
        $manager = new ToggleManager($collection);

        $actual = $manager->get('foo');
        $this->assertInstanceOf(Toggle::class, $actual);
    }

    public function createToggleMock($active = true, $getName = 'some-feature')
    {
        $toggleMock = $this->getMockBuilder('Qandidate\Toggle\Toggle')
            ->disableOriginalConstructor()
            ->getMock();

        $toggleMock->expects($this->any())
            ->method('activeFor')
            ->will($this->returnValue($active));

        $toggleMock->expects($this->at(0))
            ->method('getName')
            ->will($this->returnValue($getName));

        return $toggleMock;
    }
}
