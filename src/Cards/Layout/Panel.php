<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Layout\Section;

/**
* Panel
*
* @version v1
* @since v1
*/

class Panel
{
    use BasicAttributesTrait; 

    protected $id;
    protected $title;
    protected $description;
    
    protected $panels = array();
    protected $sections = array();

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


     /**
     * Renders the panel by either passing the section $data to a formatter, 
     * or grabbing the section data by key based on the panel name.
     *
     * @param array $data
     * @return string
     */
    // public function getOutput(&$data = [], $joinDetails = true)
    // {
    //     $details = $joinDetails && $this->hasDetailsFormatter() ? '<br/>'.$this->getDetailsOutput($data) : '';
        
    //     if ($this->hasFormatter()) {
    //         return call_user_func($this->formatter, $data).$details;
    //     } else {
    //         $content = isset($data[$this->getID()])? $data[$this->getID()] : '';
    //         $content = is_array($content) ? implode(',', array_keys($content)) : $content;
    //         $content .= $details;
            
    //         return $this->getTranslatable() ? __($content) : $content;
    //     }
    // }

    // public function addSection(Section $section)
    // {
    //     $this->sections[] = $section;

    //     return $this;
    // }

    // public function getSections()
    // {
    //     return $this->sections;
    // }

}
