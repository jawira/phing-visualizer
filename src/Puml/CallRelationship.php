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

    /**
     * CallRelationship constructor.
     *
     * @param Target           $parent
     * @param SimpleXMLElement $child
     */
    public function __construct(Target $parent, SimpleXMLElement $child)
    {
        $this->parent = $parent;
        $this->child = $child;
    }

    public function __toString()
    {
        return sprintf('(%s) --> (%s) : calls', $this->getParentName(), $this->getChildName());
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
