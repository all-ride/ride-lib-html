# Ride: HTML Library

HTML helper library of the PHP Ride framework.

## What's In This Library

### Element

The _Element_ interface is used to implement an HTML element.
It offers helper methods to set the attributes and a method to generate the HTML.

Different implementations of this interface are provided.

#### Anchor

The _Anchor_ class is a representation of an _a_ element as used anywhere in the body of an HTML document.

#### Image

The _Image_ class is a representation of an _img_ element as used anywhere in the body of an HTML document.

#### Meta

The _Meta_ class is a representation of a _meta_ element as used in the head of an HTML document.

#### Pagination

The _Pagination_ class is a helper to generate a pagination block.
It takes care of many pages by creating gaps between the first, the active and the last page like in the following example.

```
< 1 2 ... 45 __46__ 46 ... 88 89 >
```

#### Table

The _Table class is a representation of a _table_ element as used anywhere in the body of an HTML document.
There are extended implementations available depending on the use case or data source.

#### Row

The _Row_ class is a representation of a _tr_ element as used in a table of an HTML document.

#### Cell

The _Cell_ class is a representation of a _td_ element as used in a table row of an HTML document.

#### HeaderCell

The _HeaderCell_ class is a representation of a _th_ element as used in a table row of an HTML document.

#### ArrayTable

While the regular _Table_ class expects you to create _Row_ instances and so on, the _ArrayTable_ class works differently.
It's starting point in a simple array of data. 
Each element in the array is a _Row_. 
By adding a table _Decorator_, you create a column and decide the contents of the cell in that row by formatting the data or a part there of.

#### FormTable

The _FormTable_ class works further on the _ArrayTable_.
It creates a form component from the table and adds possibilities to add pagination, search, order and actions out of the box.
You can extend it even further.

#### ExportTable

The _ExportTable_ interface adds export functionality to the table which implements it.
You can add separate decorators for the export.
The export gets populated by passing a _ExportFormat_ implementation to it.

The _FormTable_ class implements this interface.

### HtmlParser

The _HtmlParser_ class helps you to process a piece of HTML.
You can use it to make all images and anchors absolute instead of relative.

## Code Sample

Check the following code sample to see some of the functionality of this library:

