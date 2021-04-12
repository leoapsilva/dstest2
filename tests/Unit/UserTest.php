<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    private $example0;

    private $exampleA;
    private $exampleB;
    private $exampleC;
    private $exampleD;

    protected function setUp(): void
    {
        $this->example0 = new User("parent");
        $this->exampleA = new User("A");
        $this->exampleB = new User("B");
        $this->exampleC = new User("C");
        $this->exampleD = new User("D");
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_construct()
    {
        $this->assertIsObject($this->example0);
    }

    public function test_add_child()
    {
        $child = new User('child 1');

        $ret = $this->example0->addChild($child);

        $this->assertEquals(1, $ret, 'ERROR CHILD NOT ADDED');
    }

    public function test_example_0()
    {
        $child1 = new User('child 1');
        $child1->addChild(new User('grandchild 1'));
        $child1->addChild(new User('grandchild 2'));

        $child2 = new User('child 2');
        $child2->addChild(new User('grandchild 3'));
        $child2->addChild(new User('grandchild 4'));
        $child2->addChild(new User('grandchild 5'));

        $gchild = new User('grandchild 6');
        $gchild->addChild(new User('grandgrandchild 1'));
        $gchild->addChild(new User('grandgrandchild 2'));

        $child2->addChild($gchild);

        $this->example0->addChild($child1);
        $this->example0->addChild($child2);
        
        $childrenCount = $this->example0->getChildrenCount(); // 10 children
        $this->assertEquals(10, $childrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $ggchildrenCount = $gchild->getChildrenCount(); //2 children
        $this->assertEquals(2, $ggchildrenCount, 'ERROR ON COUNTING GRAND GRAN CHILDREN'); 
    }

    public function test_example_A()
    {
        // Has 3 children
        $this->exampleA->addChild(new User("child 1")); // 0 child
        $this->exampleA->addChild(new User("child 2")); // 0 child
        $this->exampleA->addChild(new User("child 3")); // 0 child

        $aChildrenCount = $this->exampleA->getChildrenCount(); // 3 children
        $this->assertEquals(3, $aChildrenCount, 'ERROR ON COUNTING "A" CHILDREN');

        // All counts must be 0
        foreach($this->exampleA->children() as $child) {
            $this->assertEquals(0, $child->getChildrenCount(), 'ERROR ON COUNTING "A" GRANDCHILDREN');
        }
    }

    public function test_example_B()
    {
        // Has 3 children
        $this->exampleB->addChild(new User("child 1")); // 0 child

        $child2 = new User("child 2");

        $this->exampleB->addChild($child2); // 1 child

        $child2->addChild(new User("grandchild 1")); // 0 child

        $bChildrenCount = $this->exampleB->getChildrenCount(); // 3 children
        $this->assertEquals(3, $bChildrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $child1Count = $this->exampleB->children()[0]->getChildrenCount(); // 0 child
        $this->assertEquals(0, $child1Count, 'ERROR ON COUNTING "child 1" CHILDREN');

        $child2Count = $this->exampleB->children()[1]->getChildrenCount(); // 1 child
        $this->assertEquals(1, $child2Count, 'ERROR ON COUNTING "child 2" CHILDREN');
    }

    public function test_example_C()
    {
        // Has 7 children
        $this->exampleC->addChild(new User("child 1")); // 0 child

        $child2 = new User("child 2");

        $this->exampleC->addChild($child2); // 1 child

        $child3 = new User("child 3"); // 3 children

        $this->exampleC->addChild($child3); // 3 children

        $child2->addChild(new User("grandchild 1")); // 0 child

        $grandChild2 = new User("grandchild 2"); // 1 child
        $grandChild2->addChild(new User("grandgrandchild 1")); // 0 child

        $child3->addChild($grandChild2);
        $child3->addChild(new User("grandchild 3")); // 0 child

        $cChildrenCount = $this->exampleC->getChildrenCount(); // 7 children
        $this->assertEquals(7, $cChildrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $child1Count = $this->exampleC->children()[0]->getChildrenCount(); // 0 child
        $this->assertEquals(0, $child1Count, 'ERROR ON COUNTING "child 1" CHILDREN');

        $child2Count = $this->exampleC->children()[1]->getChildrenCount(); // 1 child
        $this->assertEquals(1, $child2Count, 'ERROR ON COUNTING "child 2" CHILDREN');

        $child3Count = $this->exampleC->children()[2]->getChildrenCount(); // 3 child
        $this->assertEquals(3, $child3Count, 'ERROR ON COUNTING "child 3" CHILDREN');
    }

    public function test_example_D()
    {
        // Has 15 children
        $this->exampleD->addChild(new User("child 1")); // 0 child

        $child2 = new User("child 2");

        $this->exampleD->addChild($child2); // 1 child

        $child3 = new User("child 3"); // 3 children

        $this->exampleD->addChild($child3); // 3 children

        $child2->addChild(new User("grandchild 1")); // 0 child

        $grandChild2 = new User("grandchild 2"); // 1 child
        $grandChild2->addChild(new User("grandgrandchild 1")); // 0 child
        
        $grandgrandchild2= new User("grandgrandchild 2");
        $grandgrandchild2->addChild(new User("gggchild 1"));
        $grandgrandchild2->addChild(new User("gggchild 2"));
        $grandChild2->addChild($grandgrandchild2); // 0 child

        $child3->addChild($grandChild2);
        $child3->addChild(new User("grandchild 3")); // 0 child

        $child2->addChild($grandChild2);

        $cChildrenCount = $this->exampleD->getChildrenCount(); // 10 children
        $this->assertEquals(15, $cChildrenCount, 'ERROR ON COUNTING ALL CHILDREN');

        $child1Count = $this->exampleD->children()[0]->getChildrenCount(); // 0 child
        $this->assertEquals(0, $child1Count, 'ERROR ON COUNTING "child 1" CHILDREN');

        $child2Count = $this->exampleD->children()[1]->getChildrenCount(); // 1 child
        $this->assertEquals(6, $child2Count, 'ERROR ON COUNTING "child 2" CHILDREN');

        $child3Count = $this->exampleD->children()[2]->getChildrenCount(); // 3 child
        $this->assertEquals(6, $child3Count, 'ERROR ON COUNTING "child 3" CHILDREN');
    }

    public function test_example_A_parents()
    {
        $this->test_example_A();
        $children = $this->exampleA->children();

        foreach($children as $child) {
            $this->assertEquals($child->parent(), $this->exampleA, 'ERROR ON PARENT CHECK');
        }
    }

    public function test_levels()
    {
        $child1 = new User('child 1');

        $this->test_example_0();
        $this->test_example_A();
        $this->test_example_B();
        $this->test_example_C();
        $this->test_example_D();

        $this->assertEquals(1, $child1->getLevels(), 'ERROR ON LEVELS CHECK');
        $this->assertEquals(0, $child1->getChildrenCount(), 'ERROR ON LEVELS CHECK');

        $this->assertEquals(4, $this->example0->getLevels(), 'ERROR ON LEVELS CHECK');
        $this->assertEquals(2, $this->exampleA->getLevels(), 'ERROR ON LEVELS CHECK');
        $this->assertEquals(3, $this->exampleB->getLevels(), 'ERROR ON LEVELS CHECK');
        $this->assertEquals(4, $this->exampleC->getLevels(), 'ERROR ON LEVELS CHECK');
        $this->assertEquals(5, $this->exampleD->getLevels(), 'ERROR ON LEVELS CHECK');
    }

    public function test_removeChild()
    {
        $this->test_example_C();

        // Originally the example C has 4 levels
        $this->assertEquals(4, $this->exampleC->getLevels(), 'ERROR ON LEVELS CHECK');

        // ... and 7 children
        $cChildrenCount = $this->exampleC->getChildrenCount(); 
        $this->assertEquals(7, $cChildrenCount, 'ERROR ON CHILDREN COUNT CHECK');

        // Removing the rightmost branch: node and its children
         $this->exampleC->removeChild("child 3");

        // Now the example C have 3 levels ...
        $this->assertEquals(3, $this->exampleC->getLevels(), 'ERROR ON LEVELS CHECK');
        
        // ... and 3 children
        $cChildrenCount = $this->exampleC->getChildrenCount(); 
        $this->assertEquals(3, $cChildrenCount, 'ERROR ON CHILDREN COUNT CHECK');
    }   

}


