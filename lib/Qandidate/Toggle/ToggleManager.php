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

use OutOfBoundsException;

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
     * Rename the toggle.
     *
     * @param Toggle $toggle
     * @param $originalName
     * @return bool
     */
    public function rename(Toggle $toggle, $originalName)
    {
        if($toggle->getName() != $originalName) {
            $this->collection->set($toggle->getName(), $toggle);

            return $this->collection->remove($originalName);
        }

        throw new OutOfBoundsException("Toggle {$toggle->getName()} and {$originalName} are not valid arguments for renaming");
    }

    /**
     * @return array All toggles from the manager.
     */
    public function all()
    {
        return $this->collection->all();
    }
}
