<?php
// 设置内存
ini_set('memory_limit', '128M');

// 读取敏感词字典库
$handle = fopen('word.txt', 'r');

// 生成空的trie-tree-filter
$resTrie = trie_filter_new();

while(! feof($handle)) {
    $item = trim(fgets($handle));

    if (empty($item)) {
        continue;
    }

    // 把敏感词逐个加入trie-tree
    trie_filter_store($resTrie, $item);
}

// 生成trie-tree文件
$blackword_tree = __DIR__ . '/blackword.tree';

trie_filter_save($resTrie, $blackword_tree);
