<?php

namespace App\GraphQL\Queries;
use PDO;

class readonlyresultset 
{
	private $rs;
	private $foundrows;
	private $field;
	private $current_record;
	private $results;
	private $returnDataSet;
	private $returnType="";
	function __construct($rs,$foundrows,$returnDataSet=false,$returnType="") 
	{
		$PDO_FETCH_ASSOC = PDO::FETCH_ASSOC;
		$this->rs = $rs;
		$this->current_record = 0;
		if($this->rs != null) 
			$this->results = $this->rs->fetchAll($PDO_FETCH_ASSOC);
		else
			$this->results = [];
		$this->foundrows = $foundrows;//$this->queryFoundRows();
		$this->returnDataSet = $returnDataSet;
		$this->returnType ="";//$returnType;
	}
	
	function getReturnType(){
		return ($this->returnType=="")? "":$this->returnType;
	}
	
	function getResults(){
		if($this->returnDataSet)
			return [$this->results];
		else
			return $this->results;
	}
	
	function getNext() 
	{
		for($i=$this->current_record;$i<$this->rs->rowCount();$i++)
		{
			$resultset = $this->results;
			$this->current_record++;
			if(isset($resultset[$i]))
				return $resultset[$i];
			else
				return false;
		}
	}
	
	function reset() 
	{
		$this->current_record = 0;
	}
	
	function seek($index=0)
    {
       $this->current_record = $index;
    }
	
	function rowCount() 
	{
		return ($this->rs == null)? 0:$this->rs->rowCount();
	}
	
	function getFoundRows() 
	{
		return $this->foundrows;
	}
	
	function queryFoundRows()
	{
		/*
		******* It will call at BaseDAL.php ******
		$qry = "SELECT FOUND_ROWS()";
		global $conn;
		$result=$conn->prepare($qry);
		$result->execute();
		$result->bindColumn(1,$aResultFilterTotal);
		$result->fetch();
		return $aResultFilterTotal;
		*/
	}
	
	function toArray($key="")
	{
		$resultset = $this->rs;
		$rtn_array = array();
		if ($resultset->rowCount()>0)
		{
			while($row = $resultset->fetch())
			{
				if ( $key == "")
					$rtn_array[] = $row;
				else 
					$rtn_array[$row[$key]] = $row;
			}
		}
		return $rtn_array;
	}
}
?>