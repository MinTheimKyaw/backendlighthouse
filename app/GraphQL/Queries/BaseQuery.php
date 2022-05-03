<?php
namespace App\GraphQL\Queries;
use DB;
class BaseQuery{
    protected $db;
	protected $sqlErrorMsg = "";

    public function __construct()
    {
        $PDO = \DB::connection()->getPdo();
		//print_r($PDO);
       
		$this->db = $PDO;
    }

    function getSQLErrorMsg(){
		return $this->sqlErrorMsg;
	}

    function queryFoundRows()
	{
		try{
			$qry = "SELECT FOUND_ROWS()";
			$result=$this->db->prepare($qry);
			$result->execute();
			$result->bindColumn(1,$aResultFilterTotal);
			$result->fetch();
			return $aResultFilterTotal;
		}catch(\PDOException $exp){
			$this->sqlErrorMsg = $exp->getMessage();
		}
		return 0;
	}

    function last_instert_id()
	{
		try{
			return $this->db->lastInsertId();	
		}catch(\PDOException $exp){
			$this->sqlErrorMsg = $exp->getMessage();
		}
		return 0;
	}

    function execute_non_query($query, $param=array())		//return true on success query and false on fail query
	{
		try{
			$result=$this->db->prepare($query);
			//var_dump($result);
			if(!$result)
			{
				$err_arr = $this->db->errorInfo();
				die($err_arr[2] . " " . $query);
			}
			//echo count($param);exit();
			if(count($param)>0)
				return $result->execute($param);
			else
				return $result->execute();
		}catch(\PDOException $exp){
			$this->sqlErrorMsg = $exp->getMessage();
		}
		return false;
	}
	
	function execute_scalar_query($query, $param=array())		//return true on success query and false on fail query
	{
		try{
			$result=$this->db->prepare($query);
			if(!$result)
			{
				$err_arr = $this->db->errorInfo();
				die($err_arr[2] . " " . $query);
			}
			
			if(count($param)>0)
				$result->execute($param);
			else
				$result->execute();
			$result->bindColumn(1, $retvalue);
			$result->fetch();
			return $retvalue;
		}catch(\PDOException $exp){
			$this->sqlErrorMsg = $exp->getMessage();
		}
		return false;
	}

    function execute_query($query, $param=array(), $useSet=false)	//return result on sucess query, die on fail query
	{
		try{
           
			if($useSet) $this->db->query('set @row=0');
			$result=$this->db->prepare($query);
			if(!$result)
			{
				$err_arr = $this->db->errorInfo();
				die($err_arr[2] . " " . $query);
			}
			
			if(count($param)>0)
				$result->execute($param);
			else
				$result->execute();
			//echo 'enter dal '.$result;exit();
			return $result;
		}catch(\PDOException $exp){
			$this->sqlErrorMsg = $exp->getMessage();
		}
		return false;
	}
    
	function debugPDO($raw_sql, $parameters)
	{
		$keys = array();
		$values = $parameters;
		foreach ($parameters as $key => $value) 
		{
			// check if named parameters (':param') or anonymous parameters ('?') are used
			if (is_string($key)) 
			{
				if (substr($key, 0, 1) === ':')
					$keys[] = '/'.$key.'/';
				else
					$keys[] = '/:'.$key.'/';
			} 
			else 
			{
				$keys[] = '/[?]/';
			}
			// bring parameter into human-readable format
			if (is_string($value)) {
				$values[$key] = "'" . $value . "'";
			} elseif (is_array($value)) {
				$values[$key] = implode(',', $value);
			} elseif (is_null($value)) {
				$values[$key] = 'NULL';
			}
		}
		$raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);
		return $raw_sql;
	}

    //global function
    function get_pageoffset($rowsperpage,$currentpage) 
	{
      $currentpage = $currentpage+1;
	  $offset = $rowsperpage * ($currentpage - 1);      
      return $offset;
   	}
}