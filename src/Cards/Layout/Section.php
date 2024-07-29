<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Layout\Item;

/**
* Section
*
* @version v1
* @since v1
*/

class Section {

    use BasicAttributesTrait;

    protected $id;
    protected $title;

    protected $items = array();

    public function __construct($id, $title)
    {
        $this->setID($id);
        $this->title = $title;
    }

    public function getSections(){
        return $this->sections;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function addItems($id, $label = '', $value = '')
    {
        $this->items[$id] = new Item($id, $label, $value);
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    // Add your properties and methods here
}