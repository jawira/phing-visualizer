<?php
/**
 * Created by PhpStorm.
 * User: jawira
 * Date: 15/11/17
 * Time: 12:14
 */

namespace Jawira\PhingDiagram\Puml;


use SimpleXMLElement;

class DependsRelationship
{
    protected $parent;
    protected $child;


    /**
     * @param SimpleXMLElement $parent
     * @param string           $child
     */
    public function __construct(SimpleXMLElement $parent, string $child)
    {
        $this->parent = $parent;
        $this->child = $child;
    }

    public function __toString()
    {
        return sprintf('(%s) --> (%s) : depends', $this->getParentName(), $this->getChildName());
    }

    public function getParentName()
    {
        return (string)$this->parent['name'];
    }

    public function getChildName()
    {
        return $this->child;
    }

}
