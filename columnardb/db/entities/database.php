<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of database
 *
 * @author ebred
 */
namespace ColumnarDB\db\entities;
use ColumnarDB\utilities\fileManager;
use ColumnarDB\output\cliOut;
/*
 * database entity which  manages database level CRUD functions
 * 
 */
class database extends entityAbstract {
    
    protected $databases = [
        
        "current_database_path" =>  "dbdata{ds}databases{ds}state{ds}current.data",
        
        "all_databases_path"    =>  "dbdata{ds}databases{ds}alldb.data",
        
        "individual_database_path" =>  "dbdata{ds}databases{ds}{databaseName}{ds}"

    ];
    protected $all_databases = [];
    
    protected $current_database = ['use'=>''];
    
    
    function __construct( $cliOut, $fileManager ) {
       
        $this->output = $cliOut;
        
         $this->fileManager = $fileManager;
         
         $this ->databases = $this->setDirectorySeperator( $this->databases );
         
    }
    
    function show(){
        
      $this->fileManager->setFile( $this->databases[ "all_databases_path" ] );
      
     
      $this->output->AddColumn( 'Database(s)', strlen('Database(s)') );
      
      $this->generateHeaders();
      
      $this->output->setBody( $arrayOfDatabases );
      
      return $this->output->output();
      
    }

    function create( $databaseName ){
    
        $this->fileManager->setFile( $this->databases[ "all_databases_path" ] );
      
        $arrayOfDatabases = $this->fileManager->read(false,false,function( $jsonEncodedData ){
          
            return strlen( $jsonEncodedData ) ? json_decode( $jsonEncodedData ):[];
          
        });
      
        if( !in_array( strtolower( $databaseName ), $arrayOfDatabases ) ){
          
          $arrayOfDatabases[ ] = $databaseName;
          
        }
        else{
          
            return "Error: Database {$databaseName} already exists";
          
        }
      
        $writeStatus = $this->fileManager->write( $arrayOfDatabases, function( $data ){
          
            return json_encode( count( $data ) ? $data:[] );
          
        });
      
        return $writeStatus?"Successfully created database $databaseName}":"Error creating database $databaseName}";
    
        
    } 
    function select( $databaseName ){
        
        $this->current_database[ "use" ] = $databaseName;
        
        $this->fileManager->setFile( $this->databases[ "current_database_path" ] );
        
        $writeStatus = $this->fileManager->write( $this->current_database, function( $data ){

            return json_encode( count( $data ) ? $data:[] );

        });  
        
        return $writeStatus?"{$databaseName} database selected":"Unable able to select database {$databaseName}";
    }
    
    function getCurrentDatabase(){
        
        if(!isset($this->current_database['use'])){
            
            $this->fileManager->setFile( $this->databases[ "current_database_path" ] );
            
            $currentDatabase = $this->fileManager->read(false,false,function( $jsonEncodedData ){
          
                return strlen( $jsonEncodedData ) ? json_decode( $jsonEncodedData ):[];
          
            });
            
            return str_replace("{databaseName}", $currentDatabase, $this->individual_database_path );
            
        }
        
        return str_replace("{databaseName}", $this->current_database[ 'use' ], $this->individual_database_path );
        
    }
}
