<?php
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/utils.php');

if($argv[1]){
    $farchive=$argv[1];
}else{
    $farchive=file_get_contents('file.txt');
}
$farchive=trim($farchive);
$farchive=str_replace('"','',$farchive);

$iarchive=pathinfo($farchive);
$iarchive['fullpath']=$farchive;
$iarchive['size']=filesize($farchive);
define("PATHINFO",$iarchive);
unset($farchive,$iarchive);

$fp=fopen(PATHINFO['fullpath'],'rb'); 
$ffmpeg=ffcomand('-', '"'.PATHINFO['filename'].'.mp4"', CNF['ffmpeg_write']);
$proc = popen($ffmpeg, 'wb');
$kn=0;
while (!feof($fp)) {
    $cadr=fread($fp, CNF['bytes_count_infarame']);
    if(strlen($cadr)<CNF['bytes_count_infarame']){
        $cadr=str_pad($cadr,CNF['bytes_count_infarame'],"\x00");
    }
    fwrite($proc, CNF['pbm_head'].$cadr, CNF['bytes_count_infarame']+CNF['pbm_head_len']);
    $kn++;
}
pclose($proc);
fclose($fp);

echo "\nDone!\n";