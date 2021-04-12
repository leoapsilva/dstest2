<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\NodeUser;

class NodeUserTest extends TestCase
{
    private $parent;
    private $testA;
    private $testB;
    private $testC;

    protected function setUp(): void
    {
        $this->parent = new NodeUser("parent");
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_construct()
    {
        $this->assertIsObject($this->parent);
    }

    public function test_add_child()
    {
        $child = new NodeUser('child 1');

        $ret = $this->parent->addChild($child);

        $this->assertEquals(1, $ret, 'ERROR CHILD NOT ADDED');
    }

    public function test_add_childs_and_grandchilds_and_grandgrandchildren()
    {
        $child1 = new NodeUser('child 1');
        $child1->addChild(new NodeUser('grandchild 1'));
        $child1->addChild(new NodeUser('grandchild 2'));

        $child2 = new NodeUser('child 2');
        $child2->addChild(new NodeUser('grandchild 3'));
        $child2->addChild(new NodeUser('grandchild 4'));
        $child2->addChild(new NodeUser('grandchild 5'));

        $gchild = new NodeUser('grandchild 6');
        $gchild->addChild(new NodeUser('grandgrandchild 1'));
        $gchild->addChild(new NodeUser('grandgrandchild 2'));

        $child2->addChild($gchild);

        $this->parent->addChild($child1);
        $this->parent->addChild($child2);
        
        $childrenCount = $this->parent->getChildrenCount(); // 10 children
        $this->assertEquals(10, $childrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $ggchildrenCount = $gchild->getChildrenCount(); //2 children
        $this->assertEquals(2, $ggchildrenCount, 'ERROR ON COUNTING GRAND GRAN CHILDREN');
    }

    public function test_example_A()
    {
        // Has 3 children
        $this->testA = new NodeUser("A");

        $this->testA->addChild(new NodeUser("child 1")); // 0 child
        $this->testA->addChild(new NodeUser("child 2")); // 0 child
        $this->testA->addChild(new NodeUser("child 3")); // 0 child


        $aChildrenCount = $this->testA->getChildrenCount(); // 3 children
        $this->assertEquals(3, $aChildrenCount, 'ERROR ON COUNTING "A" CHILDREN');

        // All counts must be 0
        foreach($this->testA->children() as $child) {
            $this->assertEquals(0, $child->getChildrenCount(), 'ERROR ON COUNTING "A" GRANDCHILDREN');
        }
    }


    public function test_example_B()
    {
        // Has 3 children
        $this->testB = new NodeUser("B");

        $this->testB->addChild(new NodeUser("child 1")); // 0 child

        $child2 = new NodeUser("child 2");

        $this->testB->addChild($child2); // 1 child

        $child2->addChild(new NodeUser("grandchild 1")); // 0 child

        $bChildrenCount = $this->testB->getChildrenCount(); // 3 children
        $this->assertEquals(3, $bChildrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $child1Count = $this->testB->children()[0]->getChildrenCount(); // 0 child
        $this->assertEquals(0, $child1Count, 'ERROR ON COUNTING "child 1" CHILDREN');

        $child2Count = $this->testB->children()[1]->getChildrenCount(); // 1 child
        $this->assertEquals(1, $child2Count, 'ERROR ON COUNTING "child 2" CHILDREN');
    }


    public function test_example_C()
    {
        // Has 7 children
        $this->testC = new NodeUser("C");

        $this->testC->addChild(new NodeUser("child 1")); // 0 child

        $child2 = new NodeUser("child 2");

        $this->testC->addChild($child2); // 1 child

        $child3 = new NodeUser("child 3"); // 3 children

        $this->testC->addChild($child3); // 3 children

        $child2->addChild(new NodeUser("grandchild 1")); // 0 child

        $grandChild2 = new NodeUser("grandchild 2"); // 1 child
        $grandChild2->addChild(new NodeUser("grandgrandchild 1")); // 0 child

        $child3->addChild($grandChild2);
        $child3->addChild(new NodeUser("grandchild 3")); // 0 child

        $cChildrenCount = $this->testC->getChildrenCount(); // 7 children
        $this->assertEquals(7, $cChildrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $child1Count = $this->testC->children()[0]->getChildrenCount(); // 0 child
        $this->assertEquals(0, $child1Count, 'ERROR ON COUNTING "child 1" CHILDREN');

        $child2Count = $this->testC->children()[1]->getChildrenCount(); // 1 child
        $this->assertEquals(1, $child2Count, 'ERROR ON COUNTING "child 2" CHILDREN');

        $child3Count = $this->testC->children()[2]->getChildrenCount(); // 3 child
        $this->assertEquals(3, $child3Count, 'ERROR ON COUNTING "child 3" CHILDREN');
    }
}
