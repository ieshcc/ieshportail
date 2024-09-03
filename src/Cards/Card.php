<?php

namespace Gibbon\Cards;

use Gibbon\Domain\DataSet;
use Gibbon\Domain\QueryCriteria;
use Gibbon\Forms\OutputableInterface;
use Gibbon\Tables\Action;
use Gibbon\Cards\Renderer\RendererInterface;
use Gibbon\Cards\View\PanelsCardView;
use Gibbon\Cards\Layout\Panel;
use Gibbon\View\View;
use Gibbon\Cards\Traits\ComponentMetadataTrait;

/**
 * Card
 * 
 * @version v1
 * @since v1
 */
class Card implements OutputableInterface {

    use ComponentMetadataTrait;
    
    protected $id;
    protected $title;
    protected $description;
    protected $data;
    protected $renderer;

    protected $panels = array();
    // protected $meta = array(); 

    /**
     * 
     * Create a card with optional renderer
     * 
     * @param string $id
     * @param RendererInterface $renderer
     */

    public function __construct(RendererInterface $renderer = null){
        $this->renderer = $renderer;
    }

    public static function createPanelsCard($id, RendererInterface $renderer = null){
        global $container;

        $renderer = !empty($renderer) ? $renderer : $container->get(PanelsCardView::class);

        return (new static($renderer))->setId($id);
    }

    /**
     * Render the card, either with the supplied renderer or default to the built-in one.
     *
     * @param DataSet|array $dataSet
     * @param RendererInterface $renderer
     * @return string
     */
    public function render($dataSet, RendererInterface $renderer = null)
    {
        $renderer = isset($renderer)? $renderer : $this->renderer;
        $this->withData($dataSet);

        return $renderer->renderCard($this, $this->data);
    }

    /**
     * Set the card data internally.
     *
     * @param DataSet|array $data
     * @return self
     */
    public function withData($dataSet)
    {
        $dataSet = is_array($dataSet) ? new DataSet($dataSet) : $dataSet;

        if (!empty($this->data)) {
            $this->data->merge($dataSet);
        } else {
            $this->data = $dataSet;
        }

        return $this;
    }

    public function getPanel($id){
        return $this->panels[$id];
    }

    /**
     * Add a panel to the card, by name and optional title and description. Returns the created panel.
     *
     * @param string $name
     * @param string $title
     * @param string $description
     * @return Panel
     */
    public function addPanel($id, $title = '', $description = '')
    {
        $this->panels[$id] = new Panel($id, $title, $description);

        return $this->panels[$id];
    }

    /**
     * Remove a panel by id.
     *
     * @param string $id
     * @return self
     */
    public function removePanel($id)
    {
        if (isset($this->panels[$id])) {
            unset($this->panels[$id]);
        }

        return $this;
    }

    public function getMetaDatas()
    {
        if (!isset($this->meta['classes'])) {
            $this->meta['classes'] = [
                'cardHeader' => '',
                'cardTitle' => '',
                'cardDescription' => '' 
            ];
        }

        if (!isset($this->meta['styles'])) {
            $this->meta['styles'] = [
                'cardHeader' => '',
                'cardTitle' => '',
                'cardDescription' => '' 
            ];
        }

        return $this->meta;
    }

    /**
     * Set the renderer for the card. Can also be supplied ad hoc in the render method.
     *
     * @param RendererInterface $renderer
     * @return self
     */
    public function setRenderer(RendererInterface $renderer){
        $this->renderer = $renderer;
        return $this;
    }
    
    /**
     * Get the current card renderer.
     *
     * @return RendererInterface
     */
    public function getRenderer(){
        return $this->renderer;
    }
    
    public function getOutput(){
        return $this->renderCard($this, $this->data);
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getId(){
        return $this->id;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function getTitle(){
        return $this->title;
    }

    /**
     * Set the table description. Can be a string or a callable that returns a string.
     * @param  string|Callable  $description
     */
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public function getDescription(){
        return is_callable($this->description)
            ? call_user_func($this->description)
            : $this->description;
    }

    public function setData(DataSet $data){
        $this->data = $data;
        return $this;
    }

    public function getData(){
        return $this->data;
    }

    public function setMeta(array $meta){
        $this->meta = $meta;
        return $this;
    }

    public function getMeta(){
        return $this->meta;
    }

    public function getPanels(){
        return $this->panels;
    }

} 
