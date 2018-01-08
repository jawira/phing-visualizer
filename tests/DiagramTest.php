<?php

namespace Jawira\PhingVisualizer;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionObject;

class DiagramTest extends TestCase
{
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
        $diagramMock = $this->getMockBuilder(Diagram::class)
            ->disableOriginalConstructor()
            ->setMethods(['setBuildfile'])
            ->getMock();
        $diagramMock->expects($this->once())
            ->method('setBuildfile')
            ->with($this->equalTo($buildfile));

        $reflectedClass = new ReflectionClass(Diagram::class);
        $constructor    = $reflectedClass->getConstructor();
        $constructor->invoke($diagramMock, $buildfile);
    }

    /**
     * Tests "setBuildfile" method
     *
     * @dataProvider buildfilesProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::setBuildfile()
     *
     * @param string $buildfile Path to buildfile
     */
    public function testInputIsLoadedWithConstructor(string $buildfile)
    {
        $diagram = new Diagram($buildfile);
        $this->assertThat(
            $diagram,
            $this->attributeEqualTo('buildfile', $buildfile)
        );
    }

    /**
     * Test exception when buildfile location is invalid
     *
     * @dataProvider notExistentFilesProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::setBuildfile()
     *
     * @param string $input Invalid path
     */
    public function testConstructorFailsOnInvalidInput(string $input)
    {
        $this->expectException(DiagramException::class);
        $this->expectExceptionMessage(sprintf('File "%s" is invalid.', $input));
        new Diagram($input);
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
            'Gist alphabraga'    => [__DIR__ . '/../resources/buildfiles/gist-alphabraga.xml'],
            'Gist kbariotis'     => [__DIR__ . '/../resources/buildfiles/gist-kbariotis.xml'],
            'Gist mapserver2007' => [__DIR__ . '/../resources/buildfiles/gist-mapserver2007.xml'],
            'Gist nfabre'        => [__DIR__ . '/../resources/buildfiles/gist-nfabre.xml'],
            'Phing doc'          => [__DIR__ . '/../resources/buildfiles/phing-doc.xml'],
        ];
    }

    public function notExistentFilesProvider()
    {
        return [
            'Not existent 1' => ['/this/file/do/not/exists.xml'],
            'Not existent 2' => ['/hello/world'],
            'Not existent 3' => [uniqid('file-', true)],
            'Temp dir'       => [sys_get_temp_dir()],
            'root dir'       => ['/'],
        ];
    }

}
