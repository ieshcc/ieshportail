<?php

namespace Gibbon\Cards\Renderer;

use Gibbon\Domain\DataSet;
use Gibbon\Cards\Card;

interface RendererInterface
{
    public function renderCard(Card $card, DataSet $dataSet);
}