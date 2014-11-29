<?php
/****************************************************************
*****************************************************************

Copyright (C) 2003  Matthieu MARY http://www.phplibrairies.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

You can found more information about GPL licence at:
http://www.gnu.org/licenses/gpl.html

for contact me: http://www.phplibrairies.com
****************************************************************
****************************************************************/
/**
 * @shortdesc tools for making easy query on google search browser
 * under GPL licence
 * latest version can be download at : http://www.phplibrairies.com
 *
 * @author      Matthieu MARY &lt;<a href="http://www.phplibrairies.com">http://www.phplibrairies.com</a>&gt;
 * @version     1.0.1
 * @date      February 02th 2004
 **/
class cache
{
	/**
     * @shortdesc : current cache directory filename
	 * @type string
     * @private
     **/
	var $cacheDirectory;
	/**
     * @shortdesc : period of cache
	 * @type int
     * @private
     **/
	var $cacheDuration;
	
	/**
     * @shortdesc : current cache filename
	 * @type string
     * @private
     **/
	var $cacheFilename;

	/**
     * @shortdesc : builder
     *
	 * builder
	 * @param int -cacheDuration : period of cache
	 * @param string -cacheDirectory : the cache directory folder
	 * @type void
     * @public
     **/
	function cache($cacheDuration=86400,$cacheDirectory='./cache')
	{
		$this->cacheDuration = 0;
		$this->cacheFilename = '';
		$this->cacheDirectory = '.';
		$this->updateCache($cacheDuration,$cacheDirectory);
	}

	/**
     * @shortdesc : create the cache folder
     *
	 * create the cache folder
	 * @type void
     * @private
     **/
	function _makeCacheFolder()
	{
        if (!is_dir($this->cacheDirectory))
		{
					// create the cache folder
			$temp = explode('/',$this->cacheDirectory);
			$cur_dir = '';
			for($i=0;$i<count($temp);$i++)
			{
				$cur_dir .= $temp[$i].'/';
				if (!is_dir($cur_dir))
				{
					// PROTECT the current cache folder
					if (@mkdir($cur_dir)&&($cur_dir!=getcwd()))
					{
						 $this->_writeFile($cur_dir.'.htaccess','Deny from all');
            			 $this->_writeFile($cur_dir.'index.html','');
					}
				}
			}
		}
		
	}

	/**
     * @shortdesc : get the current cache filename
     *
	 * get the current cache filename
	 * @type string
     * @public
	 *
	 * @return string
	 */
	function getCacheFilename()
	{
		return $this->cacheFilename;
	}

	/**
     * @shortdesc : set the current cache filename
     *
	 * set the current cache filename
	 * @param string /contents : the contents to make the cache filename
	 * @type void
     * @private
     **/
	 function _setCacheFilename($contents)
	 {
        $this->cacheFilename = $this->cacheDirectory.'/'.md5($contents).'.txt';
	 }

	/**
     * @shortdesc : check if the contents is in the cache folder
     *
	 * check if the contents is in the cache folder by generating the cache filename
	 * and checking if the cachefilename already exist
	 * @param string /contents : the contents to make the cache filename
	 * @type bool
     * @public
     **/
	/**
	 * @param $contents
	 *
	 * @return bool
	 */
	function inCache($contents)
	 {
	 	$this->_setCacheFilename($contents);
		return file_exists($this->cacheFilename);
	 }

	/**
     * @shortdesc : read the cache
     *
	 * read the cache
	 * @type string
     * @public
     **/
	/**
	 * @return string
	 */
	function readCache()
	 {
	 	$contents = '';
	 	$fp = @fopen($this->cacheFilename,'r');
		if ($fp)
		{
			while(!feof($fp)) $contents .= fread($fp,4096);
			fclose($fp);
		}
		return $contents;
	 }
	 
	/**
     * @shortdesc : update cache values
     *
	 * pdate cache values
	 * @param int -cacheDuration : the cache validity period (in seconds)
	 * @param string -cacheFolder : the cache folder
	 * @type void
     * @public
     **/
	/**
	 * @param int    $cacheDuration
	 * @param string $cacheFolder
	 */
	function updateCache($cacheDuration=86400,$cacheFolder='./cache')
	{
		$this->cacheDuration = $cacheDuration;
		$this->cacheDirectory = $cacheFolder;
        $this->_makeCacheFolder();
	}
	
	/**
     * @shortdesc : save datas in cache Filename
     *
	 * save datas in cache Filename
	 * @param string -contents : save contents in cache
	 * @param string -filename : the cache key. If not specified, it's the string to save
	 * @type void
     * @private
     **/
	/**
	 * @param        $contents
	 * @param string $filename
	 */
	function saveInCache($contents,$filename='')
	 {
	        if (trim($filename)=='') $filename = $contents;
			// update the cache?
			if ($this->inCache($filename)&&((filectime($this->cacheFilename)-time())>$this->cacheDuration))
			{
				@unlink($this->cacheFilename);
			}
			$this->_writeFile($this->cacheFilename,$contents);
	 }

	/**
     * @shortdesc : write a file
     *
	 * write a file
	 * @param string -filename : filepath to write
	 * @param string -contents : contents file
	 * @type void
     * @private
     **/
	/**
	 * @param $filename
	 * @param $contents
	 */
	function _writeFile($filename,$contents)
	 {
	 	if (!file_exists($file))
	 	{
	 		$fp = @fopen($filename,'w');
			 if ($fp)
	 		{
				fputs($fp,$contents);
				fclose($fp);
	 		}
	 	}
	 }

}
?>