<!--
id: readme
tags: ''
-->

# {{ book.title }}

![](https://badgen.net/packagist/name/aklump/dom-testing-selectors) ![](https://badgen.net/packagist/php/aklump/dom-testing-selectors) ![](https://badgen.net/github/license/aklump/dom-testing-selectors)

![hero](../../images/testing_selectors.jpg)

**This library offers a PHP solution designed to add specific markup to your DOM for testing purposes.** By default, it configures a `data-test` attribute for DOM elements, as shown in the examples. The library is intended to be integrated into a server-side rendering pipeline, which generates your markup.

```html

<html>
<body>
<main>
  <section data-test="hero"></section>
  <article data-test="article"></article>
</main>
</body>
</html>
```

This attribute should be used exclusively for targeting elements in your tests. Separating concerns in this way helps prevent test fragility that can arise from relying on classes or other multipurpose attributes that you may not control and could change unexpectedly. By using a dedicated data attribute for testing—one that you control—you can ensure that your tests remain stable and reliable over time.

```js
describe('The main page', () => {
  it('should have hero and article sections.', () => {
    cy.visit('/')
    cy.get('[data-test="hero"]').its('length').should('equal', 1)
    cy.get('[data-test="article"]').its('length').should('equal', 1)
  })
})
```

{{ composer.install|raw }}

## Selectors

```php
$test_selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();

$username_selector = $test_selector('username');
// $username_selector === 'data-test="username"'

$password_selector = $test_selector('password');
// $password_selector === 'data-test="password"'
```

You may also add a group to the selectors, which will prefix the attribute value:

```php
$test_selector->setGroup('login');

$username_selector = $test_selector('username');
// $username_selector === 'data-test="login__username"'
```

#### Naming Convention

Be aware that in an effort to reduce confusion and errors, the provided `\AKlump\DomTestingSelectors\Selector\AbstractSelector` has an opinion about naming convention.

```php
$selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
$attribute = $selector('A.StrangeSelector string---NAME');
// $attribute === 'data-test="a_strange_selector_string_name"
```

To change this behavior you should create a custom selector class, overriding `\AKlump\DomTestingSelectors\Selector\AbstractSelector::applyNamingConventions`.

#### Using a Custom Attribute

This example will illustrate how to change the attribute to `data-cy`, which you may want to use while [testing with Cypress](https://www.cypress.io/). Simple create a custom selector class and use in place of `DataTestSelector`.

```php
namespace Vendor\DomTestingSelectors\Selectors;

final class CypressSelector extends AbstractSelector {

  public function getAttributeName(): string {
    return 'data-cy';
  }
}
```

To learn more about selectors, refer to `\AKlump\DomTestingSelectors\Selectors\AbstractSelector`.

#### Using Classes as Testing Selectors

The included `\AKlump\DomTestingSelectors\Selector\ClassSelector` can be used instead of `\AKlump\DomTestingSelectors\Selector\DataTestSelector` if you want to use CSS classes (e.g. `t-foo`) for selecting your elements.  **Notice the naming convention is altered for classes, using hyphens instead of underscores.**

```php
// When using the class attribute--e.g., "<div class="foo bar"/>"--you must
// merge with any existing value.  The current value has to be passed as the
// second argument to __invoke() and __getAttributeValue.
$selector = new \AKlump\DomTestingSelectors\Selector\ClassSelector();
$attribute_markup = $selector('my_target_element', 'foo bar');
// $attribute_markup === 'class="foo bar t-my-target-element"'

$selector = new \AKlump\DomTestingSelectors\Selector\ClassSelector();
$attribute_value = $selector->setName('my_target_element')
  ->getAttributeValue('foo bar');
// $attribute_value === 'foo bar t-my-target-element'
```

## Handlers

Handlers do the work of adding the selector markup to your HTML. The `\AKlump\DomTestingSelectors\Handlers\StringHandler` is provided by this library, to add the selector to HTML strings.

```php
$element = '<div></div>';
$handler = new \AKlump\DomTestingSelectors\Handler\StringHandler();
$selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
if ($handler->canHandle($element)) {
  $handler->addTestingSelectorToElement($element, $selector->setName('foobar'));
}
// $element === '<div data-test="foobar"></div>'
```

Framework-specific and custom handlers are very easy to add by implementing `\AKlump\DomTestingSelectors\Handler\HandlerInterface`.

```php
class MyArrayHandler implements \AKlump\DomTestingSelectors\Handler\HandlerInterface {
  public function canHandle($element): bool {
    return is_array($element);
  }

  public function addTestingSelectorToElement(&$element, \AKlump\DomTestingSelectors\Selector\ElementSelectorInterface $selector): void {
    $element['attributes'][$selector->getAttributeName()] = $selector->getAttributeValue();
  }
}
```

## Factories

In practice you may have multiple handlers to cover the full range of elements you wish to markup. This is the reason for `\AKlump\DomTestingSelectors\AbstractHandlerFactory`. The following example shows how you would write and use a custom factory.

```php
class MyFactory extends \AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory {
  public function __construct() {
    $this->addHandler(new \AKlump\DomTestingSelectors\Handler\StringHandler());
    $this->addHandler(new MyArrayHandler());
  }
}

$factory = new MyFactory();
$selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
$selector->setName('foobar');

$element1 = '<div></div>';
$element2 = ['tag' => 'div'];

try {
  $factory->getHandler($element1)->addTestingSelectorToElement($element1, $selector);
  // $element1 === '<div data-test="foobar"></div>'

  $factory->getHandler($element2)->addTestingSelectorToElement($element2, $selector);
  // $element2 === ['tag'=>'div','attributes'=>['data-test'=>'foobar']]
}
catch (\AKlump\DomTestingSelectors\Exception\NoHandlerFoundException $exception) {
  // No handler found
}
```

### Make it a Safe Factory

Notice that you have to catch if a handler cannot be found for a given element. To get around this you may want to create a "safe factory", that is one which adds **as it's final handler** the `\AKlump\DomTestingSelectors\Handler\PassThroughHandler` or something similar (maybe with your own logging). Instead of an exception, the $element will simply pass through unchanged.

```php
class MySafeFactory extends \AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory {

  public function __construct() {
    $this->addHandler(new \AKlump\DomTestingSelectors\Handler\StringHandler());
    $this->addHandler(new \AKlump\DomTestingSelectors\Handler\PassThroughHandler());
  }
}

$factory = new MySafeFactory();
$selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
$selector->setName('foobar');

$element1 = '<div></div>';
$element2 = 'lorem ipsum dolar';

$factory->getHandler($element1)->addTestingSelectorToElement($element1, $selector);
// $element1 === '<div data-test="foobar"></div>'

$factory->getHandler($element2)->addTestingSelectorToElement($element2, $selector);
// $element2 === 'lorem ipsum dolar'
```
