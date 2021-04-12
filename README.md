# Data Structure Test #2 

## Problem statement
We would like to build a data tree where the parent node can count how many children its on lower levels.
Write a class User following the recommendations above:

1. Extendend from Model class
2. Any node knows how many children nodes are directly or indirected linked to it, considering all the levels: ```method getChildrenCount()```
4. Any node knows its parent: ```method parent()```
5. Any node knows its children: ```method getChildren()```
6. The function *MUST NOT* use recursion

## Proposed solution

### ```method getChildren()```
The solution was to write a algorithm without using recursion.

The main idea is to start on the node and walk thought its children.

We start on first level, relative of the node which called de counting method and put the node on ```cur_level```array.

For each node visited, the node will be pushed to two "baskets":
- ```counted``` contains every node visited
- ```next_level``` cointains the children of node visited 

Before go the next iteration, i.e next lower level we:
- update ```cur_level``` with the nodes inside the ```next_level```
- empty the ```next_level```

The process will finish when we reach the bottom level and ```cur_level``` is null.

### ```method children()```

The method do not use recursion, just return the array children.

The ```addChild``` method is responsible to chain the nodes and allow the solution to be 'recursion-free'


### Auxiliary methods

- ```addChild```: to add childs to a node
- ```setName```: to set the name of the node
- ```getName```: to get the name of the node
- ```setParent```: to set the parent of the node 
- ```getLevels```: to get how many levels the tree has 
- ```removeChild```: to remove a child - *I have a question about the expected behavior #2*
- ```ifChildFoundThenRemove```: to help ```removeChild``` method


## Test Driven Development

All the devolopement was led using unit tests.

On folder ```/tests/Unit``` we have a file called ```UserTest.php``` where we can run all tests that validates the three examples proposed on this test.

Also I wrote two more examples (```example0``` and ```exampleD````) to show it is scalable.


## Future features

- Build a simple way to show graphically and dinamycally when add or remove a node and calculate the children of each node. 
  - Use a mermaid markdown language to buid graphs using some vue.js component
  - Something like that:
````
graph TD
    A((3)) --> B((2))
    A((3)) --> C((0))
    A((3)) --> D((0))
    B((2)) --> E((0))
    B((2)) --> F((1))
    F((1)) --> G((0))

classDef blue fill:#85C1E9,stroke:#333,stroke-width:0px;
classDef orange fill:#FAD7A0,stroke:#333,stroke-width:0px;
classDef green fill:#76D7C4 ,stroke:#333,stroke-width:0px;
classDef purple fill:#AF7AC5,stroke:#333,stroke-width:0px;

class A blue
class B green
class C green
class D green
class E orange
class F orange
class G purple```
  - The markdown above can be rendered using this live editor https://mermaid-js.github.io/mermaid-live-editor

## Questions to validate
- [#2 Validate the expected behavior when a node is removed: remove the branch or only the node an keep its children?](https://github.com/leoapsilva/dstest2/issues/2)
- [#3 Validate what is the unique key of a node](https://github.com/leoapsilva/dstest2/issues/3)

# Bugs found
- [#4 After removing a node on test example C, the children count is wrong](https://github.com/leoapsilva/dstest2/issues/4)

