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
        vfsStream::copyFromFileSystem(__DIR__ . '/../../resources/', $this->root);
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
     *
     * @throws \ReflectionException
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
     * @covers       \Jawira\PhingVisualizer\Diagram::__construct()
     * @covers       \Jawira\PhingVisualizer\Diagram::setBuildfile()
     *
     * @param string $invalidFile Invalid file path
     *
     * @throws \Jawira\PhingVisualizer\DiagramException
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
     * @covers \Jawira\PhingVisualizer\Diagram::generateUrl()
     */
    public function testGenerateUrlException()
    {
        $format = 'abc';

        $this->expectException(DiagramException::class);
        $this->expectExceptionMessage(sprintf('Format "%s" not handled.', $format));

        $diagramStub = $this->getMockBuilder(Diagram::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['generatePuml'])
                            ->getMock();

        /** @noinspection PhpUndefinedMethodInspection */
        $diagramStub->generateUrl($format, null);
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

    /**
     * @dataProvider notExistentDirsProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::validateOutputLocation()
     *
     * @param string $dir Invalid output directory
     */
    public function testInvalidOutputDirWhenValidatingOutputLocation(string $dir)
    {
        $this->expectException(DiagramException::class);
        $this->expectExceptionMessage(
            sprintf('Dir "%s" is invalid.', $dir)
        );

        $diagramStub = $this->getMockBuilder(Diagram::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getBuildfile'])
                            ->getMock();
        $diagramStub->method('getBuildfile')->willReturn('/demo.xml');

        $reflection = new ReflectionObject($diagramStub);
        $method     = $reflection->getMethod('validateOutputLocation');
        $method->setAccessible(true);

        $method->invokeArgs($diagramStub, [Diagram::FORMAT_PNG, $dir]);
    }

    /**
     * @dataProvider saveProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::save()
     *
     * @param string $format
     * @param string $outputPath
     * @param string $contentPath
     */
    public function testSave(string $format, string $outputPath, string $contentPath)
    {
        $outputVfs  = $this->root->url() . DIRECTORY_SEPARATOR . $outputPath; // output.png
        $contentVfs = $this->root->getChild($contentPath)->url(); // image.png
        $content    = file_get_contents($contentVfs);

        $diagramStub = $this->getMockBuilder(Diagram::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['generatePuml', 'generateImage', 'validateOutputLocation'])
                            ->getMock();
        $diagramStub->method('generatePuml')->willReturn($content);
        $diagramStub->method('generateImage')->willReturn($content);
        $diagramStub->method('validateOutputLocation')->willReturn($outputVfs);

        /** @noinspection PhpUndefinedMethodInspection */
        $currentSize = $diagramStub->save($format, $outputVfs);

        $this->assertFileEquals($outputVfs, $contentVfs);
        $this->assertNotEmpty($currentSize);
    }

    /**
     * @covers \Jawira\PhingVisualizer\Diagram::save()
     */
    public function testSaveNotHandledFormat()
    {
        $format = 'abc';

        $this->expectException(DiagramException::class);
        $this->expectExceptionMessage(sprintf('Format "%s" not handled.', $format));

        $diagramStub = $this->getMockBuilder(Diagram::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['generatePuml', 'generateImage', 'validateOutputLocation'])
                            ->getMock();

        /** @noinspection PhpUndefinedMethodInspection */
        $diagramStub->save($format, null);
    }

    /**
     * @covers       \Jawira\PhingVisualizer\Diagram::generateImage()
     * @dataProvider generateImageProvider
     *
     * @param string $format Format to generate, png, svg,...
     * @param string $url    PlantUml url
     * @param string $image  Relative path to existing file
     */
    public function testGenerateImage(string $format, string $url, string $image)
    {
        $expectedImage = file_get_contents($this->root->getChild($image)->url());

        $diagramStub = $this->getMockBuilder(Diagram::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['generateUrl'])
                            ->getMock();
        $diagramStub->method('generateUrl')->willReturn($url);

        $reflection = new ReflectionObject($diagramStub);
        $method     = $reflection->getMethod('generateImage');
        $method->setAccessible(true);

        $generatedImage = $method->invokeArgs($diagramStub, [$format]);

        if ($format === Diagram::FORMAT_SVG) {
            $this->assertXmlStringEqualsXmlString($expectedImage, $generatedImage);
        } else {
            $this->assertSame($expectedImage, $generatedImage);
        }
    }

    /**
     * @dataProvider generatePumlProvider
     * @covers       \Jawira\PhingVisualizer\Diagram::generatePuml()
     *
     * @param string $buildfile
     * @param string $puml
     */
    public function testGeneratePuml(string $buildfile, string $puml)
    {
        $buildfileVfs = $this->root->getChild($buildfile)->url();
        $expectedPuml = file_get_contents($this->root->getChild($puml)->url());

        $diagramStub = $this->getMockBuilder(Diagram::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['getBuildfile'])
                            ->getMock();
        $diagramStub->method('getBuildfile')->willReturn($buildfileVfs);

        $reflection = new ReflectionObject($diagramStub);
        $method     = $reflection->getMethod('generatePuml');
        $method->setAccessible(true);

        $puml = $method->invoke($diagramStub);

        $this->assertSame($expectedPuml, $puml);

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
            'png - Basic'         => [
                Diagram::FORMAT_PNG,
                file_get_contents(__DIR__ . '/../../resources/phpunit/bob-alice.puml'),
                'http://www.plantuml.com/plantuml/png/SoWkIImgAStDuNBAJrBGjLDmpCbCJbMmKiX8pSd9vt98pKi1IG80',
            ],
            'eps - Basic'         => [
                Diagram::FORMAT_EPS,
                file_get_contents(__DIR__ . '/../../resources/phpunit/bob-alice.puml'),
                'http://www.plantuml.com/plantuml/eps/SoWkIImgAStDuNBAJrBGjLDmpCbCJbMmKiX8pSd9vt98pKi1IG80',
            ],
            'svg - Phing'         => [
                Diagram::FORMAT_SVG,
                file_get_contents(__DIR__ . '/../../resources/phpunit/buildfile.puml'),
                'http://www.plantuml.com/plantuml/svg/FSqX3e0m341HtrDKWa1W9yW22PoZP1KBOrrA2U7s33FrnHFVNqPgzvd0eYN6LMGcXUk8kP3IYQGgpohP5acYk2c_q1Nbgjn3jzynXLOSXWcReiF0XNDm8_YQ__81',
            ],
            'png - Spanish chars' => [
                Diagram::FORMAT_PNG,
                file_get_contents(__DIR__ . '/../../resources/phpunit/spanish-characters.puml'),
                'http://www.plantuml.com/plantuml/png/SoWkIImgAStDuIh9BCb9LV18JCf9p4l9LqZDKqWjBaWyl34_X-lmn7mWhQ1hf-2SaLXGcdDuRPw2bSAX_O6bcOTNvYaKvASK7Lwea5XPcf9Ob9jgpuLGfGlK0zKDrB0mCb-Hoo4rBmLa7m00',
            ],
        ];
    }

    public function buildfilesProvider()
    {
        return [
            'Dummy'              => ['buildfiles/dummy.xml'],
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

    public function notExistentDirsProvider()
    {
        return [
            'Not existent 1' => ['/this/dir/do/not/exists.xml'],
            'Not existent 2' => ['/hello/world/'],
            'Not existent 3' => ['/directory/file.tmp'],
            'Temp dir'       => [sys_get_temp_dir() . '/invalid/dir/here/'],
        ];
    }

    public function saveProvider()
    {
        return [
            'Save png'  => [
                Diagram::FORMAT_PNG,
                'buildfiles/output.png',
                'phpunit/bob-alice.png',
            ],
            'Save svg'  => [
                Diagram::FORMAT_SVG,
                'buildfiles/output.svg',
                'phpunit/bob-alice.svg',
            ],
            'Save puml' => [
                Diagram::FORMAT_PUML,
                'buildfiles/output.puml',
                'phpunit/bob-alice.puml',
            ],
            'Save eps'  => [
                Diagram::FORMAT_EPS,
                'buildfiles/output.eps',
                'phpunit/bob-alice.eps',
            ],
        ];
    }

    public function generateImageProvider()
    {
        return [
            'Generate svg' => [
                Diagram::FORMAT_SVG,
                'http://www.plantuml.com/plantuml/svg/SoWkIImgAStDuNBAJrBGjLDmpCbCJbMmKiX8pSd9vt98pKi1IG80',
                'phpunit/bob-alice.svg',
            ],
            'Generate png' => [
                Diagram::FORMAT_PNG,
                'http://www.plantuml.com/plantuml/png/SoWkIImgAStDuNBAJrBGjLDmpCbCJbMmKiX8pSd9vt98pKi1IG80',
                'phpunit/bob-alice.png',
            ],
            'Generate eps' => [
                Diagram::FORMAT_EPS,
                'http://www.plantuml.com/plantuml/eps/SoWkIImgAStDuNBAJrBGjLDmpCbCJbMmKiX8pSd9vt98pKi1IG80',
                'phpunit/bob-alice.eps',
            ],
        ];
    }

    public function generatePumlProvider()
    {
        return [
            'Dummy'              => [
                'buildfiles/dummy.xml',
                'puml/dummy.puml',
            ],
            'Gist alphabraga'    => [
                'buildfiles/gist-alphabraga.xml',
                'puml/gist-alphabraga.puml',
            ],
            'Gist kbariotis'     => [
                'buildfiles/gist-kbariotis.xml',
                'puml/gist-kbariotis.puml',
            ],
            'Gist mapserver2007' => [
                'buildfiles/gist-mapserver2007.xml',
                'puml/gist-mapserver2007.puml',
            ],
            'Gist nfabre'        => [
                'buildfiles/gist-nfabre.xml',
                'puml/gist-nfabre.puml',
            ],
            'Phing doc'          => [
                'buildfiles/phing-doc.xml',
                'puml/phing-doc.puml',
            ],
        ];
    }
}
