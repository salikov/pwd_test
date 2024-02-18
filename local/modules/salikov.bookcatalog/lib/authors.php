<?php
namespace Salikov\Bookcatalog;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\ORM\Query\Join,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

class AuthorsTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'test_authors';
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
                    'title' => Loc::getMessage('AUTHORS_ENTITY_ID_FIELD')
                ]
            ),
            new StringField(
                'FIRST_NAME',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateFirstName'],
                    'title' => Loc::getMessage('AUTHORS_ENTITY_FIRST_NAME_FIELD')
                ]
            ),
            new StringField(
                'LAST_NAME',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateLastName'],
                    'title' => Loc::getMessage('AUTHORS_ENTITY_LAST_NAME_FIELD')
                ]
            ),
            new StringField(
                'SECOND_NAME',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateSecondName'],
                    'title' => Loc::getMessage('AUTHORS_ENTITY_SECOND_NAME_FIELD')
                ]
            ),
            new StringField(
                'CITY',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateCity'],
                    'title' => Loc::getMessage('AUTHORS_ENTITY_CITY_FIELD')
                ]
            ),
            (new ManyToMany('BOOK', BooksTable::class))
                ->configureTableName('test_book_author')
        ];
    }

    /**
     * Returns validators for TITLE field.
     *
     * @return array
     */
    /**
     * Returns validators for FIRST_NAME field.
     *
     * @return array
     */
    public static function validateFirstName()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for LAST_NAME field.
     *
     * @return array
     */
    public static function validateLastName()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }

    /**
     * Returns validators for SECOND_NAME field.
     *
     * @return array
     */
    public static function validateSecondName()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for CITY field.
     *
     * @return array
     */
    public static function validateCity()
    {
        return [
            new LengthValidator(null, 80),
        ];
    }
}