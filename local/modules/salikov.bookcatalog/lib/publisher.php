<?php
namespace Salikov\Bookcatalog;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\FloatField,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\ORM\Query\Join,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

class PublisherTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'test_publisher';
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
                    'title' => Loc::getMessage('PUBLISHER_ENTITY_ID_FIELD')
                ]
            ),
            new StringField(
                'TITLE',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateTitle'],
                    'title' => Loc::getMessage('PUBLISHER_ENTITY_TITLE_FIELD')
                ]
            ),
            new StringField(
                'CITY',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateCity'],
                    'title' => Loc::getMessage('PUBLISHER_ENTITY_CITY_FIELD')
                ]
            ),
            new FloatField(
                'AUTHOR_PROFIT',
                [
                    'required' => true,
                    'title' => Loc::getMessage('PUBLISHER_ENTITY_AUTHOR_PROFIT_FIELD')
                ]
            ),
            (new OneToMany('BOOKS', BooksTable::class, 'PUBLISHER'))->configureJoinType('inner')
        ];
    }

    /**
     * Returns validators for TITLE field.
     *
     * @return array
     */
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