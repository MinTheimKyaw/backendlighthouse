<?php

namespace App\GraphQL\Queries;
use Illuminate\Support\Facades\DB;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
class SomeComplexQuery extends BaseQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        
        //return \App\Models\Post::where('title','like','%'.$args['search'].'%')->get();
    }

    public function showAll($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        // TODO implement the resolver
        // Some Complex Query
        $strCommandText=" SELECT  SQL_CALC_FOUND_ROWS posts.id,posts.title,comments.reply FROM posts LEFT JOIN comments ON posts.id=comments.post_id WHERE posts.title LIKE :title";
        $param = array();
        $strCommandText .= " limit :PageNo , :PageSize ";
        $param[':title']= "%".$args['Search']."%";
        $pageNo = ($args['page']-1) * $args['first'];
        $param[':PageNo']= $pageNo;
        $param[':PageSize']= $args['first'];
       
       $result = $this->execute_query($strCommandText, $param) or die("query fail.");		
       $return_result= new readonlyresultset($result,$this->queryFoundRows());
        
        return [
            "data" => $return_result->getResults(),
            "page" => [
                "currentPage" => $args['page'],
                "lastPage" => $this->get_lastpage($return_result->getFoundRows(), $args['first']),
                "total"=>$return_result->getFoundRows()
            ]
        ];
    }
}
