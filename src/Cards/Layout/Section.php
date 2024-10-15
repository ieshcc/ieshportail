<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Layout\Item;
use Gibbon\Cards\Traits\ComponentMetadataTrait;
use Gibbon\Tables\Action;

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
    protected $sectionActions = array();

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

    public function getItem($id)
    {
        return $this->items[$id];
    }

    public function addItem($id, $label = '', $value = '')
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

    /**
     * Add an action to the section, generally displayed at the right-side of the section.
     *
     * @param string $name
     * @param string $label
     * @return Action
     */
    public function addSectionAction($name, $label = '')
    {
        $this->sectionActions[$name] = new Action($name, $label);

        return $this->sectionActions[$name];
    }

    /**
     * Get all section actions.
     *
     * @return array
     */
    public function getSectionActions()
    {
        return $this->sectionActions;
    }

    public function getSectionAction($name)
    {
        return isset($this->sectionActions[$name]) ? $this->sectionActions[$name] : null;
    }

    public function setSectionActions($sectionActions)
    {
        $this->sectionActions = $sectionActions;

        return $this;
    }


}