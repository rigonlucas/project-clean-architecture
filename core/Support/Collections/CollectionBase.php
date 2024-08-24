<?php

namespace Core\Support\Collections;

use ArrayIterator;
use Core\Support\Collections\Paginations\toArrayInterface;
use Core\Support\Exceptions\MentodMustBeImplementedException;
use Countable;
use IteratorAggregate;
use Traversable;

class CollectionBase implements IteratorAggregate, Countable, toArrayInterface
{
    protected array $items = [];

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function remove(int $index): void
    {
        unset($this->items[$index]);
    }

    public function exists(int $index): bool
    {
        return isset($this->items[$index]);
    }

    public function getItens(): array
    {
        return $this->items;
    }

    /**
     * @throws MentodMustBeImplementedException
     */
    public function toArray(): array
    {
        throw new MentodMustBeImplementedException('Method toArray not implemented');
    }
}
