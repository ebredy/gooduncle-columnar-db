<?php
/**
 * Description of sort
 *
 * @author ebred
 */
namespace ColumnarDB\utilities;

class quickSort {
    
    public static function sort( array &$data, $low, $hi ){
        
        if( $hi > $low ){
            $p = self::partition($data, $low, $hi);
            self::sort($data, $low, $p-1);
            self::sort($data, $p+1, $hi);
        }
    }
    public static function partition(array &$data, $low, $hi){
        
        $pivot =  $data[$hi];
        
        $y = $lo-1;
        
        for($x = $lo; $x<=$hi; $x++){
            
            if( $data[$x]<= $pivot ){
                $y++;
                if($y != $x ){
                    self::swap($data, $y, $x);
                }
            }
        }
        return $y;
    }
     public static function swap(array &$data, $swapIndex1, $swapIndex2){
        
        $temp =  $data[$swapIndex1];
        
        $data[$swapIndex1] = $data[$swapIndex2];
        
        $data[$swapIndex2] =  $temp;
    }
}