```php
<?php

use ride\library\form\Form;
use ride\library\html\table\decorator\StaticDecorator;
use ride\library\html\table\decorator\ValueDecorator;
use ride\library\html\table\FormTable;
use ride\library\html\Anchor;
use ride\library\html\HtmlParser;
use ride\library\html\Image;
use ride\library\html\Meta;
use ride\library\html\Pagination;

function exampleAnchor() {
    $anchor = new Anchor('ride/lib-html', 'https://github.com/all-ride/ride-lib-html');
    $anchor->setId('github-link');
    $anchor->setClass('btn');
    $anchor->addToClass('btn-primary');
    
    $html = $anchor->getHtml();
    // <a id="github-link" class="btn btn-primary" href="https://github.com/all-ride/ride-lib-html">ride/lib-html</a>
}

function exampleImage() {
    $image = new Image('https://url/to/image');
    $image->setAttribute('alt', 'Caption for the image');
    
    $html = $image->getHtml();
    // <img src="https://url-to-image" alt="Caption for the image" />
}

function exampleMeta() {
    $meta = new Meta();
    $meta->setName('viewport');
    $meta->setContent('width=device-width, initial-scale=1, shrink-to-fit=no');
    
    $html = $meta->getHtml();
    // <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        
    $meta = new Meta();
    $meta->setProperty('og:title');
    $meta->setContent('My Title');
    
    $html = $meta->getHtml();
    // <meta property="og:title" content="My Title" />
}

function examplePagination() {
    $pages = 3;
    $page = 2;
    
    $pagination = new Pagination($pages, $page);
    $pagination->setHref('http://url/to/?page=%page%');

    $previous = $pagination->getPreviousLink();
    // http://url/to/?page=1
    
    $next = $pagination->getNextLink();
    // http://url/to/?page=3
    
    $anchors = $pagination->getAnchors();
    // array all the anchor instances
    
    $html = $pagination->getHtml();
    // <div class="pagination">
    // <ul>
    //     <li><a href="http://url/to/?page=1">&laquo;</a></li>
    //     <li><a href="http://url/to/?page=1">1</a></li>
    //     <li class="active"><a href="http://url/to/?page=2">2</a></li>
    //     <li><a href="http://url/to/?page=2">3</a></li>
    //     <li><a href="http://url/to/?page=3">&raquo;</a></li>
    // </ul>
    // </div>
}

function exampleHtmlParser() {
    $html = '
<a href="some/action">Some text</a>
<a href="http://www.foo.bar">Foo</a>
<a href="#"><img src="img/icon.png"/></a>
';
    $baseUrl = 'http://url/to';
    
    $htmlParser = new HtmlParser($html);
    $htmlParser->setStripBody(true);
    $htmlParser->makeAnchorsAbsolute($baseUrl);
    $htmlParser->makeImagesAbsolute($baseUrl);
    
    $html = $htmlParser->getHtml();
    // <a href="http://url/to/some/action">Some text</a>
    // <a href="http://www.foo.bar">Foo</a>
    // <a href="#"><img src="http://url/to/img/icon.png"/></a>
}

function exampleFormTable(Form $form) {
    // some sample data, can be objects or anything
    $values = array(
        2 => array('name' => 'John', 'surname' => 'Doe', 'age' => 35),
        5 => array('name' => 'Jane', 'surname' => 'Doe', 'age' => 33),
        9 => array('name' => 'Neville', 'surname' => 'Brown', 'age' => 41),
    );
    
    $baseUrl = 'http://url/to/overview';
    
    // lets create the table
    $table = new FormTable($values);
    $table->setFormUrl($baseUrl);
    
    // add some decorators to create columns, heading decorators are optional
    $table->addDecorator(new ValueDecorator('name'), new StaticDecorator('Name'));
    $table->addDecorator(new ValueDecorator('surname'), new StaticDecorator('Surname'));
    $table->addDecorator(new ValueDecorator('age'), new StaticDecorator('Age'));
    
    // add order methods on the values
    $hasOrder = $table->hasOrderMethods();
    // false;
    
    // a simple ordering callback, one for ascending and one for descending
    $orderNameAscCallback = 'orderNameAsc';
    $orderNameDescCallback = 'orderNameDesc';
    
    $table->addOrderMethod('Name', $orderNameAscCallback, $orderNameDescCallback);
    
    // you can add extra arguments for your callbacks, check the function signatures further below
    $orderCustomAscCallback = 'orderCustomAsc';
    $orderCustomDescCallback = 'orderCustomDesc';
    
    $table->addOrderMethod('Custom', $orderCustomAscCallback, $orderCustomDescCallback, 'name', 'surname', 'age');
    
    $table->setOrderMethod('Name');
    $table->setOrderDirection('asc');

    $hasOrder = $table->hasOrderMethods();
    // false;

    // now add some pagination
    $hasPaginationOptions = $table->hasPaginationOptions();
    // false
    
    $table->setPaginationOptions(array(5, 10, 25, 50, 100, 250));
    $table->setPaginationUrl($baseUrl . '?page=%page%');
    $table->setRowsPerPage(10);
    $table->setPage(1);
    
    // searching values is to be implemented by extending the FormTable class and implementing the applySearch method
    $hasSearch = $table->hasSearch();
    // false;
    
    $table->setHasSearch(true);
    $table->setSearchQuery('doe');
    
    $searchQuery = $table->getSearchQuery();
    // 'doe'
    
    $hasSearch = $table->hasSearch();
    // true;
    
    // but it wont work unless applySearch is implemented
    
    // add some actions which can be applied on multiple items in the table
    $moveCallback = 'onMove';
    $deleteCallback = 'onDelete';
    
    $hasActions = $table->hasActions();
    // false
    
    $table->addAction('Move', $moveCallback);
    $table->addAction('Delete', $deleteCallback, 'Are you sure you want to delete the selected items?');
    
    $hasActions = $table->hasActions();
    // true
    
    $actionConfirmationMessages = $table->getActionConfirmationMessages);
    // array(
    //     'Delete' => 'Are you sure you want to delete the selected items?',
    // ) 
    
    // we have an unbuild form, add the table to it as a component
    $form->addRow('table', 'component', array(
        'component' => $table,
    ));
    $form = $form->build();
    
    $table->processForm();
    
    $numTotalRows = $table->countRows();
    $numDisplayRows = $table->countPageRows();
    $numPages = $table->getPages();
    $pagination = $table->getPagination();
    // ride\library\html\Pagination
    
    $html = $table->getHtml();
    // ... :-)
}

function orderNameAsc(array $values) {
    // order on name asc
}

function orderNameAsc(array $values) {
    // order on name desc
}

function orderNameDesc(array $values) {
    // custom ascending order with extra arguments
}

function orderCustomDesc(array $values, $name, $surname, $age) {
    // custom descending order with extra arguments
}

function onMove(array $ids) {
    // delete the selected ids
}

function onDelete(array $ids) {
    // delete the selected ids
}
```

## Related Modules

- [ride/lib-common](https://github.com/all-ride/ride-lib-common)
- [ride/lib-form](https://github.com/all-ride/ride-lib-form)
- [ride/lib-image](https://github.com/all-ride/ride-lib-image)
- [ride/lib-reflection](https://github.com/all-ride/ride-lib-reflection)
- [ride/web-base](https://github.com/all-ride/ride-web-base)
- [ride/web-orm](https://github.com/all-ride/ride-web-orm)

## Installation

You can use [Composer](http://getcomposer.org) to install this library.

```
composer require ride/lib-html
```

