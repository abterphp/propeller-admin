<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Navigation\Navigation;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NavbarTest extends TestCase
{
    /** @var Navbar - System Under Test */
    protected $sut;

    /** @var UrlGenerator|MockObject */
    protected $urlGeneratorMock;

    public function setUp()
    {
        $this->urlGeneratorMock = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->setMethods(['createFromName'])
            ->getMock();

        $this->sut = (new Navbar($this->urlGeneratorMock))->init();

        parent::setUp();
    }

    public function testDecorateWithEmptyComponents()
    {
        $this->sut->decorate([]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateNonMatchingComponents()
    {
        $this->sut->decorate([new Component()]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateWithDoubleInit()
    {
        $this->sut->init();
        $this->sut->decorate([new Component()]);

        $this->testDecorateNonMatchingComponents();
    }

    public function testDecorateNavbar()
    {
        $navbar = new Navigation('', [Navigation::INTENT_NAVBAR]);

        $this->sut->decorate([$navbar]);

        $this->assertContains(Navbar::NAVBAR_CLASS, $navbar->getAttribute(Html5::ATTR_CLASS));
        $this->assertCount(1, $navbar->getNodes());
    }
}
