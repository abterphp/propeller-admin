<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Navigation;

use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Component\Button;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    /** @var Header - System Under Test */
    protected $sut;

    /** @var Button|MockObject */
    protected $brandBtnMock;

    public function setUp(): void
    {
        $this->brandBtnMock = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $this->sut = new Header($this->brandBtnMock);

        parent::setUp();
    }

    public function testRenderContainsBrandButton()
    {
        $brandBtnRendered = 'foo';

        $this->brandBtnMock->expects($this->atLeastOnce())->method('__toString')->willReturn($brandBtnRendered);

        $this->assertStringContainsString($brandBtnRendered, (string)$this->sut);
    }

    public function testRenderContainsHamburgerButton()
    {
        $brandBtnRendered     = 'foo';
        $hamburgerBtnRendered = 'bar';

        /** @var Button|MockObject $hamburgerBtnMock */
        $hamburgerBtnMock = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $this->sut = new Header($this->brandBtnMock, $hamburgerBtnMock);

        $this->brandBtnMock->expects($this->atLeastOnce())->method('__toString')->willReturn($brandBtnRendered);
        $hamburgerBtnMock->expects($this->atLeastOnce())->method('__toString')->willReturn($brandBtnRendered);

        $this->assertStringContainsString($hamburgerBtnRendered, (string)$this->sut);
    }

    public function testGetExtended()
    {
        /** @var Button|MockObject $hamburgerBtnMock */
        $hamburgerBtnMock = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $extraComponent = $this->getMockBuilder(Component::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $this->sut = new Header($this->brandBtnMock, $hamburgerBtnMock);

        $this->sut[] = $extraComponent;

        $actualResult = $this->sut->getExtendedNodes();

        $this->assertContains($this->brandBtnMock, $actualResult);
        $this->assertContains($hamburgerBtnMock, $actualResult);
        $this->assertContains($extraComponent, $actualResult);
    }
}
