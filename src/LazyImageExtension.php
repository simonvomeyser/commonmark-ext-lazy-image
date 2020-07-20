<?php

namespace SimonVomEyser\CommonMarkExtension;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Renderer\ImageRenderer;

class LazyImageExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addInlineRenderer('League\CommonMark\Inline\Element\Image', new LazyImageRenderer(new ImageRenderer()), 10);
    }
}
