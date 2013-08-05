<?php

namespace Qandidate\Trigger\ToggleCollection;

use Qandidate\Trigger\TestCase;
use Qandidate\Trigger\Toggle;

class InMemoryCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_null_if_toggle_not_in_collection()
    {
        $collection = new InMemoryCollection();
        $this->assertNull($collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_returns_a_set_toggle()
    {
        $toggle     = new Toggle('some-feature', array());
        $collection = new InMemoryCollection();
        $collection->set($toggle);

        $this->assertEquals($toggle, $collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_removes_a_toggle()
    {
        $toggle     = new Toggle('some-feature', array());
        $collection = new InMemoryCollection();
        $collection->set($toggle);

        $collection->remove('some-feature');

        $this->assertNull($collection->get('some-feature'));
    }

    /**
     * @test
     */
    public function it_does_not_complain_when_removing_a_non_existing_toggle()
    {
        $collection = new InMemoryCollection();

        $collection->remove('some-feature');

        $this->assertNull($collection->get('some-feature'));
    }
}
