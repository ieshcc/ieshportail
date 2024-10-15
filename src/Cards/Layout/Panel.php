<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Layout\Section;
use Gibbon\Cards\Traits\ComponentMetadataTrait;
use Gibbon\Tables\Action;

/**
* Panel
*
* @version v1
* @since v1
*/

class Panel
{
    use BasicAttributesTrait;
    use ComponentMetadataTrait;
     

    protected $id;
    protected $title;
    protected $description;
    
    protected $panels = array();
    protected $sections = array();
    protected $panelActions = array();

    public function __construct($id, $title = '', $description = '')
    {
        $this->setID($id);
        $this->title = $title;
        $this->description = $description;
    }

    public function addPanel($id, $title = ''){
        $this->panels[$id] = new Panel($id, $title);

        return $this;
    }

    public function getPanels(){
        return $this->panels;
    }

    public function getPanel($id){
        return $this->panels[$id];
    }

    public function addSection($id, $title = ''){
        $this->sections[$id] = new Section($id, $title);

        return $this;
    }

    public function getSections(){
        return $this->sections;
    }

    public function getSection($id){
        return $this->sections[$id];
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getMetaDatas()
    {
        if (!isset($this->meta['classes'])) {
            $this->meta['classes'] = [
                'panelHeader' => '',
                'panelTitle' => '',
                'panelDescription' => '' 
            ];
        }

        if (!isset($this->meta['styles'])) {
            $this->meta['styles'] = [
                'panelHeader' => '',
                'panelTitle' => '',
                'panelDescription' => '' 
            ];
        }

        return $this->meta;
    }

    /**
     * Add an action to the panel, generally displayed at the right-side of the panel.
     *
     * @param string $name
     * @param string $label
     * @return Action
     */
    public function addPanelAction($name, $label = '')
    {
        $this->panelActions[$name] = new Action($name, $label);

        return $this->panelActions[$name];
    }

    /**
     * Get all panel actions.
     *
     * @return array
     */
    public function getPanelActions()
    {
        return $this->panelActions;
    }

    public function getPanelAction($name)
    {
        return isset($this->panelActions[$name]) ? $this->panelActions[$name] : null;
    }

    public function setPanelActions($panelActions)
    {
        $this->panelActions = $panelActions;

        return $this;
    }


}
