<?php
Bitrix\Main\Loader::registerAutoLoadClasses(null, array(
    '\Salikov\Bookcatalog\BooksTable' => '/local/modules/salikov.bookcatalog/lib/book.php',
    '\Salikov\Bookcatalog\AuthorsTable' => '/local/modules/salikov.bookcatalog/lib/authors.php',
    '\Salikov\Bookcatalog\PublisherTable' => '/local/modules/salikov.bookcatalog/lib/publisher.php',
    '\Salikov\Bookcatalog\BookAuthorTable' => '/local/modules/salikov.bookcatalog/lib/bookauthors.php',
));