<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private $children = [];

    private $parent;

    private $id;

    private $name;
    
    private $childrenCount;

    private $levels;

    public function __construct($name = NULL)
    {
        $this->name = $name;
    }

    public function parent(): User
    {
        return $this->parent;
    }

    public function &children(): array
    {
        return $this->children;        
    }

    public function getChildrenCount(): int
    {
        $counted = [];
        $next_level = [];
        $cur_level = $this->children();
        $this->levels = 1;

        do {
            if (count($cur_level))  $this->levels++;
                
            foreach ($cur_level as $node) {
                if (is_array($node)) {
                    foreach($node as $element){
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

        $this->childrenCount = count($counted);
        return $this->childrenCount;
    }

    public function addChild(User $child)
    {
        $child->setParent($this);
        return array_push($this->children, $child);
    }

    public function removeChild(string $name)
    {
        $removed = false;
        $next_level = [];
        $cur_level = &$this->children();

        do {
            foreach ($cur_level as $node) {
                if (is_array($node)) {
                    foreach($node as $element){
                        if ($element->children() == null) {
                            $removed = $this->ifChildFoundThenRemove($element, $cur_level, $name);
                        } else {
                            array_push($next_level, $element->children());
                            $removed = $this->ifChildFoundThenRemove($element, $cur_level, $name);
                        }
                    }
                } else {
                    if ($node->children() == null) {
                        $removed = $this->ifChildFoundThenRemove($node, $cur_level, $name);
                    } else {
                        array_push($next_level, $node->children());
                        $removed = $this->ifChildFoundThenRemove($node, $cur_level, $name);
                    }
                }
            }

            $cur_level = $next_level;
            $next_level = [];

            if($removed) {
                break;
            }
        } while ($cur_level != null); 
    }

    private function ifChildFoundThenRemove($child, &$parent, $name)
    {
        $found = false;

        $key = array_search($child, $parent);
        if ($child->name == $name) {
            $spliced_array = array_splice($parent, $key, 1);
            $count = $child->parent->getChildrenCount();
            $count = $this->getChildrenCount();
            $found = true;
        }

        return $found;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setParent(User $parent)
    {
        $this->parent = $parent;
    }

    public function getLevels()
    {
        $this->getChildrenCount();
        return $this->levels;
    }
}
