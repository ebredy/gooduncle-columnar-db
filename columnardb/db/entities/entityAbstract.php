<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ColumnarDB\db\entities;

/**
 * Description of entityAbstract
 *
 * @author ebred
 */
abstract class entityAbstract {
    //put your code here
    protected $output = null;
  
    protected $fileManager = null;
    
    public function __construct() {
        
    }
    
    
    public function setDirectorySeperator( array $paths ){
        
        foreach($paths as $key => $value ){
             
             $paths[ $key ] = str_replace("{ds}",DIRECTORY_SEPARATOR,$value);
         }
         
         return $paths;
    }
}
