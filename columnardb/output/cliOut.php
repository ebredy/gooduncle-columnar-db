<?php

/**
 * formats query output
 *
 * @author ebred
 */
namespace ColumnarDB\output;

class cliOut {
    
    protected $horizontalDividerChar = "=";
    protected $verticalDividerChar ="|";
    protected $horizontalDivider =  null;
    protected $maxColumnLength = [];
    protected $sumColumnLength = 0;
    protected $header = null;
    protected $body =  null;
    protected $output = null;
    protected $columnLabels = [];
            

    
    
    function __construct() {
           
    }
    public function AddColumn( $columnName, $length ){
        
        $this->columnLabels[] = [   
                                    'column_label'  =>  $columnName, 
                                    'column_length' =>  $length 
                                ];
    }    
    function generateHeaders(){
        
        $this->sumColumnLength = 0;
        
        foreach( $this->columnLabels  as $index => $columnLabel ){
            
            if( !isset($columnLabel[ $index ][ 'column_length' ] ) ){
                
                throw new Exception("Column length is missing in column header for header # ".$index);
                
            }
            
            $this->maxColumnLength[ $index ] = $columnLabels[ $index ][ 'column_length' ];
            
            $this->sumColumnLength  += $this->maxColumnLength[ $index ];
            
            if(!isset($columnLabel[ $index ][ 'column_label' ])){
                 
                throw new Exception("Column label is missing in column header for header # ".$index);

            }
            
            $this->sumColumnLength += $index == 0?2:1;
            
            $this->output .= $this->setColumnValue( $columnLabel[ $index ][ 'column_label' ], $index, $this->maxColumnLength[ $index ] );
             
            
        }
        
        
        $horizontalDivider = $this->getHorizontalDivider();
        
        $this->output = $horizontalDivider.$this->output.$horizontalDivider;
        
        //this is not necesary but is used in the event needs to paginate output
        $this->header =  $this->output;
    }
    
    protected function setColumnValue( $columnVal, $index, $length ){
        
        $leftBorder = $index == 0?$this->verticalDividerChar:"";
            
        $this->sumColumnLength += $index == 0?2:1;
        
       return printf($leftBorder."[%0{$length}s]{$this->verticalDividerChar}", $columnVal );
       
    }
    
    protected function getHorizontalDivider(){
        
        return $this->horizontalDivider  =  str_repeat( $this->horizontalDivider, $this->sumColumnLength )."\n";
    }
    
    function setBody( array $body ){
      
        foreach( $body as $row => $columns ){
            
            foreach( $columns as $index => $columnVal ){

                $this->output.= $this->setColumnValue( $columnVal, $index, $this->maxColumnLength[ $index ] );
            }
            
        }
        $horizontalDivider = $this->getHorizontalDivider();
        
        $this->output = $horizontalDivider.$this->output.$horizontalDivider;
    }
    
    function output(){
        
        return $this->output;
    }
    function clear(){
        
        $this->header = null;
        $this->body = null;
        $this->output = null;
    }
}
