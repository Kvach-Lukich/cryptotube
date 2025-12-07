<?php
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/utils.php');

array_shift($argv);

if($argc>4){
    throw new Exception('to many arg', 401);
}

foreach($argv as $val){
    if($val=='-l'){
        $local=true;
    }elseif(is_file($val)){
        $farchive=$val;
    }elseif(strtolower(substr($val,0,4))=='http'){
        $url['url']=$val;
    }
}

if(!$farchive){
    $farchive=file_get_contents('file.txt');
}

if(!$farchive){
    throw new Exception('no file specified', 404);
}

if(!file_exists($farchive)){
    throw new Exception('no file', 402);
}

$farchive=trim($farchive);
$farchive=str_replace('"','',$farchive);

$iarchive=pathinfo($farchive);
$iarchive['fullpath']=$farchive;
$iarchive['size']=filesize($farchive);
define("PATHINFO",$iarchive);
unset($farchive,$iarchive);

if(!$url['url']){
    $url['url']=trim(file_get_contents('url.txt'));
}

parse_str(parse_url($url['url'], PHP_URL_QUERY), $url_params);
$url['id']=$url_params['v'];
define("URLINFO",$url);

$fp=fopen(PATHINFO['fullpath'],'rb');
if($local){
    $in_video='"'.glob("*-{$title}-*.mp4")[0].'"';
    if(!$in_video){
        $in_video='"'.glob("{$title} *.mp4")[0].'"';
    }
    $cmd=ffcomand($in_video, '-', CNF['ffmpeg_read']);
}else{
    $ffmpeg=ffcomand('-', '-', CNF['ffmpeg_read']);
    $cmd=CNF['yt-dlp_download'].URLINFO['url'].' | '.$ffmpeg;
}
print_r($cmd); echo "\n";

$rproc = popen($cmd, 'rb');
$buffer='';
$bn=0;

while (!feof($fp)) {
    $kn++;
    $cadr='';
    $file = fread($fp, CNF['bytes_count_infarame']);
    $bl=strlen($file);
    
    while (strlen($buffer)<CNF['bytes_count_infarame'] && !feof($rproc)) {
        $bytesNeeded = CNF['bytes_count_infarame'] - strlen($buffer);
        $chunk=fread($rproc,$bytesNeeded);
        $buffer .= $chunk;
    }
    $yt_cadr=substr($buffer,0,CNF['bytes_count_infarame']);
    $buffer=substr($buffer,CNF['bytes_count_infarame']);
    if($bn<PATHINFO['size']){
        for($n=0;$n<CNF['bytes_count_infarame'];$n++){
            if($yt_cadr[$n]!=$file[$n]){
                print_r([$kn,$bn,ord($file[$n]),ord($yt_cadr[$n])]);
                $log[$bn]=ord($file[$n]);
                $ytarc.=pack40($bn).$file[$n];
            }
            $bn++;
            if($bn==PATHINFO['size']) break;
        }
    }
}
if($bn<PATHINFO['size']){
    if(!$local){
        throw new Exception('yt-dlp can\'t download video', 501);
    }else{
        throw new Exception('wrong video', 502);
    }
}

$ytarc=pack40(PATHINFO['size']).$ytarc;
file_put_contents(PATHINFO['filename'].'_bytes.log',var_export([PATHINFO['size'],$log,PATHINFO['basename']],true));
file_put_contents(PATHINFO['filename'].'.ytkey',$ytarc);
$datalog=[PATHINFO['size'],$log,PATHINFO['basename']];
$brlog=execute(CNF['brotli_pack'],json_encode($datalog,JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE));
$ytlog=hindoo_encod($brlog);

file_put_contents(PATHINFO['filename'].'_description.txt',$ytlog);

pclose($rproc);
fclose($fp);
echo "\nDone!\n";