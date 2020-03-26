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

abstract class ToggleCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_null_if_toggle_not_in_collection()
    {
        $collection = $this->createCollection();
        $this->assertNull($collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_returns_a_set_toggle()
    {
        $toggle = new Toggle('some-feature', []);
        $collection = $this->createCollection();
        $collection->set($toggle->getName(), $toggle);

        $this->assertEquals($toggle, $collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_removes_a_toggle()
    {
        $toggle = new Toggle('some-feature', []);
        $collection = $this->createCollection();
        $collection->set($toggle->getName(), $toggle);

        $collection->remove('some-feature');

        $this->assertNull($collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_does_not_complain_when_removing_a_non_existing_toggle()
    {
        $collection = $this->createCollection();

        $collection->remove('some-feature');

        $this->assertNull($collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_exposes_all_toggles()
    {
        $toggle = new Toggle('some-feature', []);
        $toggle2 = new Toggle('some-other-feature', []);
        $collection = $this->createCollection();

        $collection->set($toggle->getName(), $toggle);
        $collection->set($toggle2->getName(), $toggle2);

        $all = $collection->all();
        $this->assertCount(2, $all);
        $this->assertEquals($all['some-feature'], $toggle);
        $this->assertEquals($all['some-other-feature'], $toggle2);
    }

    /**
     * @test
     */
    public function it_removes_existing_toggle()
    {
        $toggle = new Toggle('some-feature', []);
        $collection = $this->createCollection();

        $collection->set($toggle->getName(), $toggle);
        $this->assertCount(1, $collection->all());

        $collection->remove('some-feature');

        $this->assertCount(0, $collection->all());
    }

    /**
     * @test
     */
    public function it_does_not_throw_when_removing_non_existing_toggle()
    {
        $collection = $this->createCollection();

        $this->assertCount(0, $collection->all());

        $collection->remove('some-feature');
        $this->assertCount(0, $collection->all());
    }

    /**
     * @return ToggleCollection
     */
    abstract protected function createCollection();
}
