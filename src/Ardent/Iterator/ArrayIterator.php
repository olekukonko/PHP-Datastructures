<?php

namespace Ardent\Iterator;

use Ardent\Collection;

class ArrayIterator implements CountableSeekableIterator, Collection {

    use IteratorCollection;

    /**
     * @var \ArrayIterator
     */
    private $inner;

    private $count;

    function __construct(array $array) {
        $this->inner = new \ArrayIterator($array);
        $this->count = count($array);
    }
    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->inner->current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->inner->next();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->inner->key();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->inner->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->inner->rewind();
    }

    /**
     * @param int $position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @return void
     */
    function seek($position) {
        $this->inner->seek($position);
    }

    function count() {
        return $this->count;
    }

}
