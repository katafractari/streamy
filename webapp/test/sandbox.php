<?php
/**
 * Stream manager sandbox
 *
 * @author Me
 * @version $Id$
 * @copyright Me, 17 May, 2011
 * @package default
 **/

if(($addr = inet_pton(".254.254.2")) != FALSE)
{
	echo "TRUE";
}
else
{
	echo "FALSE";
}
?>
