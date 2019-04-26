<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Html\Collection;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Navigation\Dropdown;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Framework\Navigation\UserBlock;
use AbterPhp\Framework\Session\MockSessionFactory;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PrimaryTest extends TestCase
{
    /** @var Primary - System Under Test */
    protected $sut;

    /** @var UrlGenerator|MockObject */
    protected $urlGeneratorMock;

    public function setUp()
    {
        $this->urlGeneratorMock = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->setMethods(['createFromName'])
            ->getMock();

        $this->sut = (new Primary($this->urlGeneratorMock))->init();

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

    public function testDecorateEmptyPrimaryNavigation()
    {
        $navigation = new Navigation([Navigation::INTENT_PRIMARY]);

        $this->sut->decorate([$navigation]);

        $this->assertContains(Primary::PRIMARY_CLASS, $navigation->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecoratePrimaryNavigationWithGeneralItem()
    {
        $item = new Item();

        $navigation = new Navigation([Navigation::INTENT_PRIMARY]);
        $navigation->addItem($item);

        $this->sut->decorate([$navigation]);

        $this->assertFalse($item->hasAttribute(Html5::ATTR_CLASS));
    }

    public function testDecoratePrimaryNavigationWithUserGroup()
    {
        $sessionData = [
            Session::USERNAME => 'foo',
        ];

        /** @var ISession|MockObject $sessionMock */
        $sessionMock = MockSessionFactory::create($this, $sessionData);

        $item = new Item(new UserBlock($sessionMock), [UserBlock::class]);

        $navigation = new Navigation([Navigation::INTENT_PRIMARY]);
        $navigation->addItem($item);

        $this->sut->decorate([$navigation]);

        $this->assertEquals(Primary::USER_BLOCK_ITEM_CLASS, $item->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecoratePrimaryNavigationWithDropdownItemWithoutWrapper()
    {
        $dropdown = new Dropdown();
        $item     = new Item($dropdown, [UserBlock::class]);

        $navigation = new Navigation([Navigation::INTENT_PRIMARY]);
        $navigation->addItem($item);

        $this->sut->decorate([$navigation]);

        $this->assertEquals(Primary::DROPDOWN_CLASS, $dropdown->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecoratePrimaryNavigationWithDropdownItemWithWrapper()
    {
        $wrapper = new Component();

        $dropdown = new Dropdown();
        $dropdown->setWrapper($wrapper);

        $item = new Item($dropdown, [UserBlock::class]);

        $navigation = new Navigation([Navigation::INTENT_PRIMARY]);
        $navigation->addItem($item);

        $this->sut->decorate([$navigation]);

        $this->assertEquals(Primary::DROPDOWN_CLASS, $dropdown->getAttribute(Html5::ATTR_CLASS));
        $this->assertEquals(Primary::DROPDOWN_WRAPPER_CLASS, $wrapper->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecoratePrimaryNavigationRemovesBtnClasses()
    {
        $collection   = new Collection();
        $collection[] = new Button('A', [], [Html5::ATTR_CLASS => 'btn']);
        $collection[] = new Button('B', [], [Html5::ATTR_CLASS => 'btn btn-1']);
        $collection[] = new Button('C', [], [Html5::ATTR_CLASS => 'btn btn-2']);
        $button       = new Button('D', [], [Html5::ATTR_CLASS => 'btn btn-3']);

        $navigation = new Navigation([Navigation::INTENT_PRIMARY]);
        $navigation->addItem(new Item($collection));
        $navigation->addItem(new Item($button));

        $this->sut->decorate([$navigation]);

        $this->assertEquals('', $collection[0]->getAttribute(Html5::ATTR_CLASS));
        $this->assertEquals('btn-1', $collection[1]->getAttribute(Html5::ATTR_CLASS));
        $this->assertEquals('btn-2', $collection[2]->getAttribute(Html5::ATTR_CLASS));
        $this->assertEquals('btn-3', $button->getAttribute(Html5::ATTR_CLASS));
    }
}
