<?php

namespace Gibbon\Cards\Traits;

/**
 * Basic HTML Attributes (id, class)
 * 
 * @version v1
 * @since   v1
 */

 trait ComponentMetadataTrait
 {

    protected $meta = array();
    
    /**
     * Add a piece of meta data to the table. Can be used for renderer-specific details.
     *
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function addMetaData($name, $value)
    {
        if (isset($this->meta[$name]) && is_array($this->meta[$name]) && is_array($value)) {
            $this->meta[$name] = array_replace_recursive($this->meta[$name], $value);
        } else {
            $this->meta[$name] = $value;
        }
    
        return $this;
    }

    public function getMetaData($name, $defaultValue = null)
    {
        return isset($this->meta[$name]) ? $this->meta[$name] : $defaultValue;
    }
 }