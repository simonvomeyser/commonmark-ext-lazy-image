<?php

namespace SimonVomEyser\CommonMarkExtension;

use Faker\Provider\Image;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Renderer\ImageRenderer;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Util\ConfigurationInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class LazyImageRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /** @var ConfigurationInterface */
    protected $config;

    /** @var ImageRenderer */
    protected $baseImageRenderer;

    /**
     * @param ImageRenderer $baseImageRenderer
     */
    public function __construct(ImageRenderer $baseImageRenderer)
    {
        $this->baseImageRenderer = $baseImageRenderer;
    }

    /**
     * @param AbstractInline $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        $this->baseImageRenderer->setConfiguration($this->config);
        $baseImage = $this->baseImageRenderer->render($inline, $htmlRenderer);

        $baseImage->setAttribute('loading', 'lazy');
        $baseImage->setAttribute('data-src', $baseImage->getAttribute('src'));
        $baseImage->setAttribute('class', 'lozad');
        $baseImage->setAttribute('src', '');

        return $baseImage;
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }
}
