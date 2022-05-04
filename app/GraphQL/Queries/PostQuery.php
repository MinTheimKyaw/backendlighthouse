<?php

namespace App\GraphQL\Queries;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Post;
class PostQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function showAll($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        //print_r("aa");exit();
        $first = $args["first"];
        $page = $args["page"];

        $filtertitle = "";
        $filtercontent = "";
        if(array_key_exists("filtertitle",$args)) {
            $filtertitle = $args["filtertitle"];
        }
        if(array_key_exists("filtercontent",$args)) {
            $filtercontent = $args["filtercontent"];
        }
        // this filter 
        $posts = Post::where("title","like","%{$filtertitle}%")   
                        ->where("content","like","%{$filtercontent}%")   
                        ->orderBy("created_at","DESC");
        return $this->showPostPageData($posts,$first,$page);
    }
    private function showPostPageData($posts,$first,$page) {
        
        $posts = $posts->paginate($first,['*'],'page',$page);
        return [
            "data" => $posts,
            "page" => [
                "currentPage" => $page,
                "lastPage" => $posts->lastPage(),
                "total"=>$posts->total()
            ]
        ];
    }
}
