<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of table
 *
 * @author ebred
 */
namespace ColumnarDB\db\entities;
use ColumnarDB\utilities\fileManager;
use ColumnarDB\db\entities\database;

class table extends entityAbstract{
   protected $database = null;
  
    protected $tables = [
        'table' => '{tableName}/{tableName}.data',
        'column' => '{tableName}/{columnName}.data',
        'column_meta' => '{tableName}/{columnName}-meta.data'
    ];
    
    protected $tableData = [
            'columns'=> null,
            'column_datatypes'=> null,
            
    ];
    
    protected $selectedDatabasePath = null;
    
    function __construct($cliOut, $fileManager ) {
     
       
       $this->output = $cliOut;
        
       $this->fileManager = $fileManager;
        
       $this ->tables = $this->setDirectorySeperator( $this->tables );
       
    }
    
    protected function setSelectedDatabasePath( $currentDatabasePath ){
        
        $this->selectedDatabasePath = $currentDatabasePath;
    }
    protected function getSelectedDatabasePath( ){
        
        return $this->selectedDatabasePath;
    } 
    protected function getTablePath( $tableName ){
        
        return $this->getSelectedDatabasePath().str_replace("{tableName}", $tableName, $this->tables[ 'table' ]);
        
    }
    function select( $currentDatabasePath, $tableName, $columns, $filter ){
       
        
        if( !$currentDatabase ){
            
            return "No database selected.  Please select a database ";
            
        }
        
        $this->filemanager->setFile( $this->getTablePath( str_replace("{tableName}", $tableName, $this->tables['table'] ) ) );
        
        $selectedColumns = $this->fileManager->read(false,false,function( $jsonEncodedData )use( $columns ){
              
            $AllColumns = strlen( $jsonEncodedData ) ? json_decode( $jsonEncodedData ):[];

            if( strpos( $columns,"*") !== false ){

                  $columnToSelectArray = explode(",", $columns );
                  
                  return array_intersect( $AllColumns, $columnToSelectArray[ 'columns' ] );
            }
            else
            {
                  return $AllColumns;
            }

          
        });
        
        foreach( $selectedColumns as $index => $value ){
            
            /****
             * 
             * select appropriate columns
             */
        }

    }
    
    function insert( $tableName, $fields ){
       
        $this->filemanager->setFile( $this->getTablePath( str_replace("{tableName}", $tableName, $this->tables['table']  ) ) );

    }
    
    function update( $tableName, $fields, $filter ){
        
        $this->filemanager->setFile( $this->getTablePath( str_replace("{tableName}", $tableName, $this->tables['table']  ) ) );
    }
    
    function create($tableName, $columns ){
        
        $this->filemanager->setFile( $this->getTablePath( str_replace("{tableName}", $tableName, $this->tables['table']  ) ) );
        

        
         //create table paths
         $writeStatus = $this->fileManager->write( $columns, function( $data ){
          
            return json_encode( count( $data ) ? $data:[] );
          
        }); 
        
        $writeStatusArray = [];
        
        foreach($columns['name'] as $index => $columnLabel ){
            $this->filemanager->setFile( $this->getTablePath( str_replace("{columnName}", $columnLabel, str_replace("{tableName}", $tableName, $this->tables['column'] )  ) ) );
             //create column information
             $writeStatusArray[$index] = $this->fileManager->write("");
        }
        if( in_array( false, $writeStatusArray ) ){
            
            return "unable to create one of many columns";
        }
    }
    function show( $currentDatabasePath ){
        
    }
    protected function updateCreateColumn($tableName, $columns){
        
        $writeStatusArray = [];
        
        foreach($columns as $index => $columnLabel ){
            
            $this->filemanager->setFile( $this->getTablePath( str_replace("{columnName}", $columns[$index]['name'], str_replace("{tableName}", $tableName, $this->tables['column'] )  ) ) );
             //create column information
             $writeStatusArray[$index] = $this->fileManager->write("");
        }
        if( in_array( false, $writeStatusArray ) ){
            
            return false;
        }
        
        return empty($writeStatusArray)?false:true;
    }
}
