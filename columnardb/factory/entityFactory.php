<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace ColumnarDB\factory;

use ColumnarDB\db\interfaces\entityInterface;
/**
 * Description of entityFactory
 *
 * @author ebred
 */
class entityFactory implements entityInterface {
   
    
    private static $_entityIstance = [];


    public static function getInstance()
    {
        $entity = get_called_class();
        
        if ( !isset( self::$_entityIstance[ $entity ] ) ) {
            
            self::$_entityIstance[ $entity ] = new static;
        }
        
        return self::$_entityIstance[ $entity ];
    }
}
