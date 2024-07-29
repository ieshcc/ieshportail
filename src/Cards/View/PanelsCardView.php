<?php

namespace Gibbon\Cards\View;

use Gibbon\View\View;
use Gibbon\Domain\DataSet;
use Gibbon\Cards\Renderer\RendererInterface;
use Gibbon\Cards\Card;
use Gibbon\Cards\Layout\Panel;

class PanelsCardView extends View implements RendererInterface{
    public function renderCard(Card $card, DataSet $dataSet) {
        $dataSet->htmlEncode($card->getMetaData('allowHTML', []));

        $this->addData('card', $card);
        
        if($dataSet->count() > 0) {
            $data = $dataSet->toArray();
            $this->addData([
                'panels'       => $card->getPanels(),
                'blankSlate' => __('There are no data to display.'),
            ]);
        }
    
        return $this->render('components/panelsCard.twig.html');

    }

}

