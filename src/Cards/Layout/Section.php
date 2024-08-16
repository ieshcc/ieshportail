<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Layout\Item;
use Gibbon\Cards\Traits\ComponentMetadataTrait;


/**
* Section
*
* @version v1
* @since v1
*/

class Section {

    use BasicAttributesTrait;
    use ComponentMetadataTrait;

    protected $id;
    protected $title;

    protected $items = array();

    public function __construct($id, $title)
    {
        $this->setID($id);
        $this->title = $title;
    }

    public function getMetaDatas()
    {
        if (!isset($this->meta['classes'])) {
            $this->meta['classes'] = [
                'sectionHeader' => '',
                'sectionTitle' => ''
            ];
        }

        if (!isset($this->meta['styles'])) {
            $this->meta['styles'] = [
                'sectionHeader' => '',
                'sectionTitle' => ''
            ];
        }

        return $this->meta;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

}