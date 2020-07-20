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

        $this->assertCount(2, $this->getImageRenderers($environment));
    }

    public function testThatOnlyTheLazyAttributeIsAddedInDefaultConfig()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new LazyImageExtension());

        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="/path/to/image.jpg" alt="alt text" loading="lazy" />', $html);
    }

    public function testThatTheSrcCanBeStripped()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new LazyImageExtension());

        $converter = new CommonMarkConverter([
            'lazy_image' => ['strip_src' => true]
        ], $environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="" alt="alt text" loading="lazy" />', $html);
    }

    public function testThatTheDataSrcBeDefined()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new LazyImageExtension());

        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'lazy_image' => ['data_attribute' => 'src']
        ], $environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('data-src="/path/to/image.jpg"', $html);
    }

    public function testThatTheClassCanBeAdded()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new LazyImageExtension());

        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'lazy_image' => ['html_class' => 'lazy-loading-class']
        ], $environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('class="lazy-loading-class"', $html);
    }


    /**
     * @param ConfigurableEnvironmentInterface $environment
     * @return array
     */
    private function getImageRenderers(ConfigurableEnvironmentInterface $environment) {
        return iterator_to_array($environment->getInlineRenderersForClass('League\CommonMark\Inline\Element\Image'));
    }
}