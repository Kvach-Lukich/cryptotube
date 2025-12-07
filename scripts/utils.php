<?php
function execute($cmd, $stdin=null, $enableout=true){
    $descriptors = [
        0 => ['pipe', 'rb'],  // stdin
        1 => ['pipe', 'wb'],  // stdout
        2 => ['pipe', 'wb']   // stderr
    ];

    $process = proc_open($cmd , $descriptors, $pipes);
    
    if($stdin){
        fwrite($pipes[0], $stdin);
        fclose($pipes[0]);
    }
    if($enableout){
        $stdout=stream_get_contents($pipes[1]);
        fclose($pipes[1]);
    }
    proc_close($process);
    return $stdout;
}

function pack40($n) {
    return chr(($n >> 32) & 0x01) . pack("N", $n & 0xFFFFFFFF);
}

function unpack40($str) {
    $high = ord($str[0]) & 0x01;
    $low = unpack("N", substr($str, 1, 4))[1];
    return ($high << 32) | $low;
}

define("INDUSI", 
['ഀ','ഁ','ം','ഃ','ഄ','അ','ആ','ഇ','ഈ','ഉ','ഊ','ഋ','ഌ','എ','ഏ','ഐ','ഒ','ഓ','ഔ','ക','ഖ','ഗ','ഘ','ങ','ച','ഛ','ജ','ഝ','ഞ','ട','ഠ','ഡ','ഢ','ണ','ത','ഥ','ദ','ധ','ന','ഩ','പ','ഫ','ബ','ഭ','മ','യ','ര','റ','ല','ള','ഴ','വ','ശ','ഷ','സ','ഹ','ഺ','഻','഼','ഽ','ാ','ി','ീ','ു','ൂ','ൃ','ൄ','൅','െ','േ','ൈ','ൊ','ോ','ൌ','്','ൎ','൏','ൔ','ൕ','ൖ','ൗ','൘','൙','൚','൛','൜','൝','൞','ൟ','ൠ','ൡ','ൢ','ൣ','൦','൧','൨','൩','൪','൫','൬','൭','൮','൯','൰','൱','൲','൳','൴','൵','൶','൷','൸','൹','ൺ','ൻ','ർ','ൽ','ൾ','ൿ','ଁ','ଂ','ଃ','ଅ','ଆ','ଇ','ଈ','ଉ','ଊ','ଋ','ଌ','ଏ','ଐ','ଓ','ଔ','କ','ଖ','ଗ','ଘ','ଙ','ଚ','ଛ','ଜ','ଝ','ଞ','ଟ','ଠ','ଡ','ଢ','ଣ','ତ','ଥ','ଦ','ଧ','ନ','ପ','ଫ','ବ','ଭ','ମ','ଯ','ର','ଲ','ଳ','ଵ','ଶ','ଷ','ସ','ହ','଼','ଽ','ା','ି','ୀ','ୁ','ୂ','ୃ','ୄ','େ','ୈ','ୋ','ୌ','୍','୕','ୖ','ୗ','ଡ଼','ଢ଼','ୟ','ୠ','ୡ','ୢ','ୣ','୦','୧','୨','୩','୪','୫','୬','୭','୮','୯','୰','ୱ','୳','୴','୶','୷','ઁ','ં','ઃ','઄','અ','આ','ઇ','ઈ','ઉ','ઊ','ઋ','ઌ','ઍ','એ','ઐ','ઑ','ઓ','ઔ','ક','ખ','ગ','ઘ','ઙ','ચ','છ','જ','ઝ','ઞ','ટ','ઠ','ડ','ઢ','ણ','ત','થ','દ','ધ','ન','પ','ફ','બ','ભ','મ','ય','ર','લ','ળ','વ']
);
function hindoo_encod($data, $auto=true){
    $len=strlen($data);
    $brl=60;
    $brd=7;
    $br=[];
    if($auto){
        $brn=intdiv($len,$brl);
        for($i=0;$i<$brn;$i++){
            $br[ rand($i*$brl-$brd,$i*$brl+$brd) ]="\n";
        }
    }
    for($i=0;$i<$len;$i++){
        $txt.=INDUSI[ord($data[$i])].$br[$i];
    }
    return $txt;
}
function hindoo_decod($text, $auto=true){
    if($auto){
        $text=str_replace("\n",'', $text);
    }
    $len=mb_strlen($text);
    $revers=array_flip(INDUSI);
    for($i=0;$i<$len;$i++){
        $data.=chr($revers[mb_substr($text,$i,1) ]);
    }
    return $data;
}
