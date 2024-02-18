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

class BooksTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'test_books';
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
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('BOOKS_ENTITY_ID_FIELD')
                ]
            ),
            new StringField(
                'TITLE',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateTitle'],
                    'title' => Loc::getMessage('BOOKS_ENTITY_TITLE_FIELD')
                ]
            ),
            new IntegerField(
                'YEAR',
                [
                    'required' => true,
                    'title' => Loc::getMessage('BOOKS_ENTITY_YEAR_FIELD')
                ]
            ),
            new IntegerField(
                'COPIES_CNT',
                [
                    'required' => true,
                    'title' => Loc::getMessage('BOOKS_ENTITY_COPIES_CNT_FIELD')
                ]
            ),
            (new IntegerField('PUBLISHER_ID')),
            (new Reference(
                'PUBLISHER',
                PublisherTable::class,
                Join::on('this.PUBLISHER', 'ref.ID')
            ))->configureJoinType('inner'),
            (new ManyToMany('AUTHOR', AuthorsTable::class))
                ->configureTableName('test_book_author'),
        ];
    }

    /**
     * Returns validators for TITLE field.
     *
     * @return array
     */
    public static function validateTitle()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }
}