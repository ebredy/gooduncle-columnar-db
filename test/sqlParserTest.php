<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PHPUnit\Framework\TestCase;
use ColumnarDB\parser\sqlParser;

class sqlParserTest extends TestCase{
    //put your code here
     protected $sqlParser;

    protected function setUp()
    {
        $this->sqlParser = new sqlParser();
    }
    /**
     * @covers sqlParser::parseListTables
     */
    public function testParseListTables(){
        
        
        $this->assertEquals(true,$this->sqlParser->parseListTables("list tables;"));
        $this->assertEquals(true,$this->sqlParser->parse("list tables;"));
        $this->assertEquals(false,$this->sqlParser->parseListTables("create tabletableA(first_name varchar(27),last_name varchar(27),age int);"));

        
        
    }
    /**
     * @covers sqlParser::parseSelectTable
     * @covers sqlParser::getEntityName
     * @covers sqlParser::getEntity
     * @covers sqlParser::getFields
     * @covers sqlParser::getAction
     * @covers sqlParser::getFilters
     * @covers sqlParser::regexToVariableMapper
     */
    public function testParseSelectTable(){
        
        
        $this->assertEquals(true,$this->sqlParser->parseSelectTable("select * from tableA;"));
        $this->assertEquals([0=>'select * from tableA;',1=>'*',2=>'tableA'],$this->sqlParser->getRegexMatches());
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([0=>'*'],$this->sqlParser->getFields());
        $this->assertEquals('select',$this->sqlParser->getAction());
        $this->assertEquals([],$this->sqlParser->getFilters());

        
        $this->assertEquals(true,$this->sqlParser->parseSelectTable("select first_name,last_name from tableA where x='y';"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([0=>'first_name',1=>'last_name'],$this->sqlParser->getFields());
        $this->assertEquals('select',$this->sqlParser->getAction());
        $this->assertEquals("x='y'",$this->sqlParser->getFilters());
        

        $this->assertEquals(true,$this->sqlParser->parseSelectTable("select * from tableA where x='y';"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([0=>'*'],$this->sqlParser->getFields());
        $this->assertEquals('select',$this->sqlParser->getAction());
        $this->assertEquals("x='y'",$this->sqlParser->getFilters());
        
        $this->assertEquals(false,$this->sqlParser->parseSelectTable("selectfrom tableA;"));
        $this->assertEquals(false,$this->sqlParser->parseSelectTable("select * fromtableA;"));
         $this->assertEquals(false,$this->sqlParser->parseSelectTable("select first_name-last_namemiddlename fromtableA;"));
        
    }
    
    /**
     * @covers sqlParser::parseInsertTable
     * @covers sqlParser::pairFieldsToValues
     */
    public function testParseInsertTable(){
        
        
        
        $this->assertEquals(true,$this->sqlParser->parseInsertTable("insert into tableA(first_name,last_name,middle_name,age) values('Erwin','Bredy','Ader',30);"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([
                                'first_name'=>'Erwin',
                                'last_name'=>'Bredy',
                                'middle_name'=>'Ader',
                                'age' => 30
        ],$this->sqlParser->getFields());
        $this->assertEquals('insert',$this->sqlParser->getAction());
        $this->assertEquals([],$this->sqlParser->getFilters());


        
      
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
        $this->assertEquals(true,$this->sqlParser->parse("create table tableA(first_name varchar(27),last_name varchar(27),age int(3));"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([
                                0=>['name'=>'first_name','datatype'=>'varchar','length'=>27],
                                1=>['name'=>'last_name','datatype'=>'varchar','length'=>27],
                                2=>['name'=>'age','datatype'=>'int','length'=>3]
            
        ],$this->sqlParser->getFields());
        $this->assertEquals('create',$this->sqlParser->getAction());
        $this->assertEquals([],$this->sqlParser->getFilters());

        $this->assertEquals(true,$this->sqlParser->parse("create table tableA(first_name varchar(27),last_name varchar(27),age int(3));"));
        $this->assertEquals('tableA',$this->sqlParser->getEntityName());
        $this->assertEquals('table',$this->sqlParser->getEntity());
        $this->assertEquals([
                                0=>['name'=>'first_name','datatype'=>'varchar','length'=>27],
                                1=>['name'=>'last_name','datatype'=>'varchar','length'=>27],
                                2=>['name'=>'age','datatype'=>'int','length'=>3]
            
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
        $this->assertEquals('database',$this->sqlParser->getEntity());
        $this->assertEquals('select',$this->sqlParser->getAction());        
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
