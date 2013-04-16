<?php

namespace Ardent\Iterator;

class FilteringIterator extends IteratorToCollectionAdapter {

    /**
     * @var callable
     */
    private $filter;

    private $current;
    private $key = -1;

    /**
     * @var bool
     */
    private $valid;

    function __construct(\Iterator $iterator, callable $filter) {
        parent::__construct($iterator);
        $this->filter = $filter;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->current;
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->valid = FALSE;
        while ($this->inner->valid()) {
            $key = $this->inner->key();
            $current = $this->inner->current();
            if (call_user_func($this->filter, $current, $key)) {
                $this->key++;
                $this->current = $current;
                $this->valid = TRUE;
                $this->inner->next();
                break;
            }
            $this->inner->next();
        }
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->key;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->valid;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->inner->rewind();
        $this->key = -1;
        $this->next();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return iterator_count($this);
    }

}
