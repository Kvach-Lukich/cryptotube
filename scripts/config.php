<?php
$config=[
    'ffmpeg_path'=>'bin\\ffmpeg.exe ',
    'ffmpeg_banner'=>'-hide_banner -loglevel error -stats ',
    'ffmpeg_read'=>"-hwaccel d3d11va -y -i input_name -vf \"fps=1,lut=if(gt(val\,127)\,0\,255)\" -pix_fmt monob -an -f rawvideo output_name",
    'ffmpeg_write'=>"-y -f image2pipe -vcodec pbm -r 1 -i - -vf format=gray -pix_fmt yuv420p -c:v hevc_nvenc -preset slow -profile main -rc constqp -qp 26 -bufsize 200M -an output_name",
    'yt-dlp_path'=>'bin\\yt-dlp.exe ',
    'brotli_path'=>"bin\\brotli.exe ",
    'node'=>"bin\\nodejs\\node.exe",
    'browser'=>"firefox",
    'profile_path'=>'profile',
    'w4k'=>1920,
    'h4k'=>1080,
    'quality'=>137
];

$config['frame_len']=$config['w4k']*$config['h4k'];
$config['bytes_count_infarame']=$config['frame_len']>>3;
$config['pbm_head']="P4\n".$config['w4k'].' '.$config['h4k']."\n";
$config['pbm_head_len']=strlen($config['pbm_head']);

$config['yt-dlp_download']=$config['yt-dlp_path']." --cookies-from-browser \"".$config['browser'].":".realpath('.')."\\".$config['profile_path']."\" --js-runtimes \"node:".realpath('.').'\\'.$config['node']."\" --extractor-args \"youtube:youtubejs:js_engine=node\" -f bestvideo -o - ";
$config['yt-dlp_info']=$config['yt-dlp_path']." --cookies-from-browser \"".$config['browser'].":".realpath('.').'\\'.$config['profile_path']."\" -j ";

$config['brotli_pack']=$config['brotli_path'].' -Zcf';

$config['ffmpeg_read']=$config['ffmpeg_path'].$config['ffmpeg_banner'].$config['ffmpeg_read'];
$config['ffmpeg_write']=$config['ffmpeg_path'].$config['ffmpeg_banner'].$config['ffmpeg_write'];

if (PHP_OS_FAMILY === 'Windows') {
    $config['cores']=intval(getenv('NUMBER_OF_PROCESSORS'));
}

define("CNF",$config);

function ffcomand($input_name,$output_name,$ffmpeg){
    if(!$input_name){
        return str_replace('output_name', $output_name,$ffmpeg);
    }else{
        return str_replace(['input_name','output_name'], [$input_name,$output_name],$ffmpeg);
    }
}


