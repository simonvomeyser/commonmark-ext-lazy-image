<?php

namespace SimonVomEyser\CommonMarkExtension;

use League\CommonMark\ConfigurableEnvironmentInterface;
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

    /** @var ConfigurableEnvironmentInterface */
    private $environment;

    /**
     * @param ConfigurableEnvironmentInterface $environment
     * @param ImageRenderer $baseImageRenderer
     */
    public function __construct(ConfigurableEnvironmentInterface $environment, ImageRenderer $baseImageRenderer)
    {
        $this->baseImageRenderer = $baseImageRenderer;
        $this->environment = $environment;
    }

    /**
     * @param AbstractInline $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {

        $stripSrc = $this->environment->getConfig('lazy_image/strip_src', false);
        $dataAttribute = $this->environment->getConfig('lazy_image/data_attribute', '');
        $htmlClass = $this->environment->getConfig('lazy_image/html_class', '');

        $this->baseImageRenderer->setConfiguration($this->config);
        $baseImage = $this->baseImageRenderer->render($inline, $htmlRenderer);

        $baseImage->setAttribute('loading', 'lazy');

        if ($dataAttribute) {
            $baseImage->setAttribute("data-$dataAttribute", $baseImage->getAttribute('src'));
        }

        if ($htmlClass) {
            $baseImage->setAttribute('class', $htmlClass);
        }

        if ($stripSrc) {
            $baseImage->setAttribute('src', '');
        }

        return $baseImage;
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }
}
