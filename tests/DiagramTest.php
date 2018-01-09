<?php

namespace Jawira\PhingVisualizer;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionObject;

class DiagramTest extends TestCase
{

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setup();
        vfsStream::copyFromFileSystem(__DIR__ . '/../resources/', $this->root);
    }

    public function tearDown()
    {
        unset($this->root);
    }

    /**
     * Tests that "__construct" calls "setBuildfile" method once
     *
     * @dataProvider buildfilesProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::__construct()
     *
     * @param string $buildfile
     */
    public function testConstructor(string $buildfile)
    {
        $buildfilePath = $this->root->getChild($buildfile)->url();

        $diagramMock = $this->getMockBuilder(Diagram::class)
            ->disableOriginalConstructor()
            ->setMethods(['setBuildfile'])
            ->getMock();
        $diagramMock->expects($this->once())
            ->method('setBuildfile')
            ->with($this->equalTo($buildfilePath));

        $reflectedClass = new ReflectionClass(Diagram::class);
        $constructor    = $reflectedClass->getConstructor();
        $constructor->invoke($diagramMock, $buildfilePath);
    }

    /**
     * Tests "setBuildfile" method
     *
     * @dataProvider buildfilesProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::setBuildfile()
     *
     * @param string $buildfile Path to buildfile
     */
    public function testSetBuildFile(string $buildfile)
    {
        $buildfilePath = $this->root->getChild($buildfile)->url();

        $diagramStub = $this->getMockBuilder(Diagram::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $reflection = new ReflectionObject($diagramStub);
        $method     = $reflection->getMethod('setBuildfile');
        $method->setAccessible(true);

        $method->invokeArgs($diagramStub, [$buildfilePath]);

        $this->assertThat(
            $diagramStub,
            $this->attributeEqualTo('buildfile', $buildfilePath)
        );
    }

    /**
     * @dataProvider buildfilesProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::getBuildfile()
     *
     * @param $buildfile
     */
    public function testGetBuildfile($buildfile)
    {
        $buildfilePath = $this->root->getChild($buildfile)->url();

        $diagramStub = $this->getMockBuilder(Diagram::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $reflection = new ReflectionObject($diagramStub);
        $property   = $reflection->getProperty('buildfile');
        $property->setAccessible(true);
        $property->setValue($diagramStub, $buildfilePath);
        $method = $reflection->getMethod('getBuildfile');
        $method->setAccessible(true);

        $this->assertSame(
            $buildfilePath,
            $method->invoke($diagramStub)
        );
    }

    /**
     * Test exception when buildfile location is invalid
     *
     * @dataProvider notExistentFilesProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::setBuildfile()
     *
     * @param string $invalidFile Invalid file path
     */
    public function testConstructorFailsOnInvalidInput(string $invalidFile)
    {
        $this->expectException(DiagramException::class);
        $this->expectExceptionMessage(sprintf('File "%s" is invalid.', $invalidFile));
        new Diagram($invalidFile);
    }

    /**
     * @dataProvider generateUrlProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::generateUrl()
     *
     * @param string $format   File extension (png or svg)
     * @param string $plantUml Diagram's source code
     * @param string $url      Expected result
     */
    public function testGenerateUrl(string $format, string $plantUml, string $url)
    {
        $diagramStub = $this->getMockBuilder(Diagram::class)
            ->disableOriginalConstructor()
            ->setMethods(['generatePuml', 'getFormat'])
            ->getMock();
        $diagramStub->method('generatePuml')->willReturn($plantUml);
        $diagramStub->method('getFormat')->willReturn($format);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertSame($url, $diagramStub->generateUrl($format));
    }

    /**
     * @dataProvider validateOutputLocationProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::validateOutputLocation()
     *
     * @param $buildfile
     * @param $format
     * @param $output
     * @param $expected
     */
    public function testValidateOutputLocation($buildfile, $format, $output, $expected)
    {
        $diagramStub = $this->getMockBuilder(Diagram::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBuildfile'])
            ->getMock();
        $diagramStub->method('getBuildfile')->willReturn($buildfile);

        $reflection = new ReflectionObject($diagramStub);
        $method     = $reflection->getMethod('validateOutputLocation');
        $method->setAccessible(true);

        $this->assertSame(
            $expected,
            $method->invokeArgs($diagramStub, [$format, $output])
        );
    }

    public function validateOutputLocationProvider()
    {
        return [
            'null output 1' => [
                sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'build.xml',
                Diagram::FORMAT_PNG,
                null,
                sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'build.png',
            ],
            'null output 2' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'demo-file.xml',
                Diagram::FORMAT_SVG,
                null,
                __DIR__ . DIRECTORY_SEPARATOR . 'demo-file.svg',
            ],
            'current dir'   => [
                __DIR__ . DIRECTORY_SEPARATOR . 'demo-file.xml',
                Diagram::FORMAT_PUML,
                __DIR__,
                __DIR__ . DIRECTORY_SEPARATOR . 'demo-file.puml',
            ],
            'output dir'    => [
                __DIR__ . DIRECTORY_SEPARATOR . 'demo-file.xml',
                'abc',
                sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'demo-file.abc',
                sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'demo-file.abc',
            ],
        ];
    }

    public function generateUrlProvider(): array
    {
        return [
            'Basic'         => [
                Diagram::FORMAT_PNG,
                file_get_contents(__DIR__ . '/../resources/puml/hello-world.puml'),
                'http://www.plantuml.com/plantuml/png/SoWkIImgAStDuNBAJrBGjLDmpCbCJbMmKiX8pSd9vt98pKi1IG80',
            ],
            'Phing'         => [
                Diagram::FORMAT_SVG,
                file_get_contents(__DIR__ . '/../resources/puml/buildfile.puml'),
                'http://www.plantuml.com/plantuml/svg/FSqX3e0m341HtrDKWa1W9yW22PoZP1KBOrrA2U7s33FrnHFVNqPgzvd0eYN6LMGcXUk8kP3IYQGgpohP5acYk2c_q1Nbgjn3jzynXLOSXWcReiF0XNDm8_YQ__81',
            ],
            'Spanish chars' => [
                Diagram::FORMAT_PNG,
                file_get_contents(__DIR__ . '/../resources/puml/spanish-characters.puml'),
                'http://www.plantuml.com/plantuml/png/SoWkIImgAStDuIh9BCb9LV18JCf9p4l9LqZDKqWjBaWyl34_X-lmn7mWhQ1hf-2SaLXGcdDuRPw2bSAX_O6bcOTNvYaKvASK7Lwea5XPcf9Ob9jgpuLGfGlK0zKDrB0mCb-Hoo4rBmLa7m00',
            ],
        ];
    }

    public function buildfilesProvider()
    {
        return [
            'Gist alphabraga'    => ['buildfiles/gist-alphabraga.xml'],
            'Gist kbariotis'     => ['buildfiles/gist-kbariotis.xml'],
            'Gist mapserver2007' => ['buildfiles/gist-mapserver2007.xml'],
            'Gist nfabre'        => ['buildfiles/gist-nfabre.xml'],
            'Phing doc'          => ['buildfiles/phing-doc.xml'],
        ];
    }

    public function notExistentFilesProvider()
    {
        return [
            'Not existent 1' => ['/this/file/do/not/exists.xml'],
            'Not existent 2' => ['/hello/world'],
            'Not existent 3' => [uniqid('file-', true)],
            'Temp dir'       => [sys_get_temp_dir()],
            'Temp file'      => [sys_get_temp_dir() . '/file.xml'],
            'root dir'       => ['/'],
        ];
    }

}
