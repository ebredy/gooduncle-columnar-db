<?php


/**
 * Description of sql_parser
 *
 * @author ebred
 */
namespace ColumnarDB\parser;

class sqlParser {
    
    private $_entity;
    
    private $_entityName = false;
    
    private $_action;
    
    private $_fields =[0=>'*'];
    
    private $_filters = [];
    
    private $_regexMatches = [];
    
    function __construct() {
        
    }
    
    function parse( $sql ){
        
        if( strpos( $sql,"select ") !== false ){
            
            return $this->parseSelectTable( $sql );
        }
        elseif( strpos( $sql, "insert " )!==false ){
            
            return $this->parseInsertTable( $sql );
        }
         elseif( strpos( $sql, "update " )!== false ){
             
            return $this->parseUpdateTable( $sql );
        } 
        elseif( strpos( $sql,"delete ")!== false ){
            
            return $this->parseDeleteTable( $sql );
        }
        elseif( strpos( $sql,"list ")!== false && strpos( $sql,"databases")!== false ){
            
            return $this->parseListDatabases( $sql );
        } 
        elseif( strpos( $sql,"list ")!== false && strpos( $sql,"tables")!== false ){
            
            return $this->parseListTables( $sql );
        }          
        elseif( strpos( $sql,"use ")!== false ){
            
            return $this->parseUseDatabase( $sql );
        } 
        elseif( strpos( $sql,"create ") !== false &&  strpos( $sql,"database")){
            
            return $this->parseCreateDatabase( $sql );
        } 
        elseif( strpos( $sql,"create ") !== false &&  strpos( $sql,"table")){
            
            return $this->parseCreateTable( $sql );
        }
        else{
            return false;
        }
    }
    
    function getEntity(){
        
        return $this->_entity;
    }
    
    function getEntityName(){
        
        return $this->_entityName?$this->_entityName:'';
    }
    
    function getAction(){
        
        return $this->_action;
    }
       
    function getFields(){
        
        return $this->_fields;
    
    }
       
    function getFilters(){
        
        return $this->_filters;
    
    }
    
    function getRegexMatches(){
        
        return $this->_regexMatches;
        
    }
    
    function parseListTables( $sql ){
        
        if( preg_match('/list\s+tables\s*?;/i', $sql, $this->_regexMatches ) ){
                
            $this->regexToVariableMapper([
                
                'entity'=> 'tables',
                'action'=> 'list'
                    
            ]);
            
            return true;
        }
        
        return false;
        
    }    
    function parseSelectTable( $sql ){
        
        if( preg_match('/select\s+(.*?)\s*from\s+(.*?)\s*(where\s(.*?)\s*)?;/i', $sql, $this->_regexMatches ) ){
                        
            $this->regexToVariableMapper([
                
                'entity'     => 'table',
                'action'     => 'select',
                'fields'     => explode(",", $this->_regexMatches[1] ),
                'entityName' => $this->_regexMatches[2],
                'filters'    => isset($this->_regexMatches[3])?$this->_regexMatches[3]:null
                    
            ]);
            
            return true;
            
        }
        
        return false;
    }
    
    function parseUpdateTable( $sql ){
        
         if( preg_match('/update\s+(.*?)\s+set\s+(.*?)\s+where\s+(.*?)\s*?;/i',  $sql, $this->_regexMatches ) ){
                         
            $this->regexToVariableMapper([
                
                'entity'     => 'table',
                'entityName' => $this->_regexMatches[1],
                'fields'     => $this->parseUpdateFields( explode( ",", $this->_regexMatches[2] ) ),
                'action'     => 'update',
                'filters'    => isset($this->_regexMatches[3])?$this->_regexMatches[3]:null
                    
            ]);
            
            return true;
            
         }
         
         return false;
         
    }
    
