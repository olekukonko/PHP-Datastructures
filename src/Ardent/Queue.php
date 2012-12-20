<?php

namespace Ardent;

interface Queue extends Collection {
    /**
     * @param $item
     *
     * @return void
     * @throws FullException if the Queue is full.
     * @throws TypeException when $item is not the correct type.
     */
    function push($item);

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function pop();

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function peek();
}
