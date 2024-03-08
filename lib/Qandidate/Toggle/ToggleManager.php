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

use RuntimeException;
use InvalidArgumentException;
/**
 * Manages the toggles of an application.
 */
class ToggleManager
{
    public function __construct(private readonly ToggleCollection $collection)
    {
    }

    /**
     * @return bool True, if the toggle exists and is active
     */
    public function active(string $name, Context $context): bool
    {
        if (null === $toggle = $this->collection->get($name)) {
            return false;
        }

        return $toggle->activeFor($context);
    }

    /**
     * Removes the toggle from the manager.
     */
    public function remove(string $name): void
    {
        $this->collection->remove($name);
    }

    /**
     * Add the toggle to the manager.
     */
    public function add(Toggle $toggle): void
    {
        $this->collection->set($toggle->getName(), $toggle);
    }

    /**
     * Update the toggle.
     */
    public function update(Toggle $toggle): void
    {
        $this->collection->set($toggle->getName(), $toggle);
    }

    /**
     * Rename the toggle.
     *
     * @throws RuntimeException
     */
    public function rename(string $oldName, string $newName): void
    {
        if (null !== $this->collection->get($newName)) {
            throw new RuntimeException(sprintf('Could not rename toggle %1$s to %2$s, a toggle with name %2$s already exists', $oldName, $newName));
        }

        $currentToggle = $this->collection->get($oldName);

        if (null === $currentToggle) {
            throw new RuntimeException(sprintf('Could not rename toggle %1$s to %2$s, toggle with name %1$s does not exists', $oldName, $newName));
        }

        $currentToggle->rename($newName);

        $this->add($currentToggle);
        $this->remove($oldName);
    }

    /**
     * @return Toggle[] all toggles from the manager
     */
    public function all(): array
    {
        return $this->collection->all();
    }

    /**
     * @return Toggle toggle from manager that has given name
     *
     * @throws InvalidArgumentException
     */
    public function get(string $name): Toggle
    {
        $toggle = $this->collection->get($name);
        if (!$toggle) {
            throw new InvalidArgumentException("Cannot find Toggle with name $name");
        }

        return $toggle;
    }
}
