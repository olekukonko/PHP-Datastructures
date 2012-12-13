<?php

namespace Spl;

class AvlTreeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AvlTree
     */
    protected $object;

    protected function setUp() {
        $this->object = new AvlTree();
    }

    /**
     * @covers \Spl\AvlTree::add
     * @covers \Spl\AvlTree::balance
     * @covers \Spl\AvlTree::rotateLeft
     * @covers \Spl\AvlTree::rotateSingleLeft
     */
    function testCaseRightRight() {
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(5));

        $this->reCalculateHeights($root);
        $actualRoot = $this->object->getBinaryTree();

        $this->assertEquals($root, $actualRoot);
    }

    /**
     * @covers \Spl\AvlTree::add
     * @covers \Spl\AvlTree::balance
     * @covers \Spl\AvlTree::rotateRight
     * @covers \Spl\AvlTree::rotateSingleRight
     */
    function testCaseLeftLeft() {
        $this->object->add(5);
        $this->object->add(4);
        $this->object->add(3);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(5));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $this->object->getBinaryTree());
    }

    /**
     * @covers \Spl\AvlTree::add
     * @covers \Spl\AvlTree::balance
     * @covers \Spl\AvlTree::rotateRight
     * @covers \Spl\AvlTree::rotateSingleLeft
     * @covers \Spl\AvlTree::rotateSingleRight
     */
    function testCaseLeftRight() {
        $this->object->add(5);
        $this->object->add(1);
        $this->object->add(9);
        $this->object->add(3);
        $this->object->add(0);

        // triggers the double rotate
        $this->object->add(4);

        // original tree before rotate
        //      5
        //    /   \
        //   1    9
        //  / \
        // 0  3
        //     \
        //     4
        //
        // resulting tree should be
        //      3
        //    /   \
        //   1    5
        //  /    / \
        // 0    4  9


        $root = new BinaryTree(3);
        $root->setLeft(new BinaryTree(1));
        $root->setRight(new BinaryTree(5));
        $root->getLeft()->setLeft(new BinaryTree(0));
        $root->getRight()->setLeft(new BinaryTree(4));
        $root->getRight()->setRight(new BinaryTree(9));

        $this->reCalculateHeights($root);
        $actualRoot = $this->object->getBinaryTree();
        $this->assertEquals($root, $actualRoot);

    }

    /**
     * @covers \Spl\AvlTree::add
     * @covers \Spl\AvlTree::balance
     * @covers \Spl\AvlTree::rotateLeft
     * @covers \Spl\AvlTree::rotateSingleLeft
     * @covers \Spl\AvlTree::rotateSingleRight
     */
    function testCaseRightLeft() {
        $this->object->add(5);
        $this->object->add(1);
        $this->object->add(8);
        $this->object->add(7);
        $this->object->add(9);

        // triggers the double rotate
        $this->object->add(6);

        // original tree before rotate
        //      5
        //    /   \
        //   1    8
        //       / \
        //      7  9
        //     /
        //    6
        //
        // resulting tree should be
        //      7
        //    /   \
        //   5    8
        //  / \    \
        // 1  6    9


        $root = new BinaryTree(7);
        $root->setLeft(new BinaryTree(5));
        $root->setRight(new BinaryTree(8));
        $root->getLeft()->setLeft(new BinaryTree(1));
        $root->getLeft()->setRight(new BinaryTree(6));
        $root->getRight()->setRight(new BinaryTree(9));

        $this->reCalculateHeights($root);
        $actualRoot = $this->object->getBinaryTree();
        $this->assertEquals($root, $actualRoot);

    }

    /**
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\BinarySearchTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     */
    function testRemoveNonExistingItem() {
        $this->object->remove(1);
    }

    /**
     * @covers \Spl\AvlTree::balance
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\BinarySearchTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     * @covers \Spl\BinaryTree::isLeaf
     * @covers \Spl\BinaryTree::hasOnlyOneChild
     */
    function testRemoveRootBasic() {
        $this->object->add(5);
        $this->object->remove(5);

        $root = $this->object->getBinaryTree();
        $this->assertNull($root);
    }

    /**
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\BinarySearchTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     * @covers \Spl\BinaryTree::isLeaf
     * @depends testCaseRightRight
     * @depends testCaseLeftLeft
     */
    function testRemoveLeaf() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);

        $this->object->remove(3);

        $root = new BinaryTree(4);
        $root->setRight(new BinaryTree(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getBinaryTree());
    }

    /**
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\BinarySearchTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     * @covers \Spl\BinaryTree::setValue
     * @covers \Spl\BinaryTree::hasOnlyOneChild
     * @depends testCaseRightRight
     * @depends testCaseLeftLeft
     */
    function testRemoveWithLeftChild() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);
        $this->object->add(1);

        $this->object->remove(3);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(1));
        $root->setRight(new BinaryTree(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getBinaryTree());
    }

    /**
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\BinarySearchTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     * @covers \Spl\BinaryTree::setValue
     * @covers \Spl\BinaryTree::hasOnlyOneChild
     * @depends testCaseRightRight
     * @depends testCaseLeftLeft
     */
    function testRemoveWithRightChild() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);
        $this->object->add(7);

        $this->object->remove(5);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(7));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getBinaryTree());
    }

    function testAddItemThatAlreadyExists() {
        $this->object->add(4);
        $this->object->add(4);

        $expectedRoot = new BinaryTree(4);
        $actualRoot = $this->object->getBinaryTree();

        $this->reCalculateHeights($expectedRoot);

        $this->assertEquals($expectedRoot, $actualRoot);

    }

    /**
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\BinarySearchTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     * @covers \Spl\BinaryTree::setValue
     */
    function testRemoveWithBothChildren() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);

        $this->object->remove(4);

        $root = new BinaryTree(3);
        $root->setRight(new BinaryTree(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getBinaryTree());
    }

    /**
     * @covers \Spl\AvlTree::remove
     * @covers \Spl\AvlTree::removeNode
     * @covers \Spl\AvlTree::deleteNode
     * @covers \Spl\BinaryTree::setValue
     */
    function testRemoveWhereInOrderPredecessorHasChild() {
        //          5
        //        /    \
        //       2      9
        //     /  \    / \
        //    1    4  8  11
        //        /
        //       3
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(9);
        $this->object->add(1);
        $this->object->add(4);
        $this->object->add(8);
        $this->object->add(11);
        $this->object->add(3);

        // build a test tree to validate that we are set up correctly
        $expectedRoot = new BinaryTree(5);
        $expectedRoot->setLeft(new BinaryTree(2));
        $expectedRoot->getLeft()->setLeft(new BinaryTree(1));
        $expectedRoot->getLeft()->setRight(new BinaryTree(4));
        $expectedRoot->getLeft()->getRight()->setLeft(new BinaryTree(3));
        $expectedRoot->setRight(new BinaryTree(9));
        $expectedRoot->getRight()->setLeft(new BinaryTree(8));
        $expectedRoot->getRight()->setRight(new BinaryTree(11));

        $this->reCalculateHeights($expectedRoot);

        $actualRoot = $this->object->getBinaryTree();

        $this->assertEquals($expectedRoot, $actualRoot);

        // okay, now for the real test:
        $this->object->remove(5);

        //          4
        //        /    \
        //       2      9
        //     /  \    / \
        //    1    3  8  11

        $expectedRoot = new BinaryTree(4);
        $expectedRoot->setLeft(new BinaryTree(2));
        $expectedRoot->getLeft()->setLeft(new BinaryTree(1));
        $expectedRoot->getLeft()->setRight(new BinaryTree(3));
        $expectedRoot->setRight(new BinaryTree(9));
        $expectedRoot->getRight()->setLeft(new BinaryTree(8));
        $expectedRoot->getRight()->setRight(new BinaryTree(11));

        $this->reCalculateHeights($expectedRoot);

        $actualRoot = $this->object->getBinaryTree();

        $this->assertEquals($expectedRoot, $actualRoot);
    }

    private function reCalculateHeights(BinaryTree $root = NULL) {
        if ($root === NULL) {
            return;
        }
        $this->reCalculateHeights($root->getLeft());
        $this->reCalculateHeights($root->getRight());
        $root->recalculateHeight();

    }

    /**
     * @covers \Spl\AvlTree::clear
     * @covers \Spl\AvlTree::count
     */
    function testClear() {
        $this->object->add(5);

        $this->object->clear();

        $this->assertNull($this->object->getBinaryTree());
        $this->assertEmpty($this->object->count());
    }

    /**
     * @covers \Spl\AvlTree::contains
     * @covers \Spl\AvlTree::containsNode
     */
    function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object->add(1);
        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers \Spl\AvlTree::contains
     * @covers \Spl\AvlTree::containsNode
     */
    function testContainsRightSubTree() {
        $this->object->add(2);
        $this->object->add(3);
        $this->assertTrue($this->object->contains(3));
        $this->assertFalse($this->object->contains(1));
    }

    /**
     * @covers \Spl\AvlTree::contains
     * @covers \Spl\AvlTree::containsNode
     */
    function testContainsLeftSubTree() {
        $this->object->add(2);
        $this->object->add(1);
        $this->assertTrue($this->object->contains(1));
        $this->assertFalse($this->object->contains(3));
    }

    /**
     * @covers \Spl\AvlTree::get
     */
    function testGet() {
        $this->assertNull($this->object->get(1));

        $this->object->add(1);
        $this->assertEquals(1, $this->object->get(1));
    }

    /**
     * @covers \Spl\AvlTree::get
     * @covers \Spl\AvlTree::getNode
     */
    function testGetRightSubTree() {
        $this->object->add(2);
        $this->object->add(3);
        $this->assertEquals(3, $this->object->get(3));
        $this->assertNull($this->object->get(1));
    }

    /**
     * @covers \Spl\AvlTree::get
     * @covers \Spl\AvlTree::getNode
     */
    function testGetLeftSubTree() {
        $this->object->add(2);
        $this->object->add(1);
        $this->assertEquals(1, $this->object->get(1));
        $this->assertNull($this->object->get(3));
    }

    /**
     * @covers \Spl\AvlTree::isEmpty
     */
    function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object->add(1);
        $this->assertFalse($this->object->isEmpty());
    }

    function testDefaultIterator() {
        $iterator = $this->object->getIterator();

        $this->assertInstanceOf('\\Spl\\InOrderIterator', $iterator);
    }

    function testEmptyTreeIterators() {

        $iterators = array(
            'InOrder' => $this->object->getIterator(AvlTree::TRAVERSE_IN_ORDER),
            'PreOrder' => $this->object->getIterator(AvlTree::TRAVERSE_PRE_ORDER),
            'PostOrder' => $this->object->getIterator(AvlTree::TRAVERSE_POST_ORDER),
            'LevelOrder' => $this->object->getIterator(AvlTree::TRAVERSE_LEVEL_ORDER),
        );

        foreach ($iterators as $algorithm => $iterator) {
            /**
             * @var \Iterator $iterator
             */
            $this->assertInstanceOf("\\Spl\\{$algorithm}Iterator", $iterator);

            $iterator->rewind();
            $this->assertFalse($iterator->valid());
        }
    }

    function testGetIteratorA() {
        //          5
        //        /    \
        //       2      8
        //        \      \
        //        3      11
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(8);
        $this->object->add(11);
        $this->object->add(3);

        $expectedSequences = array(
            'inOrder' => array(2,3,5,8,11),
            'preOrder' => array(5,2,3,8,11),
            'postOrder' => array(3,2,11,8,5),
            'levelOrder' => array(5,2,8,3,11),
        );

        $this->__testIterators($expectedSequences);

    }

    function testGetIteratorB() {
        //          5
        //        /     \
        //       2      8
        //      / \
        //     1  3
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(8);
        $this->object->add(1);
        $this->object->add(3);

        $expectedSequences = array(
            'inOrder' => array(1,2,3,5,8),
            'preOrder' => array(5,2,1,3,8),
            'postOrder' => array(1,3,2,8,5),
            'levelOrder' => array(5,2,8,1,3),
        );

        $this->__testIterators($expectedSequences);

    }

    function testGetIteratorC() {
        //          5
        //        /     \
        //       2      8
        //             / \
        //            6  9
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(8);
        $this->object->add(6);
        $this->object->add(9);

        $expectedSequences = array(
            'inOrder' => array(2,5,6,8,9),
            'preOrder' => array(5,2,8,6,9),
            'postOrder' => array(2,6,9,8,5),
            'levelOrder' => array(5,2,8,6,9),
        );

        $this->__testIterators($expectedSequences);

    }

    function testGetIteratorD() {
        //          5
        //        /
        //       2
        $this->object->add(5);
        $this->object->add(2);

        $expectedSequences = array(
            'inOrder' => array(2,5),
            'preOrder' => array(5,2),
            'postOrder' => array(2,5),
            'levelOrder' => array(5,2),
        );

        $this->__testIterators($expectedSequences);

    }

    function testGetIteratorE() {
        //     0
        //      \
        //       2
        $this->object->add(0);
        $this->object->add(2);

        $expectedSequences = array(
            'inOrder' => array(0,2),
            'preOrder' => array(0,2),
            'postOrder' => array(2,0),
            'levelOrder' => array(0,2),
        );

        $this->__testIterators($expectedSequences);

    }

    private function __testIterators(array $expectedSequences) {

        $iterators = array(
            'inOrder' => $this->object->getIterator(AvlTree::TRAVERSE_IN_ORDER),
            'preOrder' => $this->object->getIterator(AvlTree::TRAVERSE_PRE_ORDER),
            'postOrder' => $this->object->getIterator(AvlTree::TRAVERSE_POST_ORDER),
            'levelOrder' => $this->object->getIterator(AvlTree::TRAVERSE_LEVEL_ORDER),
        );

        foreach ($iterators as $algorithm => $iterator) {
            $actualSequence = array();
            foreach ($iterator as $item) {
                $actualSequence[] = $item;
            }

            $this->assertEquals($expectedSequences[$algorithm], $actualSequence);
        }

    }

    function testTraverse() {
        $sum = 0;
        $callback = function($item) use (&$sum) {
            $sum += $item;
        };

        $algorithms = array(
            AvlTree::TRAVERSE_IN_ORDER,
            AvlTree::TRAVERSE_PRE_ORDER,
            AvlTree::TRAVERSE_POST_ORDER,
            AvlTree::TRAVERSE_LEVEL_ORDER,
        );

        foreach ($algorithms as $algorithm) {
            $this->object->traverse($algorithm, $callback);
        }

        $this->assertEquals(0, $sum);

        $this->object->add(1);
        $this->object->add(2);

        foreach ($algorithms as $algorithm) {
            $this->object->traverse($algorithm, $callback);
        }

        $this->assertEquals(12, $sum);

    }

    /**
     * @covers \Spl\BinaryTree::getInOrderPredecessor
     */
    function testGetInOrderPredecessorBasic() {
        $root = new BinaryTree(5);
        $inOrderPredecessor = new BinaryTree(2);
        $root->setLeft($inOrderPredecessor);
        $root->setRight(new BinaryTree(6));

        $this->assertEquals($inOrderPredecessor, $root->getInOrderPredecessor());
    }


    /**
     * @covers \Spl\BinaryTree::getInOrderPredecessor
     */
    function testGetInOrderPredecessorWithLeftNodeHavingRightSubTree() {
        $root = new BinaryTree(5);
        $left = new BinaryTree(2);
        $root->setLeft($left);
        $root->setRight(new BinaryTree(6));

        $inOrderPredecessor = new BinaryTree(3);
        $left->setRight($inOrderPredecessor);

        $this->assertEquals($inOrderPredecessor, $root->getInOrderPredecessor());
    }

    /**
     * @covers \Spl\AvlTree::getBinaryTree
     */
    function testGetBinaryTree() {
        $this->assertNull($this->object->getBinaryTree());
    }

    function testConstructor() {
        $avl = new AvlTree(function ($a, $b) {
            if ($a < $b) {
                return 1;
            } elseif ($b < $a) {
                return -1;
            } else {
                return 0;
            }
        });

        $avl->add(4);
        $avl->add(3);
        $avl->add(5);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(5));
        $root->setRight(new BinaryTree(3));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $avl->getBinaryTree());
    }

    /**
     * @covers \Spl\BinaryTree::__clone
     * @covers \Spl\BinarySearchTree::__clone
     */
    function test__clone() {
        $avl = new AvlTree();
        $avl->add(1);
        $avl->add(0);
        $avl->add(2);

        $copy = clone $avl;
        $this->assertCount(3, $copy);

        $avl->add(3);
        $this->assertCount(3, $copy);
        $this->assertCount(4, $avl);
    }
}
