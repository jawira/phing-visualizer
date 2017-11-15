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
    protected $order;


    /**
     * @param SimpleXMLElement $parent
     * @param string           $child
     * @param int              $order
     */
    public function __construct(SimpleXMLElement $parent, string $child, int $order)
    {
        $this->parent = $parent;
        $this->child = $child;
        $this->order = $order;
    }

    public function __toString()
    {
        return sprintf('(%s) --> (%s) : depend:%s', $this->getParentName(), $this->getChildName(), $this->order);
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
