# Extension to render lazy images in markdown

![Tests](https://github.com/simonvomeyser/commonmark-ext-lazy-image/workflows/Tests/badge.svg)

This adds support for lazy images to the [league/commonmark](https://github.com/thephpleague/commonmark) package.

## Install

``` bash
composer require simonvomeyser/commonmark-ext-lazy-image
```

## Example

``` php
use League\CommonMark\Environment;
use SimonVomEyser\CommonMarkExtension\LazyImageExtension;

$this->environment = Environment::createCommonMarkEnvironment();
$this->environment->addExtension(new LazyImageExtension());

$converter = new CommonMarkConverter([], $this->environment);
$html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');
```

This creates the following HTML

```html
<img src="/path/to/image.jpg" alt="alt text" loading="lazy" />
```

## Options

By default, only the `loading="lazy"` attribute is added, which should hopefully will be sufficient [in the future](https://web.dev/native-lazy-loading/).

You can use the follwoing options to integrate with various lazy loading libraries.

Here is an example how to use this package with the [lozad library](https://github.com/ApoorvSaxena/lozad.js):

```php
//...

$converter = new CommonMarkConverter([
  'lazy_image' => [
      'strip_src' => true, // remove the "src" to add it later via js, optional
      'html_class' => 'lozad', // the class that should be added, optional
      'data_attribute' => 'src', // how the data attribute is named that provides the source to get picked up by js, optional
  ]
], $environment)
$html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');
```


This creates the following HTML

```html
<img src="" alt="alt text" loading="lazy" data-src="/path/to/image.jpg" class="lozad" />
```
