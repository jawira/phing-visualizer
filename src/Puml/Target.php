<?php
/**
 * Created by PhpStorm.
 * User: jawira
 * Date: 15/11/17
 * Time: 11:49
 */

namespace Jawira\PhingDiagram\Puml;


use SimpleXMLElement;

class Target
{
    /**
     * @var SimpleXMLElement
     */
    protected $element;

    /**
     * Target constructor.
     *
     * @param SimpleXMLElement $element
     */
    public function __construct(SimpleXMLElement $element)
    {
        $this->element = $element;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('(%s)', $this->getName());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->element['name'];
    }

    /**
     * @return SimpleXMLElement
     */
    public function getElement()
    {
        return $this->element;
    }
}
