<?php
declare(strict_types=1);

namespace Jawira\PhingDiagram;


use Jawira\PhingDiagram\Puml\CallRelationship;
use Jawira\PhingDiagram\Puml\DependsRelationship;
use Jawira\PhingDiagram\Puml\Target;
use SimpleXMLElement;


class Diagram
{
    /**
     * Buildfile
     *
     * @var SimpleXMLElement
     */
    protected $xml;

    protected $targets = [];
    protected $depends = [];
    protected $calls = [];
    protected $buildfilePath;


    public function __construct(string $buildfilePath)
    {
        $this->buildfilePath = $buildfilePath;
        $this->loadXml($this->buildfilePath);
    }

    /**
     * @param string $path
     *
     * @return self
     */
    protected function loadXml(string $path): self
    {
        $content = file_get_contents($path);
        $this->xml = new SimpleXMLElement($content);

        return $this->loadTargets()->loadCalls()->loadDepends();
    }

    protected function loadTargets()
    {

        $targets = $this->xml->xpath('/project/target[@name]');
        $this->targets = [];
        foreach ($targets as $t) {
            $this->targets[] = new Target($t);
        }

        return $this;
    }

    protected function loadCalls()
    {
        /** @var Target $t */
        foreach ($this->targets as $t) {
            $targetName = $t->getName();
            $calls = $this->xml->xpath("/project/target[@name='{$targetName}']//phingcall/@target");
            foreach ($calls as $c) {
                $this->calls[] = new CallRelationship($t, $c);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadDepends()
    {
        $targets = $this->xml->xpath('/project/target[@depends]');
        $this->depends = [];
        foreach ($targets as $t) {
            $depends = (string)$t['depends'];
            $explode = explode(',', $depends);
            foreach ($explode as $e) {
                $this->depends[] = new DependsRelationship($t, trim($e));
            }
        }

        return $this;
    }

    /**
     * Returns PlantUML code
     */
    public function getCode()
    {
        $code = '@startuml' . PHP_EOL . PHP_EOL;

        foreach ($this->targets as $t) {
            $code .= (string)$t . PHP_EOL;
        }

        foreach ($this->calls as $c) {
            $code .= (string)$c . PHP_EOL;
        }

        foreach ($this->depends as $d) {
            $code .= (string)$d . PHP_EOL;
        }

        $code .= PHP_EOL . '@enduml' . PHP_EOL;

        return $code;
    }

    /**
     * Save puml to file
     */
    public function save()
    {
        $content = $this->getCode();
        file_put_contents($this->buildfilePath . '.puml', $content);
        return $this;
    }

}
