<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Loader;
use Salikov\Bookcatalog\BooksTable;
use Salikov\Bookcatalog\AuthorsTable;
use Salikov\Bookcatalog\PublisherTable;
use Salikov\Bookcatalog\BookAuthorTable;
use \Bitrix\Main\Entity\Base;


Loc::loadMessages(__FILE__);
CModule::IncludeModule("main");

class salikov_bookcatalog extends CModule
{
    var $MODULE_ID = 'salikov.bookcatalog';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    public function __construct()
    {
        if(file_exists(__DIR__."/version.php")){

            $arModuleVersion = [];
            include __DIR__ . '/version.php';

            $this->MODULE_ID 		   = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION 	   = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

            $this->MODULE_NAME =  Loc::getMessage('RS_NAME');
            $this->MODULE_DESCRIPTION =  Loc::getMessage('RS_MODULE_DESCRIPTION');
            $this->PARTNER_NAME =  Loc::getMessage("RS_PARTNER_NAME");
            $this->PARTNER_URI = Loc::getMessage("RS_PARTNER_URI");
        }
        return false;
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if(CheckVersion(ModuleManager::getVersion("main"), "14.00.00")){

            $this->InstallFiles();
            $this->InstallDB();
            $this->seedData();
            ModuleManager::registerModule($this->MODULE_ID);

        }else{

            $APPLICATION->ThrowException(Loc::getMessage("RS_ERROR_VERSION"));
        }

        return true;
    }

    public function InstallFiles(){
        return true;
    }

    public function InstallDB(){
        Loader::includeModule($this->MODULE_ID);
        //книги
        if(!Application::getConnection(BooksTable::getConnectionName())->isTableExists(
            Base::getInstance('\Salikov\Bookcatalog\BooksTable')->getDBTableName()
        )){
            Base::getInstance('\Salikov\Bookcatalog\BooksTable')->createDbTable();
        }
        //авторы
        if(!Application::getConnection(AuthorsTable::getConnectionName())->isTableExists(
            Base::getInstance('\Salikov\Bookcatalog\AuthorsTable')->getDBTableName()
        )){
            Base::getInstance('\Salikov\Bookcatalog\AuthorsTable')->createDbTable();
        }
        //издательства
        if(!Application::getConnection(PublisherTable::getConnectionName())->isTableExists(
            Base::getInstance('\Salikov\Bookcatalog\PublisherTable')->getDBTableName()
        )){
            Base::getInstance('\Salikov\Bookcatalog\PublisherTable')->createDbTable();
        }
        //N:M
        if(!Application::getConnection(BookAuthorTable::getConnectionName())->isTableExists(
            Base::getInstance('\Salikov\Bookcatalog\BookAuthorTable')->getDBTableName()
        )){
            Base::getInstance('\Salikov\Bookcatalog\BookAuthorTable')->createDbTable();
        }
    }

    public function seedData(){
        $authors = [
            ['ID'=>1, 'FIRST_NAME' => 'Иван', 'LAST_NAME' => 'Иванов', 'SECOND_NAME'=> 'Иванович', 'CITY'=>'Иваново'],
            ['ID'=>2, 'FIRST_NAME' => 'Петр', 'LAST_NAME' => 'Петрнов', 'SECOND_NAME'=> 'Петрович', 'CITY'=>'Петрово'],
            ['ID'=>3, 'FIRST_NAME' => 'Семен', 'LAST_NAME' => 'Семенов', 'SECOND_NAME'=> 'Семенович', 'CITY'=>'Семеново'],
        ];
        $books = [
            ['ID' => 1, 'TITLE'=>'Книга 1', 'YEAR'=>2000, 'COPIES_CNT' => 1000, 'PUBLISHER_ID' => 1],
            ['ID' => 2, 'TITLE'=>'Книга 2', 'YEAR'=>2001, 'COPIES_CNT' => 2000, 'PUBLISHER_ID' => 2],
            ['ID' => 3, 'TITLE'=>'Книга 3', 'YEAR'=>2002, 'COPIES_CNT' => 3000, 'PUBLISHER_ID' => 1],
            ['ID' => 4, 'TITLE'=>'Книга 4', 'YEAR'=>2003, 'COPIES_CNT' => 4000, 'PUBLISHER_ID' => 1],
            ['ID' => 5, 'TITLE'=>'Книга 5', 'YEAR'=>2004, 'COPIES_CNT' => 5000, 'PUBLISHER_ID' => 2],
            ['ID' => 6, 'TITLE'=>'Книга 6', 'YEAR'=>2005, 'COPIES_CNT' => 6000, 'PUBLISHER_ID' => 2],
        ];
        $publisher = [
            ['ID'=>1, 'TITLE'=> 'БумПромЛесХозОблГосПечать', 'CITY' => 'Мусохранск', 'AUTHOR_PROFIT' => 100],
            ['ID'=>2, 'TITLE'=> 'Солнышко', 'CITY' => 'Гадюкино', 'AUTHOR_PROFIT' => 102],
        ];
        foreach ($authors as $author){
            AuthorsTable::add($author);
        }
        foreach ($books as $book){
            BooksTable::add($book);
        }
        foreach ($publisher as $p){
            PublisherTable::add($p);
        }

        $ab = [
            1 => [1, 4],
            2 => [1,2,5],
            3 => [3, 1, 6],
        ];
        foreach ($ab as $a => $bArr){

            $author = AuthorsTable::getByPrimary($a)->fetchObject();
            foreach($bArr as $b){
                $book = BooksTable::getByPrimary($b)->fetchObject();
                $author->addToBook($book);
            }
            $author->save();
        }
    }

    public function DoUninstall(){
        $this->UnInstallFiles();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function UnInstallFiles(){

        return true;
    }

    public function UnInstallDB(){

        Loader::includeModule($this->MODULE_ID);
        //книги
        Application::getConnection(BooksTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('Salikov\Bookcatalog\BooksTable')->getDBTableName());
        //авторы
        Application::getConnection(AuthorsTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('Salikov\Bookcatalog\AuthorsTable')->getDBTableName());
        //издательства
        Application::getConnection(PublisherTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('Salikov\Bookcatalog\PublisherTable')->getDBTableName());
        //издательства
        Application::getConnection(BookAuthorTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('Salikov\Bookcatalog\BookAuthorTable')->getDBTableName());

        Option::delete($this->MODULE_ID);

    }

}
?>