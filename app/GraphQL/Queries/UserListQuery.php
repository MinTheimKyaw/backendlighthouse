<?php
 
namespace App\GraphQL\Queries;
use Illuminate\Support\Facades\DB;
class UserListQuery extends BaseQuery
{
    public function paginate($root,array $args)
    {
        $startRow=$args['start'];
        $endRow=$args['limit'];
        
        $strCommandText=" SELECT  SQL_CALC_FOUND_ROWS * FROM users WHERE 1=1 ";
        $param = array();
        $strCommandText .= " limit :PageNo , :PageSize ";
        $param[':PageNo']= $startRow;
        $param[':PageSize']= $endRow;	
       
        $result = $this->execute_query($strCommandText, $param) or die("query fail.");		
       // return new readonlyresultset($result,$this->queryFoundRows());
        return $result;
        //return $cards;
        /*return \App\Models\User::query()->paginate(
            $args['limit'],
            ['*'],
            'page',
            $args['start']
        );*/
    }
}