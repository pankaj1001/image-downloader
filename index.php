<?php

if($_POST['word']){

$tags = array();
function curlget($param){

$ch = curl_init();
curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
curl_setopt($ch,CURLOPT_BINARYTRANSFER,TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_URL,$param);

$results  = curl_exec($ch);
curl_close($ch);
if (!empty($results)){
  return $results;
}else return "no";
}

function pagedom($par){
  $pgdom = new DOMDocument();
  @$pgdom->loadHTML($par);
  $xmlpgpath = new DOMXPath($pgdom);

  return $xmlpgpath;
}

$text = $_POST['word'];
$text = rawurlencode(basename($text));

$url = 'https://www.pexels.com/search/'.$text.'/';
//echo $url;
$pagesource = curlget($url);
//echo $pagesource;
$pagepath = pagedom($pagesource);
$tags = $pagepath->query('//a[@class="js-photo-link"]');
 for($i=0;$i<$tags->length;$i++) {
   $l = $tags->item($i)->getAttribute('href');
   //echo '<p>'.$l.'<p>';
   $newurl = 'https://www.pexels.com'.$l;
   $pagesource2 = curlget($newurl);
   $pagepath2 = pagedom($pagesource2);
   $imagedata = $pagepath2->query('//a[@class="btn__primary js-download"]');
   for($j=0;$j<$imagedata->length;$j++){
     $finallink = $imagedata->item($j)->getAttribute('href');
     $imagename = $imagedata->item($j)->getAttribute('data-id');
     //echo '<p>'.$finallink.'<p>'.$imagename;
     if(getimagesize($finallink)){
       $imagefile = curlget($finallink);
       $file = fopen($imagename.'.jpg','w');
       fwrite($file,$imagefile);
       fclose($file);
     }
   }

 }




echo 'Hip Hip Hurray! Done your Work.';

//$find = $pagepath->query('//div[@class="btn-primary btn--lg btn--splitted"]');
//echo $find;
}
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
<form class="" action="index.php" method="post">
  <input type="text" name="word" value=""/>
  <button type="submit" name="button">Submit</button>
</form>
  </body>
</html>
