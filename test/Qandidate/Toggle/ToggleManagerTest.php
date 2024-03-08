<?php

declare(strict_types=1);

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
    public function it_returns_false_if_toggle_not_added_to_manager_before(): void
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $this->assertFalse($manager->active('foo', new Context()));
    }

    /**
     * @test
     */
    public function it_returns_the_value_of_the_toggle_if_available(): void
    {
        $manager = new ToggleManager(new InMemoryCollection());
        $manager->add($this->createToggle());

        $this->assertTrue($manager->active('some-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_updates_a_toggle(): void
    {
        $manager = new ToggleManager(new InMemoryCollection());
        $manager->add($this->createToggle());
        $manager->update($this->createToggle(false));

        $this->assertFalse($manager->active('some-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_removes_a_toggle(): void
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($this->createToggle());

        $manager->remove('some-feature');
        $this->assertFalse($manager->active('some-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_renames_a_toggle(): void
    {
        $manager = new ToggleManager(new InMemoryCollection());

        $manager->add($this->createToggle());

        $manager->rename('some-feature', 'other-feature');
        $this->assertFalse($manager->active('some-feature', new Context()));
        $this->assertTrue($manager->active('other-feature', new Context()));
    }

    /**
     * @test
     */
    public function it_throws_if_new_name_is_already_in_use(): void
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Could not rename toggle foo to some-feature, a toggle with name some-feature already exists');
        $manager = new ToggleManager(new InMemoryCollection());

        $toggle = $this->createToggle();

        $manager->add($toggle);

        $manager->rename('foo', 'some-feature');
    }

    /**
     * @test
     */
    public function it_throws_when_to_be_renamed_toggle_doesnt_exists(): void
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Could not rename toggle foo to some-feature, toggle with name foo does not exists');
        $manager = new ToggleManager(new InMemoryCollection());
        $manager->rename('foo', 'some-feature');
    }

    /**
     * @test
     */
    public function it_exposes_all_toggles(): void
    {
        $toggle = new Toggle('some-feature', []);
        $toggle2 = new Toggle('some-other-feature', []);

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
     */
    public function it_throws_when_given_name_does_not_exists(): void
    {
        $this->expectException('InvalidArgumentException');
        $collection = new InMemoryCollection();
        $collection->set('foo', new Toggle('foo-feature', []));
        $manager = new ToggleManager($collection);

        $manager->get('bar');
    }

    /**
     * @test
     */
    public function it_returns_toggle_if_toggle_with_given_name_exists(): void
    {
        $collection = new InMemoryCollection();
        $collection->set('foo', new Toggle('foo-feature', []));
        $manager = new ToggleManager($collection);

        $actual = $manager->get('foo');
        $this->assertInstanceOf(Toggle::class, $actual);
    }

    public function createToggle(bool $active = true, string $name = 'some-feature'): Toggle
    {
        $toggleStub = new Toggle($name, [], Toggle::STRATEGY_UNANIMOUS);
        $toggleStub->deactivate();
        if ($active) {
            $toggleStub->activate(Toggle::ALWAYS_ACTIVE);
        }

        return $toggleStub;
    }
}
