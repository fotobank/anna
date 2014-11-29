<?php
/****************************************************************
*****************************************************************

protect the path of your pictures

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
 * @shortdesc help in building form tag
 * under GPL licence
 * latest version can be download at : http://www.phplibrairies.com
 *
 * @author      Matthieu MARY 
 * @version     1.5.0
 * @date      october 2nd 2003
 **/
 
require_once 'class_cache.php';

class protect_picture
{
	/**
	 * the session id
	 * @private
	 **/
	var $sSessionId;
    /**
     * Builder
     *
     * @param string -sSessionId ; the session id
     * @type void
     * @public
     **/
    function protect_picture($sSessionId)
    {
        unset($_SESSION['class_protect_picture']);
        $this->sSessionId = $sSessionId;
        ob_start();
    }

    /**
     * Protect picture path
     *
     * @type void
     * @public
     **/
    function protect()
    {
        // initialise var
        $contents = ob_get_contents();
        $path = [];
        $temp = '';
        $matches = array();

        // get pictures path
        $pattern = '/<(img|input)\s+[^>]*src\s*=\s*(?:"([^"]+)"|\'([^\']+)\'|([^\s>]+))/is';
        if (preg_match_all($pattern, $contents, $matches)>0)
        {
            for($i=0;$i<count($matches[2]);$i++)
            {
               $temp = $matches[2][$i].$matches[3][$i].$matches[4][$i];
               if(preg_match("/^(https?|ftp):\\/\\//i",$temp)==0) $path[] = $temp;

            }
			$path = array_unique($path);

            // replace picture path
            if (ini_get('session.save_handler') == 'files')
			{
            	foreach($path as $i => $v) $contents = str_replace($v,'/protect_picture.php?id='.$i,$contents);
				$_SESSION['class_protect_picture'] = $path;
			}
			else{
				$cache = new cache(ini_get('max_execution_time'),'/cache/class_protect_picture');
				$cache->saveInCache(implode("||",$path),$this->sSessionId);
				foreach($path as $i => $v) $contents = str_replace($v,'/protect_picture_cache.php?id='.$i,$contents);
			}
		}
        ob_end_clean();
        echo $contents;
	}
}