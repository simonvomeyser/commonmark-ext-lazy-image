<?php

namespace SimonVomEyser\CommonMarkExtension;

use League\Config\ConfigurationInterface;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\ImageRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class LazyImageRenderer implements NodeRendererInterface
{
    /** @var ConfigurationInterface */
    protected ConfigurationInterface $config;

    /** @var ImageRenderer */
    protected ImageRenderer $baseImageRenderer;

    /**
     * @param ConfigurationInterface $config
     * @param ImageRenderer $baseImageRenderer
     */
    public function __construct(ConfigurationInterface $config, ImageRenderer $baseImageRenderer)
    {
        $this->config = $config;
        $this->baseImageRenderer = $baseImageRenderer;
    }

    /**
     * @param Node $node
     * @param ChildNodeRendererInterface $childRenderer
     *
     * @return HtmlElement
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        $stripSrc = $this->config->get('lazy_image/strip_src');
        $dataAttribute = $this->config->get('lazy_image/data_attribute');
        $htmlClass = $this->config->get('lazy_image/html_class');

        $this->baseImageRenderer->setConfiguration($this->config);
        $htmlElement = $this->baseImageRenderer->render($node, $childRenderer);

        $htmlElement->setAttribute('loading', 'lazy');

        if ($dataAttribute) {
            $htmlElement->setAttribute("data-$dataAttribute", $htmlElement->getAttribute('src'));
        }

        if ($htmlClass) {
            // append the class to existing classes
            $attr = $htmlElement->getAttribute('class');
            if (!empty($attr))
            {
                $attr .= " ";
            }
            $attr .= $htmlClass;
            $htmlElement->setAttribute('class', $attr);
        }

        if ($stripSrc) {
            $htmlElement->setAttribute('src', '');
        }

        return $htmlElement;
    }
}
