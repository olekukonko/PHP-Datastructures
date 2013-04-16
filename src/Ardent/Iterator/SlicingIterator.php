<?php

namespace Ardent\Iterator;

class SlicingIterator extends IteratorToCollectionAdapter {

    private $start;
    private $count;

    private $used = 0;

    function __construct(\Iterator $iterator, $start, $count) {
        parent::__construct($iterator);
        $this->start = $start;
        $this->count = $count;
    }

    function next() {
        $this->inner->next();
        $this->used++;
    }

    function valid() {
        return $this->used < $this->count && $this->inner->valid();
    }

    function rewind() {
        $this->inner->rewind();
        $this->used = 0;
        for ($i = 0; $i < $this->start; $i++) {
            $this->inner->next();
        }
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return iterator_count($this);
    }

}
