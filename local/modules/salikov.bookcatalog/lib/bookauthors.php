<?php
namespace Salikov\Bookcatalog;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\ORM\Query\Join,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

class BookAuthorTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'test_book_author';
    }

    public static function getConnectionName()
    {
        return 'default';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            (new IntegerField('BOOKS_ID'))
                ->configurePrimary(true),
            (new Reference('BOOKS', BooksTable::class,
                Join::on('this.BOOKS_ID', 'ref.ID')))
                ->configureJoinType('inner'),
            (new IntegerField('AUTHORS_ID'))
                ->configurePrimary(true),
            (new Reference('AUTHORS', AuthorsTable::class,
                Join::on('this.AUTHORS_ID', 'ref.ID')))
                ->configureJoinType('inner'),
        ];
    }

}