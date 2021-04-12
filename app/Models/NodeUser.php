<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Foreach_;

class NodeUser extends Model
{
    use HasFactory;

    private $children = [];

    private $parent;

    private $id;

    private $name;
    
    private $childrenCount;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function parent(): NodeUser
    {
        return $this->parent;
    }

    public function children(): array
    {
        return $this->children;        
    }

    public function getChildrenCount(): int
    {
        $counted = [];
        $next_level = [];
        $cur_level = $this->children();;
        $level = 0;

        do {
            $level++;

            foreach ($cur_level as $node) {

                if (is_array($node))
                {
                    foreach($node as $element)
                    {
                        if ($element->children() == null) {
                            array_push($counted, $element);
        
                        } else {
                            array_push($next_level, $element->children());
                            array_push($counted, $element);
                        }
                    }
                } else {
                    if ($node->children() == null) {
                        array_push($counted, $node);
                    } else {
                        array_push($next_level, $node->children());
                        array_push($counted, $node);
                    }
                }
            }

            $cur_level = $next_level;
            $next_level = [];
            
        } while ($cur_level != null); 
            
        
        return  count($counted);
    }

    public function addChild(NodeUser $child)
    {
        $child->setParent($this);
        return array_push($this->children, $child);
    }

    public function removeChild(NodeUser $child)
    {
        return collect($this->children)->firstWhere('id', $child->id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setParent(NodeUser $parent)
    {
        $this->parent = $parent;
    }


}
