<?php
ini_set('memory_limit', "10G");
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/utils.php');

array_shift($argv);
if($argc>3){
    throw new Exception('to many arg', 401);
}
foreach($argv as $val){
    if($val=='-l'){
        $local=true;
    }elseif(strtolower(substr($val,0,4))=='http'){
        $url['url']=$val;
    }
}
if(!$url['url']){
    $url['url']=trim(file_get_contents('url.txt'));
}

parse_str(parse_url($url['url'], PHP_URL_QUERY), $url_params);
$url['id']=$url_params['v'];
define("URLINFO",$url);

print_r(CNF['yt-dlp_info'].URLINFO['url']); echo "\n";
$ytdescription=json_decode(execute(CNF['yt-dlp_info'].URLINFO['url'],null,true),true);
$title=$ytdescription['title'];
$description=$ytdescription['description'];
unset($ytdescription);

$ytlog=hindoo_decod($description);
$brotli="bin\brotli.exe -dcf";
$ytlog=execute($brotli,$ytlog);
$ytlog=json_decode($ytlog,true);
$ytarc=$ytlog[1];
$fz=$ytlog[0];
$basename=$ytlog[2];

//$ytdlp=CNF['yt-dlp']." --cookies-from-browser \"firefox:".realpath('.').'\\'.CNF['profile_path']."\" --js-runtimes \"node:".realpath('.').'\\'.CNF['node']."\" --extractor-args \"youtube:youtubejs:js_engine=node\" -f bestvideo -o - ".URLINFO['url'];

if($local){
    $in_video='"'.glob("*-{$title}-*.mp4")[0].'"';
    if(!$in_video){
        $in_video='"'.glob("{$title} *.mp4")[0].'"';
    }
    $cmd=ffcomand($in_video, '"'.$basename.'"', CNF['ffmpeg_read']);
}else{
    $ffmpeg=ffcomand('-', '"'.$basename.'"', CNF['ffmpeg_read']);
    $cmd=CNF['yt-dlp_download'].URLINFO['url'].' | '.$ffmpeg;
}

print_r($cmd); echo "\n";
exec($cmd);

if(filesize($basename)<$fz){
    if(!$local){
        throw new Exception('yt-dlp can\'t download video', 501);
    }else{
        throw new Exception('wrong video', 502);
    }
}

$fp=fopen($basename, "r+b");
foreach($ytarc as $nb=>$byte){
    fseek($fp,$nb);
    fwrite($fp,chr($byte),1);
}
ftruncate($fp,$fz);
fclose($fp);

echo "\nDone!\n";