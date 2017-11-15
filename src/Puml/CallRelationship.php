<?php
/**
 * Created by PhpStorm.
 * User: jawira
 * Date: 15/11/17
 * Time: 12:14
 */

namespace Jawira\PhingDiagram\Puml;


use SimpleXMLElement;


class CallRelationship
{
    /**
     * @var Target
     */
    protected $parent;

    /**
     * @var SimpleXMLElement
     */
    protected $child;

    protected $order;

    /**
     * CallRelationship constructor.
     *
     * @param Target           $parent
     * @param SimpleXMLElement $child
     * @param int              $order
     */
    public function __construct(Target $parent, SimpleXMLElement $child, int $order)
    {
        $this->parent = $parent;
        $this->child = $child;
        $this->order = $order;
    }

    public function __toString()
    {
        return sprintf('(%s) --> (%s) : call:%s', $this->getParentName(), $this->getChildName(), $this->order);
    }

    public function getParentName()
    {
        return $this->parent->getName();
    }

    public function getChildName()
    {
        return (string)$this->child['target'];
    }
}
