<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of application
 *
 * @author ebred
 */
namespace app;

use db\entities;
use parser\sqlParser;

class application {
    
    
    public static function run(){
        
        $sqlParser = new sqlParser();
        
        while( $sql = fgets( STDIN ) ){
            
            if( $sqlParser->parse( $sql ) ){
                
             }
           
        }
    }
  
}
