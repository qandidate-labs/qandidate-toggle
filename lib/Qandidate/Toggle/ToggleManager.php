<?php

namespace Qandidate\Toggle;

/**
 * Manages the toggles of an application.
 */
class ToggleManager
{
    private $collection;

    /**
     * @param ToggleCollection $collection
     */
    public function __construct(ToggleCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param string  $name
     * @param Context $context
     *
     * @return True, if the toggle exists and is active
     */
    public function active($name, Context $context)
    {
        if (null === $toggle = $this->collection->get($name)) {
            return false;
        }

        return $toggle->activeFor($context);
    }

    /**
     * Removes the toggle from the manager.
     *
     * @param string $name
     *
     * @return boolean True, if element was removed
     */
    public function remove($name)
    {
        return $this->collection->remove($name);
    }

    /**
     * Add the toggle to the manager.
     *
     * @param Toggle $toggle
     */
    public function add(Toggle $toggle)
    {
        $this->collection->set($toggle->getName(), $toggle);
    }

    /**
     * Update the toggle.
     *
     * @param Toggle $toggle
     */
    public function update(Toggle $toggle)
    {
        $this->collection->set($toggle->getName(), $toggle);
    }

    /**
     * @return array All toggles from the manager.
     */
    public function all()
    {
        return $this->collection->all();
    }
}
