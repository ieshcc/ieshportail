<?php

namespace Gibbon\Cards\Layout;

use Gibbon\Forms\Traits\BasicAttributesTrait;
use Gibbon\Cards\Traits\ComponentMetadataTrait;
use Gibbon\Tables\Action;


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
    protected $formatter;
    protected $detailsFormatter;
    protected $translatable = false;
    protected $itemActions = array();

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

    /**
     * Renders the card item by either passing the item $data to a formatter, 
     * or grabbing the item data by key based on the item label.
     *
     * @param array $data
     * @return string
     */
    public function getOutput(&$data = [], $joinDetails = true)
    {
        $details = $joinDetails && $this->hasDetailsFormatter() ? '<br/>'.$this->getDetailsOutput($data) : '';
        
        if ($this->hasFormatter()) {
            return call_user_func($this->formatter, $data).$details;
        } else {
            $content = isset($data[$this->getID()])? $data[$this->getID()] : '';
            $content = is_array($content) ? implode(',', array_keys($content)) : $content;
            $content .= $details;
            
            return $this->getTranslatable() ? __($content) : $content;
        }
    }

    /**
     * Does the item have a valid formatter?
     *
     * @return bool
     */
    public function hasFormatter() 
    {
        return !empty($this->formatter) && is_callable($this->formatter);
    }

    /**
     * Does the item have a valid formatter?
     *
     * @return bool
     */
    public function hasDetailsFormatter() 
    {
        return !empty($this->detailsFormatter) && is_callable($this->detailsFormatter);
    }

    public function getDetailsOutput(&$data = [])
    {
        return $this->hasDetailsFormatter() ? call_user_func($this->detailsFormatter, $data) : '';
    }

    /**
     * Add an action to the item, generally displayed at the right-side of the item.
     *
     * @param string $name
     * @param string $label
     * @return Action
     */
    public function addItemAction($name, $label = '')
    {
        $this->itemActions[$name] = new Action($name, $label);

        return $this->itemActions[$name];
    }

    /**
     * Get all item actions.
     *
     * @return array
     */
    public function getItemActions()
    {
        return $this->itemActions;
    }

    public function getItemAction($name)
    {
        return $this->itemActions[$name];
    }

    public function setItemActions($itemActions)
    {
        $this->itemActions = $itemActions;

        return $this;
    }

    /**
     * Sets that this column of table must be translated
     *
     * @return self
     */
    public function translatable() 
    {
        $this->translatable = true;
        
        return $this;
    }

    /**
     * Gets if the item must be translated or not
     *
     * @return bool
     */
    public function getTranslatable()
    {
        return $this->translatable;
    } 
    
    /**
     * Sets the formatter as a callable, which should accept a $data param of item data.
     *
     * @param callable $formatter
     * @return self
     */
    public function format(callable $formatter) 
    {
        $this->formatter = $formatter;

        return $this;
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