<?php

namespace Qandidate\Toggle;

use Qandidate\Toggle\TestCase;
use Qandidate\Toggle\Toggle;

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
        $toggle     = new Toggle('some-feature', array());
        $collection = $this->createCollection();
        $collection->set($toggle->getName(), $toggle);

        $this->assertEquals($toggle, $collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_removes_a_toggle()
    {
        $toggle     = new Toggle('some-feature', array());
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
        $toggle     = new Toggle('some-feature',       array());
        $toggle2    = new Toggle('some-other-feature', array());
        $collection = $this->createCollection();

        $collection->set($toggle->getName(),  $toggle);
        $collection->set($toggle2->getName(), $toggle2);

        $all = $collection->all();
        $this->assertCount(2, $all);
        $this->assertEquals($all['some-feature'], $toggle);
        $this->assertEquals($all['some-other-feature'], $toggle2);
    }

    /**
     * @return ToggleCollection
     */
    abstract protected function createCollection();
}
