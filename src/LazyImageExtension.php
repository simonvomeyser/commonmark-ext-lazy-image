<?php

namespace SimonVomEyser\CommonMarkExtension;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Renderer\ImageRenderer;

class LazyImageExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $lazyImageRenderer = new LazyImageRenderer(new ImageRenderer(), $environment);
        $environment->addInlineRenderer('League\CommonMark\Inline\Element\Image', $lazyImageRenderer, 10);
    }
}
