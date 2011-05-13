<?php

# icy test
#$url = "http://blank-tv.de.streams.bassdrive.com:8000";
$host = "213.202.250.90";
$port = "8000";

if(($fp = fsockopen($host, $port)))
{
	$mpeg = false;
	$ogg = false;
	$http_header_end = false;
	$icy = false;

	$req = <<< EOF
GET / HTTP/1.1
User-Agent: PHP/".phpversion()."
Host: $host:$port
Icy-MetaData: 1
Accept: */*


EOF;
			
    fputs($fp, $req);

	$i=0;
	$icy_metaint = false;
	$buffer = "";
	while(!feof($fp))
	{
		if(!$http_header_end)
		{
			$line = fgets($fp);
			$buffer .= $line;
		}

		if($mpeg && $http_header_end && is_numeric($icy))
		{
			echo "tag: (".ftell($fp).")\n";
			$header_length = ftell($fp);
			
			while(($content = fread($fp, 1024)))
			{
				if((ftell($fp) - $header_length) < $icy)
				{
					echo "\n".ftell($fp)."\n";
				}
				else
				{
					break;
				}
			}
			$size = unpack("C", fread($fp, 1));
			$size = $size[1] * 15;
			if($size > 0)
			{
				$metadata_buffer = fread($fp, $size);
				//echo "-".$metadata_buffer."-\n";
				if(preg_match("/StreamTitle='(.+?)'/i", $metadata_buffer, $matches))
				{
					$title = $matches[1];
					echo $title;
				}
				//echo "size: ".print_r($size);
				//echo "tag: (".ftell($fp)."): '".$size."'\n";
			}
			break;
		}

		// If the response is type 200 then continue, extract the header
		if(preg_match("/^[\r]?$/", $line))
		{
			echo "header-end";
			$http_header_end = true;
			continue;
		}

		if(strpos($line, " OK") > strpos($line, " 20"))
		{
			continue;
		}
		if((list($name, $value) = split(": ?", $line, 2)))
		{
			if(preg_match("/content-type/i", $name))
				if(preg_match("/audio\/mpeg/i", $value))
				{
					echo $name." = ".$value;
					$mpeg = true;
					continue;
				}
			if(preg_match("/icy-metaint/i", $name))
			{
				if(preg_match("/.*1.*/", $value, $match))
				{
					echo "icy match\n";
					echo $match[0]."\n";
					$icy = trim($match[0]);
					continue;
				}
			}
		}
	}
	fclose($fp);
}
echo $i;
exit;
# some random test cases :-)

$canvas = new Imagick();

$text = $_GET['t'];
if(empty($text)){
	$text = "ERROR";
}

// Sanitize the input from a GET.  Strip out any non-word
// characters and other junk.  If it's not a word-character
// or a space, then remove it from the string.
$text = preg_replace("/[^\S ]/","",$text);

// Limit the size of the string to 20-chars.
$text = substr($text, 0, 20);

$draw = new ImagickDraw();

// I placed arial.ttf (TrueType Font file) in the same
// directory of the script.  ImageMagick uses this file
// to annotate the image with text.

$draw->setFont("monospace");
$draw->setFontSize(10);
$draw->annotation(25,25,$text);

$canvas->newImage(155,155,"#E1EFF9");

// Draw the image onto the canvas.
$canvas->drawImage($draw);

// Rotate the image

// I want a PNG
$canvas->setImageFormat('png');

header("Content-Type: image/png");
echo $canvas;
?>
