<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PHPUnit\Framework\TestCase;
use ColumnarDB\db\entities\database;

class databaseTest extends TestCase{
    //put your code here
     protected $sqlParser;

    protected function setUp()
    {
        $this->sqlParser = new database();
    }
    /**
     * @covers database::list
     */
    public function testShow(){
        
        
        $this->assertEquals([0=>'testDatabase'],$this->database->show());
        $this->assertEquals(false,$this->database->show());

        
        
    }
    /**
     * @covers sqlParser::use
     */
    public function testUse(){
              
        $this->assertEquals(true,$this->database->use("testDatabase"));
        $this->assertEquals(false,$this->database->use("nonexistentDatabase"));   
        
    }
    
    /**
     * @covers sqlParser::parseInsertTable
     * @covers sqlParser::pairFieldsToValues
     */
    public function testCreateDatabases(){
                
        $this->assertEquals(true,$this->database->create("testDabase"));
        $this->assertEquals('tableA',$this->database->getEntityName());
        $this->assertEquals(false,$this->database->create("testDabase"));
    
    }  
    
    /**
    * @covers sqlParser::parseUpdateTable
    * @covers sqlParser::parseUpdateFields
    * @covers sqlParser::removeDoubleSingleQuotes
    */
    public function testParseUpdateTable(){
        
        
        
        $this->assertEquals(true,$this->sqlParser->parseUpdateTable("update names set first_name='erwin', last_name='bredy' where first_name ='erwin';"));
        $this->assertEquals(true,$this->sqlParser->parse("update names set first_name='erwin', last_name='bredy', age=30 where first_name ='erwin';"));
        $this->assertEquals('names',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([
                                'first_name'=>'erwin',
                                'last_name'=>'bredy',
                                'age'=> 30
            
        ],$this->sqlParser->getFields());
        $this->assertEquals('update',$this->sqlParser->getAction());
        $this->assertEquals("first_name ='erwin'",$this->sqlParser->getFilters());
        $this->assertEquals(false,$this->sqlParser->parseUpdateTable("updatenames set first_name='erwin', last_name='bredy' where first_name ='erwin';"));
        $this->assertEquals(false,$this->sqlParser->parse("updatenames set first_name='erwin', last_name='bredy' where first_name ='erwin';"));
        
        
    }   

    /**
    * @covers sqlParser::parseCreateTable
    */
    public function testParseCreateTable(){
        
        
        
        $this->assertEquals(true,$this->sqlParser->parseCreateTable("create table tableA(first_name varchar(27),last_name varchar(27),age int);"));
        $this->assertEquals(true,$this->sqlParser->parse("create table tableA(first_name varchar(27),last_name varchar(27),age int);"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([
                                0=>'first_name varchar(27)',
                                1=>'last_name varchar(27)',
                                2=>'age int'
            
        ],$this->sqlParser->getFields());
        $this->assertEquals('create',$this->sqlParser->getAction());
        $this->assertEquals([],$this->sqlParser->getFilters());

        $this->assertEquals(true,$this->sqlParser->parse("create table tableA(first_name varchar(27),last_name varchar(27),age int);"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([
                                0=>'first_name varchar(27)',
                                1=>'last_name varchar(27)',
                                2=>'age int'
            
        ],$this->sqlParser->getFields());
        $this->assertEquals('create',$this->sqlParser->getAction());
        $this->assertEquals([],$this->sqlParser->getFilters());
        
        $this->assertEquals(false,$this->sqlParser->parse("create tabletableA(first_name varchar(27),last_name varchar(27),age int);"));

        
        
    } 
    /**
     * @covers sqlParser::parseUseDatabase
     */
    public function testParseUseDatabase(){
        
        
        $this->assertEquals(true,$this->sqlParser->parseUseDatabase("use erwin;"));
        $this->assertEquals(true,$this->sqlParser->parse("use erwin;"));
        $this->assertEquals(false,$this->sqlParser->parseCreateTable("create tabletableA(first_name varchar(27),last_name varchar(27),age int);"));

        
        
    }
    /**
     * @covers sqlParser::parseListDatabases
     */
    public function testParseListDatabases(){
        
        
        $this->assertEquals(true,$this->sqlParser->parseListDatabases("list databases;"));
        $this->assertEquals(true,$this->sqlParser->parse("list databases;"));
        $this->assertEquals(false,$this->sqlParser->parseListDatabases("create tabletableA(first_name varchar(27),last_name varchar(27),age int);"));

        
        
    }
    /**
     * @covers sqlParser::parseCreateDatabase
     */
    public function testParseCreateDatabase(){
        
        
        $this->assertEquals(true,$this->sqlParser->parseCreateDatabase("create database Erwin;"));
        $this->assertEquals(true,$this->sqlParser->parse("create database Erwin;"));
        $this->assertEquals('Erwin',$this->sqlParser->getEntityName());
        $this->assertEquals('database',$this->sqlParser->getEntity());
        $this->assertEquals([],$this->sqlParser->getFields());
        $this->assertEquals('create',$this->sqlParser->getAction());
        $this->assertEquals([],$this->sqlParser->getFilters());
  
        $this->assertEquals(false,$this->sqlParser->parseCreateTable("create tabletableA(first_name varchar(27),last_name varchar(27),age int);"));

        
        
    }
    
}
