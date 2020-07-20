<?php

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Environment;
use League\CommonMark\EnvironmentAwareInterface as EnvironmentAwareInterfaceAlias;
use PHPUnit\Framework\TestCase;
use SimonVomEyser\CommonMarkExtension\LazyImageExtension;

/**
 * Class LazyImageExtensionTest
 */
class LazyImageExtensionTest extends TestCase {

    public function testThatTheRendererIsAdded()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new LazyImageExtension());

        $this->assertCount(2, $this->getImageRenderes($environment));
    }

    public function testThatOnlyTheLazyAttributeIsAddedInDefaultConfig()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new LazyImageExtension());

        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="/path/to/image.jpg" alt="alt text" loading="lazy" />', $html);
    }

    /**
     * @param ConfigurableEnvironmentInterface $environment
     * @return array
     */
    private function getImageRenderes(ConfigurableEnvironmentInterface $environment) {
        return iterator_to_array($environment->getInlineRenderersForClass('League\CommonMark\Inline\Element\Image'));
    }
}