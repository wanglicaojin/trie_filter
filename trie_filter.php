<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/6/15
 * Time: 19:36
 */

/*
 * 生成词库
 * $resTrie 资源   $v  敏感词
 * 打开trie_filter_new();
 * 写入   trie_filter_store($resTrie, $v);
 * 保存  trie_filter_save($resTrie, __DIR__ . '/blackword.tree');  资源 路径
 * // 获得查找结果树 trie_filter_free($resTrie);
 * 加载关键词 trie_filter_load(__DIR__ . '/blackword.tree');
 * t查找  rie_filter_search($resTrie, $strContent);
 * 全部查找 trie_filter_search_all($resTrie, $strContent);
 */


$serv = new swoole_http_server("192.168.10.119", 9502);


$serv->on('Request', function($request, $response) {
    $content = isset($request->get['content']) ? $request->get['content']: '';
//    $content="filter test";
//加载关键词库
    $file=trie_filter_load(__DIR__ . '/blackword.tree');
//查找
    $getword=trie_filter_search_all($file, $content);

    foreach ($getword as $v){
        $str[]=substr($content,$v[0],$v[1]);
    }
    $result =replace_tie($str,$content);
//    $result=implode(',',$str);

    file_put_contents(__DIR__ . '/ab.xtx',implode(',',$str).date('Y-m-d H:i:s').PHP_EOL,FILE_APPEND);
    trie_filter_free($file);
    $response->cookie("User", "Swoole");
    $response->header("X-Server", "Swoole");
    $response->header('Content-Type', 'Content-Type: text/html; charset=utf-8');
    $response->end("$result");
});

$serv->start();





function replace_tie($array,$content){
    $badword1 = array_combine($array,array_fill(0,count($array),'*'));
    $str = strtr($content, $badword1);
    return $str;
}