    function parseInsertTable( $sql ){
        
         if( preg_match('/insert\s+into\s+(.*?)\s*\(\s*(.+?)\s*\)\s+values\s*\(\s*(.*?)\s*\)\s*;/i', $sql, $this->_regexMatches ) ){
                        
            $this->regexToVariableMapper([
                
                'entity'     => 'table',
                'action'     => 'insert',
                'entityName' => $this->_regexMatches[1],
                'fields'     => $this->pairFieldsToValues( explode(",", $this->_regexMatches[2] ), explode(",",$this->_regexMatches[3]) ),
                'filters'    => isset( $this->_regexMatches[4] )? $this->_regexMatches[4]:null
                    
            ]);
            
            return true;
            
         }
         
         return false;
         
    }
    function parseDeleteTable( $sql ){
        
        if( preg_match('/delete\s+from\s+(.*?)\s+where\s(.*?)\s*;/i', $sql, $this->_regexMatches ) ){
             
            $this->regexToVariableMapper([
                
                'entity'     => 'table',
                'entityName' => $this->_regexMatches[1],
                'action'     => 'create',
                'filters'    => $this->_regexMatches[2]
                    
            ]);
            
            return true;
            
        }
        
        return false;
    }
    function parseCreateDatabase( $sql ){
        
        if( preg_match('/create\s+database\s+(.+?)\s*;/i', $sql, $this->_regexMatches ) ){
            
            $this->regexToVariableMapper([
                
                'entity'     => 'database',
                'action'     => 'create',
                'entityName' => $this->_regexMatches[1],
                'fields' => []
            
            ]);
            
            return true;
        }
        
        return false;
        
    } 
    
    function parseCreateTable( $sql ){
        
        if( preg_match('/create\s+table\s+(.*?)\s*\(\s*(.+?)\s*\)\s*;/i', $sql, $this->_regexMatches ) ){
            
            $this->regexToVariableMapper([
                
                'entity'     => 'table',
                'action'     => 'create',
                'entityName' => $this->_regexMatches[1],
                'fields'     => explode( ",", $this->_regexMatches[2] ),
            
            ]);
            
            return true;
        }
        
        return false;
        
    } 
    
    function parseListDatabases( $sql ){
        
        if( preg_match('/list\s+databases\s*?;/i', $sql, $this->_regexMatches ) ){
                
            $this->regexToVariableMapper([
                
                'entity'=> 'database',
                'action'=> 'list'
                    
            ]);
            
            return true;
        }
        
        return false;
        
    }
    
    function parseUseDatabase( $sql ){
        
        if( preg_match('/use\s+(.*?)\s*?;/i', $sql, $this->_regexMatches ) ){
        
            $this->regexToVariableMapper([

                'entity'     => 'database',
                'action'     => 'use',
                'entityName' =>  isset( $this->_regexMatches[1] )? $this->_regexMatches[1] : null

            ]);
            
            return true;
        }
        
        return false;
        
    }
    
    function parseUpdateFields(array $unparseFields ){
        
        $tempFields  = [];
        
        $fields = [];
        
        if( $unparseFields && !empty( $unparseFields ) ) {
            
            $totalFields =  count( $unparseFields );
            
            for( $x=0; $x < $totalFields; $x++ ){
                
                $tempFields = explode("=",$unparseFields[$x]);
                    
                $fields[ trim($tempFields[0]) ] = trim($this->removeDoubleSingleQuotes( $tempFields[1] ));
                
            }
        }
        
        return $fields;
    }
    
    function regexToVariableMapper( $regexVariables ){
        
        $this->_entity     = isset( $regexVariables['entity'] )? $regexVariables['entity'] : null;
        
        $this->_entityName = isset( $regexVariables['entityName'] )? $regexVariables['entityName'] : null;
        
        $this->_action     = isset( $regexVariables['action'] )? $regexVariables['action'] : null;
        
        $this->_fields     = isset( $regexVariables['fields'] )? $regexVariables['fields'] : $this->_fields;
        
        $this->_filters    = isset( $regexVariables['filters'] )? str_replace("where ", "", $regexVariables['filters'] ) : $this->_filters;
        
    }
    
     function pairFieldsToValues(Array $fields=[],Array $values=[]){
         
         $fieldsToValues = [];
         
         if( $fields && ( count( $fields ) == count( $values ) ) ){
             
             $total =  count( $fields );
             
             for( $x=0; $x < $total; $x++){
                 
                 $fieldsToValues[$fields[$x]] = $this->removeDoubleSingleQuotes( $values[$x] );
             }
         }
         
         return $fieldsToValues;
     }
     
     function removeDoubleSingleQuotes( $string ){
         
         return preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode( $string, ENT_QUOTES ) );
         
     }
}
