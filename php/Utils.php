<?php

/**
 * Utility class
 *
 * @author Me
 * @version 0
 * @copyright Me, 01 May, 2011
 * @package streammanager
 * */
class Utils {

		/**
		 * Include a template from separate file
		 *
		 * @return void
		 * @author Me
		 * */
		public static function includeTemplate($templateName) {
				if (strlen($templateName) > 0)
						include("templates/$templateName.php");
		}

		/**
		 * Extract host + port
		 *
		 * @return void
		 * @author Me
		 * */
		public static function parseURL($url) {
				
		}

		/**
		 * Validate output name
		 *
		 * @return true or parser error msg
		 * @author Mue
		 * */
		public static function validateOutputName($name) {
				return (strlen($name) < 32 && strlen($name) > 0) ? true : "Name must be between 1 to 32 characters";
		}

		/**
		 * Validate output hostname
		 * Must be either an IPv4 or an IPv6 address
		 * @return true or parser error msg
		 * @author Me
		 * */
		public static function validateOutputHostname($hostname) {
				$ret = "Invalid hostname.";

				if (preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $hostname)) {
						$ret = TRUE;
				} else if (preg_match("/^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$/", $hostname)) {
						$ret = TRUE;
				}

				return $ret;
		}

		/**
		 * Validate stream URL
		 *
		 * @return string with error description or array with match
		 * @author Me
		 * */
		public static function validateURL($url) {
				$rt =
						preg_match(
						'/^http[s]?:\/\/([a-z0-9-.]+)(:[0-9]+)?.*$/i', $url, $matches
				);
				if ($rt > 0) {
						$data = array();
						if (sizeof($matches) >= 2) {
								$data = array(
									"url" => array(
										"host" => $matches[1],
										"port" => $matches[2]
									)
								);
								if (sizeof($matches) == 3) {
										$data['url']['port'] = substr($matches[2], 1);
								}

								return $data;
						}
				}
				return "Invalid URL.";
		}

		/**
		 * Validate a stream Name (32 chars max)
		 *
		 * @return void
		 * @author Me
		 * */
		public static function validateStreamName($name) {
				return (strlen($name) < 32 && strlen($name) > 0) ? true : "Name must be between 1 to 32 characters";
		}

		/**
		 * Get metadata about currently player stream (no ogg support at the moment)
		 *
		 * @return array with metadata or FALSE
		 * @author Me
		 * */
		public static function getStreamTitle($url) {
				$ret = FALSE;

				// Extract hostname and port from URL and throw an error if the URL is
				// malformed
				if (($urlData = parse_url($url)) == FALSE)
						return $ret;
				if (strlen($urlData['host']) < 1 && !is_numeric($urlData['port']))
						return $ret;

				// Connect to server
				$hostname = $urlData['host'];
				$port = $urlData['port'];
				if (@strlen($urlData['path']) > 0) {
						$stream = $urlData['path'];
						if (@strlen($urlData['query']) > 0) {
								$stream .= "?" . $urlData['query'];
						}
				} else {
						$stream = "/";
				}

				if ($fp = fsockopen($hostname, $port)) {
						$mpeg = false;
						$ogg = false;
						$http_header_end = false;
						$icy = false;

						// $phpVersion = phpversion();
						$req = <<< EOF
GET $stream HTTP/1.u1
User-Agent: Stream Manager
Host: $hostname:$port
Icy-MetaData: 1
Accept: */*


EOF;

						fputs($fp, $req);

						$i = 0;
						$icy_metaint = false;
						$buffer = "";
						while (!feof($fp)) {
								if (!$http_header_end) {
										$line = fgets($fp);
										$buffer .= $line;
								}

								if ($mpeg && $http_header_end && is_numeric($icy)) {
										//echo "tag: (".ftell($fp).")\n";
										$header_length = ftell($fp);
										//echo "header_length: ".$header_length."\n";

										while (($content = fread($fp, 1024))) {
												if ((ftell($fp) - $header_length) < $icy) {
														//echo "\n".ftell($fp)."\n";
														if ((ftell($fp) - $header_length + 1024) > $icy)
																break;
												}
												else {
														break;
												}
										}
										if (ftell($fp) < $icy)
												fread($fp, $icy - ftell($fp));
										//echo "ftell ".ftell($fp)."\n";
										$size = unpack("C", fread($fp, 1));
										$size = $size[1] * 15;
										if ($size > 0) {
												$metadata_buffer = fread($fp, $size);
												//echo "-".$metadata_buffer."-\n";
												if (preg_match("/StreamTitle='(.+?)'/i", $metadata_buffer, $matches)) {
														$title = $matches[1];
														//echo "title: ".$title;
														$ret = $title;
												}
												//echo "size: ".print_r($size);
												//echo "tag: (".ftell($fp)."): '".$size."'\n";
										}
										break;
								}

								// If the response is type 200 then continue, extract the header
								if (preg_match("/^[\r]?$/", $line)) {
										//echo "header-end";
										$http_header_end = true;

										if (!is_numeric($icy))
												break;

										continue;
								}

								if (strpos($line, " OK") > strpos($line, " 20")) {
										continue;
								}

								if (preg_match("/:/", $line)) {
										if ((list($name, $value) = split(": ?", $line, 2))) {
												if (preg_match("/content-type/i", $name))
														if (preg_match("/audio\/mpeg/i", $value)) {
																// print $name." = ".$value;
																$mpeg = true;
																continue;
														}
												if (preg_match("/icy-metaint/i", $name)) {
														if (preg_match("/.*1.*/", $value, $match)) {
																//echo "icy match\n";
																//echo $match[0]."\n";
																$icy = trim($match[0]);
																continue;
														}
												}
										}
								}
						}
						fclose($fp);
				}

				return $ret;
		}

}

// Testing
// Parse cli arguments to get variables

//@parse_str($argv[1], $_GET);
//if (strlen($argv[2]) > 0)
//		$url = $argv[2];
//if (@$_GET['flag'] === "debug") {
//		if ($title = Utils::getStreamTitle($url))
//				print $title . "\n";
//}
?>
