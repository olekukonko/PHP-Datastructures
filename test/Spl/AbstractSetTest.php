<?php

namespace Spl;

class AbstractSetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Spl\AbstractSet::difference
     */
    function testDifferenceNone() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new HashSet();
        $b->add(0);
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $diff = $a->difference($b);
        $this->assertInstanceOf('Spl\\HashSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testDifferenceNone
     * @covers \Spl\AbstractSet::difference
     */
    function testDifferenceAll() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new HashSet();

        $diff = $a->difference($b);
        $this->assertCount(4, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        for ($i = 0; $i < 4; $i++) {
            $this->assertTrue($a->contains($i));
        }
    }

    /**
     * @depends testDifferenceNone
     * @covers \Spl\AbstractSet::difference
     */
    function testDifferenceSome() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new HashSet();
        $b->add(1);
        $b->add(3);
        $b->add(5);
        $b->add(7);

        $diff = $a->difference($b);
        $this->assertCount(2, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        $this->assertTrue($diff->contains(0));
        $this->assertTrue($diff->contains(2));

        $diff = $b->difference($a);
        $this->assertCount(2, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        $this->assertTrue($diff->contains(5));
        $this->assertTrue($diff->contains(7));
    }

    /**
     * @covers \Spl\AbstractSet::intersection
     */
    function testIntersectionEmpty() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);

        $b = new HashSet();
        $b->add(2);
        $b->add(3);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Spl\\HashSet', $intersection);
        $this->assertCount(0, $intersection);

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Spl\\HashSet', $intersection);
        $this->assertCount(0, $intersection);
    }

    /**
     * @covers \Spl\AbstractSet::intersection
     */
    function testIntersectionAll() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);

        $b = new HashSet();
        $b->add(0);
        $b->add(1);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Spl\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);
        $this->assertEquals($a, $intersection);


        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Spl\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);
        $this->assertEquals($b, $intersection);
    }

    /**
     * @covers \Spl\AbstractSet::intersection
     */
    function testIntersectionSome() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $b = new HashSet();
        $b->add(1);
        $b->add(3);
        $b->add(5);
        $b->add(7);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Spl\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->contains(1));
        $this->assertTrue($intersection->contains(3));

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Spl\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->contains(1));
        $this->assertTrue($intersection->contains(3));

    }

    /**
     * @covers \Spl\AbstractSet::relativeComplement
     */
    function testRelativeComplementSelf() {
        $a = new HashSet();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $complement = $a->relativeComplement($a);
        $this->assertInstanceOf('Spl\\HashSet', $complement);
        $this->assertCount(0, $complement);
    }

    /**
     * @covers \Spl\AbstractSet::relativeComplement
     */
    function testRelativeComplement() {
        $a = new HashSet();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $b = new HashSet();
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $complement = $a->relativeComplement($b);
        $this->assertInstanceOf('Spl\\HashSet', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->contains(1));

        $complement = $b->relativeComplement($a);
        $this->assertInstanceOf('Spl\\HashSet', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->contains(4));
    }

    /**
     * @covers \Spl\AbstractSet::union
     */
    function testUnionSelf() {
        $a = new HashSet();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $union = $a->union($a);
        $this->assertInstanceOf('Spl\\HashSet', $union);
        $this->count(3, $union);
        $this->assertNotSame($a, $union);
        $this->assertEquals($a, $union);
    }

    /**
     * @covers \Spl\AbstractSet::union
     */
    function testUnion() {
        $a = new HashSet();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $b = new HashSet();
        $b->add(2);
        $b->add(3);
        $b->add(4);

        $union = $a->union($b);
        $this->assertInstanceOf('Spl\\HashSet', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->contains($i));
        }

        $union = $b->union($a);
        $this->assertInstanceOf('Spl\\HashSet', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->contains($i));
        }

    }

}
