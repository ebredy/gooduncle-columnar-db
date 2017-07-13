<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ColumnarDB\utilities;

/**
 * Description of fileManager
 *
 * @author ebred
 */
class fileManager {
    
    protected $file; //file
    
    protected $handler; //handler

    /*
     * opens the file
     */
    public function __construct( $file = false ) {
        
        $this->setFile($file);
    }
    public function setFile( $file ) {
        
        $this->file = $file;
        
        $dirname = dirname($this->file);
        
        if ( !is_dir($dirname) )
        {
            mkdir( $dirname, 0755, true);
        }
    }
    public function isValid(){
        
        return file_exists($this->file);
    }
    /*
     * writes to file
     * @input filepath
     * @returns array
     */
    function read( $length = false, $searchCallable = false, $postFormatCallable = false ){
        
        $this->handler = fopen( $this->file, "r" ); // open it for reading ("r")
        
        if( flock( $this->handler, LOCK_SH ) ) {
            
            $string = "";
            
            while( !feof( $this->handler ) ){
                
                /*
                 * if length is specified then get in batches if not then 
                 * load into memory
                 */
               
                if( $length ){
                    
                    $buffer = fgets( $this->handler, $length );
                }
                else {
                    $buffer = fgets( $this->handler );
                }
                if( $foundString = $this->runCallable( $searchCallable, [$buffer] ) ){
                    
                    $string = $foundString;
                    
                    break; // break out of loop because string is found
                }
                
               $string .=  $buffer;
               
            }
            
            flock($this->handler, LOCK_UN ); // unlock the file
         
            $formattedData = $this->runCallable($postFormatCallable,[$string]);
            
            return $formattedData? $formattedData: $string;
            
        }
        
        return false;

    }
    protected function runCallable($callable, array $arguments ){
        
        if( $callable ){     

            if( !is_callable( $callable ) ){

               return false;
            }

            return call_user_func_array( $callable, $arguments );
        } 
    }
    /*
     * writes array to file
     * @returns: bool true on succcess/false on error
     */
    function write( array $value, $preFormatCallable = false, $mode = "w+" ){
        
       if( $string = $this->runCallable( $postFormatCallable,[ $value ] ) ){
         
           $this->handler = fopen( $this->file, $mode ); 
           
            if( flock( $this->handler, LOCK_EX | LOCK_NB ) ) { 

                $fwriteStatus = fwrite( $this->handler, $string  );

                flock( $this->handler, LOCK_UN ); 
                
                return $fwriteStatus?true:false;
            }
       }
        return false;
    }
    
     /*
     * writes array to file
     * @returns: bool true on succcess/false on error
     */
    function append( array $value, $preFormatCallable = false, $mode = "a+" ){
        
       return $this->write( $value, $preFormatCallable, $mode );
         
    }   
    /*
     * close file handler
     */
    function close(){
        
        fclose( $this->handler );
    }
    /*
     * get the file size
     * 
     */
    function size( $filename ){
        
        return filesize ( $filename );
    }
}
