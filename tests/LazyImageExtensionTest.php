<?php

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Environment;
use PHPUnit\Framework\TestCase;
use SimonVomEyser\CommonMarkExtension\LazyImageExtension;

/**
 * Class LazyImageExtensionTest
 */
class LazyImageExtensionTest extends TestCase
{

    protected $environment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->environment = Environment::createCommonMarkEnvironment();
        $this->environment->addExtension(new LazyImageExtension());
    }

    public function testThatTheRendererIsAdded()
    {
        $this->assertCount(2, $this->getImageRenderers($this->environment));
    }

    public function testThatOnlyTheLazyAttributeIsAddedInDefaultConfig()
    {
        $converter = new CommonMarkConverter([], $this->environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="/path/to/image.jpg" alt="alt text" loading="lazy" />', $html);
    }

    public function testThatTheSrcCanBeStripped()
    {
        $converter = new CommonMarkConverter([
            'lazy_image' => ['strip_src' => true]
        ], $this->environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="" alt="alt text" loading="lazy" />', $html);
    }

    public function testThatTheDataSrcBeDefined()
    {

        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'lazy_image' => ['data_attribute' => 'src']
        ], $this->environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('data-src="/path/to/image.jpg"', $html);
    }

    public function testThatTheClassCanBeAdded()
    {

        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'lazy_image' => ['html_class' => 'lazy-loading-class']
        ], $this->environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('class="lazy-loading-class"', $html);
    }

    public function testLozadLibraryConfigurationAsExample()
    {

        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'lazy_image' => [
                'strip_src' => true,
                'html_class' => 'lozad',
                'data_attribute' => 'src',
            ]
        ], $this->environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('src="" alt="alt text" loading="lazy" data-src="/path/to/image.jpg" class="lozad"', $html);
    }

    /**
     * @param ConfigurableEnvironmentInterface $environment
     * @return array
     */
    private function getImageRenderers(ConfigurableEnvironmentInterface $environment)
    {
        return iterator_to_array($environment->getInlineRenderersForClass('League\CommonMark\Inline\Element\Image'));
    }
}