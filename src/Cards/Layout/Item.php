<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Traits\ComponentMetadataTrait;


/**
* Item
*
* @version v1
* @since v1
*/

class Item {

    use BasicAttributesTrait;
    use ComponentMetadataTrait;

    protected $id;
    protected $label;
    protected $value;

    public function __construct($id, $label ='', $value = '')
    {
        $this->setID($id);
        $this->label = $label;
        $this->value = $value;
    }

    public function getMetaDatas()
    {
        if (!isset($this->meta['classes'])) {
            $this->meta['classes'] = [
                'itemHeader' => '',
                'itemTitle' => '',
                'itemDescription' => '' 
            ];
        }

        if (!isset($this->meta['styles'])) {
            $this->meta['styles'] = [
                'itemHeader' => '',
                'itemTitle' => '',
                'itemDescription' => '' 
            ];
        }

        return $this->meta;
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