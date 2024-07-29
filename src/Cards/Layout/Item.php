<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;

/**
* Item
*
* @version v1
* @since v1
*/

class Item {

    use BasicAttributesTrait;

    protected $id;
    protected $label;
    protected $value;

    public function __construct($id, $label ='', $value = '')
    {
        $this->setID($id);
        $this->label = $label;
        $this->value = $value;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLabel($label)
    {
        if (!is_string($label)) {
            throw new \InvalidArgumentException('Item Label must be a string');
        }
        $this->label = $label;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}