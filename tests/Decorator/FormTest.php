<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\IElement;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component;
use PHPUnit\Framework\TestCase;
use AbterPhp\Framework\Html\Component\Button;

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
    protected $sut;

    public function setUp()
    {
        $this->sut = (new Form())->init();

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

    public function testDecorateLabels()
    {
        /** @var Label[] $nodes */
        $nodes = [new Label('a', 'A'), new Label('b', 'B')];

        $this->sut->decorate($nodes);

        $this->assertContains(Form::DEFAULT_LABEL_CLASS, $nodes[0]->getAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Form::DEFAULT_LABEL_CLASS, $nodes[1]->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecorateInputs()
    {
        /** @var IElement[] $nodes */
        $nodes = [new Input('a', 'A'), new Textarea('b', 'B'), new Select('c', 'C')];

        $this->sut->decorate($nodes);

        $this->assertContains(Form::DEFAULT_INPUT_CLASS, $nodes[0]->getAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Form::DEFAULT_INPUT_CLASS, $nodes[1]->getAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Form::DEFAULT_INPUT_CLASS, $nodes[2]->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecorateFormGroups()
    {
        $input = new Input('a', 'A');
        $label = new Label('a', 'A');

        /** @var Textarea[] $nodes */
        $nodes = [new FormGroup($input, $label), new FormGroup($input, $label)];

        $this->sut->decorate($nodes);

        $this->assertContains(Form::DEFAULT_FORM_GROUP_CLASS, $nodes[0]->getAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Form::DEFAULT_FORM_GROUP_CLASS, $nodes[1]->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecorateButtons()
    {
        /** @var Button[] $nodes */
        $nodes = [new Button('a', [Button::INTENT_FORM]), new Button('b', [Button::INTENT_FORM])];

        $this->sut->decorate($nodes);

        $this->assertContains(Form::DEFAULT_BUTTON_CLASS, $nodes[0]->getAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Form::DEFAULT_BUTTON_CLASS, $nodes[1]->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecorateCheckboxGroups()
    {
        $input = new Input('a', 'A');
        $label = new Label('a', 'A');

        /** @var CheckboxGroup[] $nodes */
        $nodes = [new CheckboxGroup($input, $label), new CheckboxGroup($input, $label)];

        $this->sut->decorate($nodes);

        $this->assertContains(Form::CHECKBOX_GROUP_CLASS, $nodes[0]->getAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Form::CHECKBOX_GROUP_CLASS, $nodes[1]->getAttribute(Html5::ATTR_CLASS));
    }
}
