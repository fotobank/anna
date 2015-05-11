<?php
 # prevent direct viewing of Security.php
 if ( false !== strpos( $_SERVER[ 'SCRIPT_NAME' ] , Alex_selfchk() ) ) send404();

/**
 * Class Security
 */
class Security {

   # protect from non-standard request types
   protected $_nonGETPOSTReqs = 1;
   # if open_basedir is not set in php.ini. Leave disabled unless you are sure about using this.
   protected $_open_basedir = 0;
   # ban attack ip address to the root /.htaccess file. Leave this disabled if you are hosting a website using TOR's Hidden Services
   protected $_banip = 0;
   # Correct Production Server Settings = 0, prevent any errors from displaying = 1, Show all errors = 2 ( depends on the php.ini settings of the webserver )
   protected $_quietscript = 1;
   # path to the root directory of the site, e.g /home/user/public_html
   protected $_doc_root = '';
   # default home page
   protected $_default = 'index.php';

	protected $_psec = '';
	protected $_bypassbanip = false;
	protected $_realIP = '0';
 	protected $_httphost = 0;
	protected $_currentVersion = '1.0.0';
 	protected $_threshold = false;
 	protected $_banreason = ''; //for testing purposes
 	protected $_htaccessfile ='';
 	protected $_rprefix = '';
 	protected $_file_stop = '';


	/**
	 *
	 */
	public function __construct() {
       
       $this->setVars();

       # if open_basedir is not set in php.ini then set it in the local scope
       if ( false !== ( bool )$this->_open_basedir ) {
		   $this->setOpenBaseDir();
	   }

       # prevent the version of php being discovered
       $this->x_secure_headers();

       # Shields Up
       $this->_REQUEST_SHIELD();
       $this->_QUERYSTRING_SHIELD();
       $this->_POST_SHIELD();
       $this->_COOKIE_SHIELD();
       $this->_REQUESTTYPE_SHIELD();
       $this->_SPIDER_SHIELD();

       # merge $_REQUEST with _GET and _POST excluding _COOKIE data
       $_REQUEST = array_merge( $_GET, $_POST );

   }


/**
    * setVars()
    *
    */
   function setVars() {

	  $this->_file_stop = '/../../424.php';
      $this->_set_error_level();

      # make sure $_SERVER[ 'REQUEST_URI' ] is set
      $this->setReq_uri();

      $this->_currentVersion = '1.0.0';
      # reliably set $PHP_SELF
      global $PHP_SELF;

      # filter the $_SERVER ip headers for malicious code
      $this->_realIP = $this->getRealIP();
      $PHP_SELF = $this->getPHP_SELF();
      $this->_threshold = false;
      $this->_banreason = ''; //for testing purposes
      $this->_htaccessfile = $this->getHTAccesspath();
      $this->_bypassbanip = false;
      $this->_psec = 'Alex Security';
      $this->_rprefix = $this->_psec . '  detected ';
      # set the host address to be used in the email notification and htaccess
      $this->_httphost = preg_replace( "/^(?:([^\.]+)\.)?domain\.com$/", '\1', $_SERVER[ 'SERVER_NAME' ] );

   }
    
   function _set_error_level() {
       $val = ( false !== is_int( $this->_quietscript ) ) ? ( int )$this->_quietscript: 0;
       switch ( ( int )$val ) {
           case ( 0 ):
               error_reporting( 6135 );
               break;
           case ( 1 ):
               error_reporting( 0 );
               ini_set( 'display_errors', 0 );
               break;
           case ( 2 ):
               error_reporting( 32767 );
               ini_set( 'display_errors', 1 );
               break;
           default:
               error_reporting( 6135 );
       }
   }

   /**
    * send403()
    *
    */
	 function send403() {

       /*$header = array( 'HTTP/1.1 403 Access Denied', 'Status: 403 Access Denied by Hokioi-IT ' . $this->_psec, 'Content-Length: 0' );
       foreach ( $header as $sent ) {
           header( $sent );
       }*/

		 header( 'HTTP/1.1 403 Access Denied');
		 header( 'Content-Length: 0');
		 header( 'Location: '.$this->_file_stop, false , 307);  // 307 �.�. �� 403 ���� �� ���������

		 die ('403');
   }


	 /**
	  * @return string
	  */
	 function getHTAccesspath() {
       return $this->getDir() . '.htaccess';    
   }


	 /**
	  * @param bool $t
	  *
	  * @return bool|void
	  */
	 function karo( $t = false ) {

     if ( false === $this->byPass() ) return false;

     if ( $this->is_server( $this->getRealIP() ) )  $this->send403(); // never htaccess ban the server IP

     if ( ( false === ( bool )$t ) || ( false !== ( bool )$this->_bypassbanip ) ) {
         $this->send403();
     } else {
         if ( ( false !== $t ) &&
             ( false !== $this->hCoreFileChk( $this->_htaccessfile, TRUE, TRUE ) ) ) {
             if ( false !== ( bool )$this->_banip ) {
                $this->htaccessbanip( $this->_realIP );
             }
         }
     $this->send403();
     }
		 return true;
   }

   /**
    * injectMatch()
    *
	  * @param $string
	  *
	  * @return bool
	  */
	 function injectMatch( $string ) {

        $string = $this->url_decoder( $string );

        $kickoff = false;

        # these are the triggers to engage the rest of this function.
        $vartrig = "\/\/|\.\.\/|\.js|%0D%0A|0x|all|ascii\(|base64|benchmark|by|char|
                    column|convert|cookie|create|declare|data|date|delete|drop|concat|
                    eval|exec|from|ftp|grant|group|insert|isnull|into|length\(|load|
                    master|onmouse|null|php|schema|select|set|shell|show|sleep|table|
                    union|update|utf|var|waitfor|while";

        $vartrig = preg_replace( "/[\s]/", "", $vartrig );

        for( $x = 1; $x <= 5; $x++ ) {
           $string = $this->cleanString( $x, $string );
           if ( false !== ( bool )preg_match( "/$vartrig/i", $string ) ) {
              $kickoff = true;
              break;
           }
        }
        if ( false === $kickoff ) {
           return false;
        } else {
           $j = 1;
           # toggle through 6 different filters
           while( $j <= 6 ) {
              $string = $this->cleanString( $j, $string );
              $sqlmatchlist = "(?:abs|ascii|base64|bin|cast|chr|char|charset|
                  collation|concat|conv|convert|count|curdate|database|date|
                  decode|diff|distinct|elt|encode|encrypt|extract|field|_file|
                  floor|format|hex|if|inner|insert|instr|interval|join|lcase|left|
                  length|load_file|locate|lock|log|lower|lpad|ltrim|max|md5|
                  mid|mod|name|now|null|ord|password|position|quote|rand|
                  repeat|replace|reverse|right|rlike|round|row_count|rpad|rtrim|
                  _set|schema|select|sha1|sha2|sleep|serverproperty|soundex|
                  space|strcmp|substr|substr_index|substring|sum|time|trim|
                  truncate|ucase|unhex|upper|_user|user|values|varchar|
                  version|while|ws|xor)\(|\(0x|@@|cast|integer";
              $sqlmatchlist = preg_replace( "/[\s]/i", '', $sqlmatchlist );

              $sqlupdatelist = "\bcolumn\b|\bdata\b|concat\(|\bemail\b|\blogin\b|
                  \bname\b|\bpass\b|sha1|sha2|\btable\b|table|\bwhere\b|\buser\b|
                  \bval\b|0x|--";
              $sqlupdatelist = preg_replace( "/[\s]/i", '', $sqlupdatelist );
  
              if ( false !== ( bool )preg_match( "/\bdrop\b/i", $string ) &&
                   false !== ( bool )preg_match( "/\btable\b|\buser\b/i", $string ) &&
                   false !== ( bool )preg_match( "/--|and||\//i", $string ) ) {
                   return true;
              } elseif ( ( false !== strpos( $string, 'grant' ) ) &&
                         ( false !== strpos( $string, 'all' ) ) &&
                         ( false !== strpos( $string, 'privileges' ) ) ) {
                   return true;
              } elseif ( false !== ( bool )preg_match( "/(?:(sleep\((\s*)(\d*)(\s*)\)|benchmark\((.*)\,(.*)\)))/i", $string ) ) {
                   return true;
              } elseif ( false !== preg_match_all( "/\bload\b|\bdata\b|\binfile\b|\btable\b|\bterminated\b/i", $string, $matches ) > 3 ) {
                   return true;
              } elseif ( ( ( false !== ( bool )preg_match( "/select|isnull|declare|ascii\(substring|length\(/i", $string ) ) &&
                           ( false !== ( bool )preg_match( "/\band\b|\bif\b|group_|_ws|load_|concat\(|\bfrom\b/i", $string ) ) &&
                           ( false !== ( bool )preg_match( "/$sqlmatchlist/", $string ) ) ) ) {
                   return true;
              } elseif ( false !== preg_match_all( "/$sqlmatchlist/", $string, $matches ) > 2 ) {
                   return true;
              } elseif ( false !== strpos( $string, 'update' ) &&
                         false !== ( bool )preg_match( "/\bset\b/i", $string ) &&
                         false !== ( bool )preg_match( "/$sqlupdatelist/i", $string ) ) {
                   return true;
              # tackle the noDB / js issue
              } elseif ( ( substr_count( $string, 'var' ) > 1 ) &&
                           false !== ( bool )preg_match( "/date\(|while\(|sleep\(/i", $string ) ) {
                   return true;
              }

              # run through a set of filters to find specific attack vectors
              $thenode = $this->cleanString( $j, $this->getREQUEST_URI() );
              $sqlfilematchlist = 'access_|access.|\balias\b|apache|\/bin|
                   \bboot\b|config|\benviron\b|error_|error.|\/etc|httpd|
                   _log|\.(?:js|txt|exe|ht|ini|bat|log)|\blib\b|\bproc\b|
                   \bsql\b|tmp|tmp\/sess|\busr\b|\bvar\b|\/(?:uploa|passw)d';
              $sqlfilematchlist = preg_replace( "/[\s]/i", '', $sqlfilematchlist );
              if ( ( false !== ( bool )preg_match( "/onmouse(?:down|over)/i", $string ) ) &&
                   ( 2 < ( int )preg_match_all( "/c(?:path|tthis|t\(this)|http:|(?:forgotte|admi)n|sqlpatch|,,|ftp:|(?:aler|promp)t/i", $thenode, $matches ) ) ) {
                   return true;
              } elseif ( ( ( false !== strpos( $thenode, 'ftp:' ) ) &&
                         ( substr_count( $thenode, 'ftp' ) > 1 ) ) &&
                         ( 2 < ( int )preg_match_all( "/@|\/\/|:/i", $thenode, $matches ) ) ) {
                   return true;
              } elseif ( ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) &&
                         ( false !== ( bool )preg_match( "/(?:showimg|cookie|cookies)=/i", $string ) ) ) {
                   return true;
              } elseif ( ( substr_count( $string, '../' ) > 3 ) ||
                         ( substr_count( $string, '..//' ) > 3 ) ) {
                   if ( false !== ( bool )preg_match( "/$sqlfilematchlist/i", $string ) ) {
                      return true;
                   }
              } elseif ( ( substr_count( $string, '/' ) > 1 ) && ( 2 <= ( int )preg_match_all( "/$sqlfilematchlist/i", $thenode, $matches ) ) ) {
                      return true;
              } elseif ( ( false !== ( bool )preg_match( "/%0D%0A/i", $thenode ) ) &&
                         ( false !== strpos( $thenode, 'utf-7' ) ) ) {
                   return true;
              } elseif ( false !== ( bool )preg_match( "/php:\/\/filter|convert.base64-(?:encode|decode)|zlib.(?:inflate|deflate)/i",$string ) ||
                         false !== ( bool )preg_match( "/data:\/\/filter|text\/plain|http:\/\/(?:127.0.0.1|localhost)/i", $string ) ) {
                   return true;
              }
  
              if ( 5 <= substr_count( $string, '%' ) ) $string = str_replace( '%', '', $string );
  
              $sqlmatchlist = '@@|_and|ascii|b(?:enchmark|etween|in|itlength|
                  ulk)|c(?:ast|har|ookie|ollate|olumn|oncat|urrent)|\bdate\b|
                  dump|e(?:lt|xport)|false|\bfield\b|fetch|format|function|
                  \bhaving\b|i(?:dentity|nforma|nstr)|\bif\b|\bin\b|inner|insert|
                  l(?:case|eft|ength|ike|imit|oad|ocate|ower|pad|trim)|join|
                  m(:?ade by|ake|atch|d5|id)|not_like|not_regexp|null|\bon\b|
                  order|outfile|p(?:ass|ost|osition|riv)|\bquote\b|\br(?:egexp\b|
                  ename\b|epeat\b|eplace\b|equest\b|everse\b|eturn\b|ight\b|
                  like\b|pad\b|trim\b)|\bs(?:ql\b|hell\b|leep\b|trcmp\b|ubstr\b)|
                  \bt(?:able\b|rim\b|rue\b|runcate\b)|u(?:case|nhex|pdate|
                  pper|ser)|values|varchar|\bwhen\b|where|with|\(0x|
                  _(?:decrypt|encrypt|get|post|server|cookie|global|or|
                  request|xor)|(?:column|db|load|not|octet|sql|table|xp)_';
              $sqlmatchlist = preg_replace( "/[\s]/i", '', $sqlmatchlist );

              if ( ( false !== ( bool )preg_match( "/\border by\b|\bgroup by\b/i", $string ) ) &&
                   ( false !== ( bool )preg_match( "/\bcolumn\b|\bdesc\b|\berror\b|\bfrom\b|hav|\blimit\b|offset|\btable\b|\/|--/i", $string ) ||
                   ( false !== ( bool )preg_match( "/\b[0-9]\b/i", $string ) ) ) ) {
                   return true;
              } elseif ( ( false !== ( bool )preg_match( "/\btable\b|\bcolumn\b/i", $string ) ) &&
                           false !== strpos( $string, 'exists' ) &&
                           false !== ( bool )preg_match( "/\bif\b|\berror\b|\buser\b|\bno\b/i", $string ) ) {
                   return true;
              } elseif ( ( false !== strpos( $string, 'waitfor' ) &&
                             false !== strpos( $string, 'delay' ) &&
                             ( ( bool )preg_match( "/(:)/i", $string ) ) ) ||
                               ( false !== strpos( $string, 'nowait' ) &&
                                 false !== strpos( $string, 'with' ) &&
                               ( false !== ( bool )preg_match( "/--|\/|\blimit\b|\bshutdown\b|\bupdate\b|\bdesc\b/i", $string ) ) ) ) {
                   return true;
              } elseif ( false !== ( bool )preg_match( "/\binto\b/i", $string ) &&
                       ( false !== ( bool )preg_match( "/\boutfile\b/i", $string ) ) ) {
                   return true;
              } elseif ( false !== ( bool )preg_match( "/\bdrop\b/i", $string ) &&
                       ( false !== ( bool )preg_match( "/\buser\b/i", $string ) ) ) {
                   return true;
              } elseif ( ( ( false !== strpos( $string, 'create' ) &&
                             false !== ( bool )preg_match( "/\btable\b|\buser\b|\bselect\b/i", $string ) ) ||
                           ( false !== strpos( $string, 'delete' ) &&
                             false !== strpos( $string, 'from' ) ) ||
                           ( false !== strpos( $string, 'insert' ) &&
                           ( false !== ( bool )preg_match( "/\bexec\b|\binto\b|from/i", $string ) ) ) ||
                           ( false !== strpos( $string, 'select' ) &&
                           ( false !== ( bool )preg_match( "/\bby\b|\bcase\b|from|\bif\b|\binto\b|\bord\b|union/i", $string ) ) ) ) &&
                         ( ( false !== ( bool )preg_match( "/$sqlmatchlist/i", $string ) ) || ( 2 <= substr_count( $string, ',' ) ) ) ) {
                   return true;
              } elseif ( ( false !== strpos( $string, 'union' ) ) &&
                         ( false !== strpos( $string, 'select' ) ) &&
                         ( false !== strpos( $string, 'from' ) ) ) {
                   return true;
              } elseif ( false !== strpos( $string, 'etc/passwd' ) ) {
                   return true;
              } elseif ( false !== strpos( $string, 'null' ) ) {
                   $nstring = preg_replace( "/[^a-z]/i", '', $this->url_decoder( $string ) );
                   if ( false !== ( bool )preg_match( "/(null){3,}/i", $nstring ) ) {
                       return true;
                   }
              }
           $j++;
           }
        }
   return false;
   }

	/**
	 * blacklistMatch()
	 *
	 * @param     $val
	 * @param int $list
	 *
	 * @return bool
	 */
   private static function blacklistMatch( $val, $list = 0 ) {
     
     # $list should never have a value of 0
     if ( $list == 0 ) die( 'there is an error' );
     $_blacklist = [ ];
     $_blacklist[1] = "php\/login|eval\(base64\_decode|asc%3Deval|eval\(\\$\_|EXTRACTVALUE\(|
           allow\_url\_include|safe\_mode|suhosin\.simulation|disable\_functions|phpinfo\(|
           open\_basedir|auto\_prepend\_file|php:\/\/input|\)limit|rush=|fromCharCode|\}catch\(e|
           ;base64|base64,|onerror=prompt\(|onerror=alert\(|javascript:prompt\(|\/var\/lib\/php|
           javascript:alert\(|pwtoken\_get|php\_uname|%3Cform|passthru\(|sha1\(|sha2\(|\}if\(!|
           <\?php|\/iframe|\\$\_GET|=@@version|ob\_starting|and1=1|\.\.\/cmd|document\.cookie|
           document\.write|onload\=|mysql\_query|document\.location|window\.location|\]\);\}|
           location\.replace\(|\(\)\}|@@datadir|\/FRAMESET|<ahref=|\[url=http:\/\/|\[\/url\]|
           \[link=http:\/\/|\[\/link\]|YWxlcnQo|\_START\_|onunload%3d|PHP\_SELF|shell\_exec|
           \\$\_SERVER|`;!--=|substr\(|\\$\_POST|\\$\_SESSION|\\$\_REQUEST|\\$\_ENV|GLOBALS\[|\$HTTP\_|
           \.php\/admin|mosConfig\_|%3C@replace\(|hex\_ent|inurl:|replace\(|\/iframe>|return%20clk|
           php\/password\_for|unhex\(|error\_reporting\(|HTTP\_CMD|=alert\(|localhost|}\)%3B|
           Set-Cookie|%27%a0%6f%72%a0%31%3d%31|%bf%5c%27|%ef%bb%bf|%20regexp%20|\{\\$\{|\\\'|
           HTTP\/1\.|\{$\_|PRINT@@variable|xp\_cmdshell|xp\_availablemedia|sp\_password|
           \/var\/www\/php|\_SESSION\[!|file\_get\_contents\(|\*\(\|\(objectclass=|\|\||
           \.htaccess|system\(\%24|UTL\_HTTP\.REQUEST|<script>";

     $_blacklist[2] = "ZXZhbCg=|eval\(base64\_decode|fromCharCode|allow\_url\_include|
           php:\/\/input|concat\(@@|suhosin\.simulation=|\#\!\/usr\/bin\/perl -I|shell\_exec|
           file\_get\_contents\(|onerror=prompt\(|script>alert\(|fopen\(|\_GET\['cmd|
           YWxlcnQo|ZnJvbUNoYXJDb2Rl";
     
     $_blacklist[3] = "Baidu|WebLeacher|autoemailspider|MSProxy|Yeti|Twiceler|blackhat|Mail\.Ru|fuck";

     $_blacklist[4] = "eval\(|fromCharCode|prompt\(|ZXZhbCg=|ZnJvbUNoYXJDb2Rl|U0VMRUNULyoqLw==|
           Ki9XSEVSRS8q|YWxlcnQo";

     $_thelist = preg_replace( "/[\s]/i", '',  $_blacklist[ ( int )$list ] );

     if ( false !== ( bool )preg_match( "/$_thelist/i", $val ) ) {
         return true;
     }
     return false;
   }

   /**
    * AdminLoginBypass()
    */
   function AdminLoginBypass() {
       if ( false !== strpos( $this->getREQUEST_URI(), '.php/login' ) ) {
           $this->karo( true );
           return;
       }
   }

   /**
    * _REQUEST_Shield()
    *
    */
   function _REQUEST_SHIELD() {
        # irregardless of _GET or _POST
        # attacks that do not necessarily
        # involve query_string manipulation
       $req = $this->getREQUEST_URI();
       $ref = isset( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ]: NULL;
       if ( false === is_null( $ref ) ) {
           if ( false !== strpos( $req, '.php/login' ) ) {
               $this->karo( true );
               return;
           }
           if ( false !== strpos( $req, '?author=' ) ) {
               $this->karo( true );
               return;
           }
           if ( false !== strpos( $ref, 'result' ) ) { 
             if ( ( false !== strpos( $ref, 'chosen' ) ) &&
                ( false !== strpos( $ref, 'nickname' ) ) ) {
                $this->karo( true );
                return;
             }
           }
       }
       if ( false !== strpos( $req, '?' ) ) {
           $v =  $this->hexoctaldecode( strtolower( substr( $req,
                strpos( $req, '?' ),
                strlen( $req ) ) ) );
           if ( false !== strpos( $v, '-' ) &&
              ( ( false !== strpos( $v, '?-' ) ) ||
                ( false !== strpos( $v, '?+-' ) ) ) &&
                ( ( false !== strpos( $v, '-s' ) ) ||
                  ( false !== strpos( $v, '-t' ) ) ||
                  ( false !== strpos( $v, '-n' ) ) ||
                  ( false !== strpos( $v, '-d' ) ) ) ) {
                  $this->karo( true );
                  return;
           }
       }
       # Quirky Wordpress Exploit
       if ( isset( $_GET[ '_wp_http_referer' ] ) &&
            ( $this->getPHP_SELF() == 'edit-tags.php' || $this->getPHP_SELF() == 'edit-comments.php' || $this->getPHP_SELF() == 'index.php' ) &&
            ( false === strpos( str_replace( 'http://', '', $_GET[ '_wp_http_referer' ] ), $this->_httphost ) ) &&
            ( false === strpos( str_replace( 'http://', '', $_GET[ '_wp_http_referer' ] ),  $this->getPHP_SELF() ) ) ) {
            # action delete selected checked user submitted posts plugin status inactive paged wpnonce
            die( "Warning: You do not want to click that URL in your Wordpress Admin - see: <a target=_blank
            href='http://goo.gl/cL5XqN'>http://goo.gl/cL5XqN</a><br />Click <a href='/index.php'>here</a> to return to the Dashboard." );
       }
       
       $_requri = $this->url_decoder( $_SERVER[ 'REQUEST_URI' ] );
       $attack = false;
       if ( substr_count( $_requri, '/' ) > 30 ) $attack = true;
       if ( substr_count( $_requri, '\\' ) > 30 ) $attack = true;
       if ( substr_count( $_requri, '|' ) > 30 ) $attack = true;
     
       if ( false !== $attack ) {
           $this->karo( true );
           return;
       } else return;
   }
   /**
    * _GET_SHIELD()
	*
    */
	 function _QUERYSTRING_SHIELD() {
       if ( false !== empty( $_SERVER[ 'QUERY_STRING' ] ) ) {
           return;
       } else {
           $qsdec = $this->url_decoder( $_SERVER[ 'QUERY_STRING' ] );
           $q = explode( '&', $qsdec );
           for( $x = 0; $x < count( $q ); $x++ ) {
               $v = is_array( $q[ $x ] )? $this->array_flatten( $q[ $x ] ) : $q[ $x ];
               $val = $this->hexoctaldecode( substr( $v, strpos( $v, '=') + 1, strlen( $v ) ) );
               if ( false !== $this->injectMatch( $val ) || false !== ( bool )$this->blacklistMatch( $val, 1 ) ) {
                    $this->karo( true );
                    return;
               }
           }
       }
       return;
   }


   function _COOKIE_SHIELD() {
       if ( false !== empty( $_COOKIE ) ) return;
       $ckeys = array_keys( $_COOKIE );
       $cvals = array_values( $_COOKIE );
       $i = 0;
       while ( $i < count( $ckeys ) ) {
           $ckey = strtolower(  $this->hexoctaldecode( $ckeys[$i] ) );
		   $t = $this->hexoctaldecode( $cvals[$i] );
		   $str =  is_array($t)?implode("", $t):$t;
           $cval = $this->url_decoder( strtolower( $str ) );
           if ( ( is_string( $ckey ) ) ) {
               if ( false !== ( bool )$this->blacklistMatch( $ckey, 4 ) ||  false !== ( bool )$this->blacklistMatch( $this->hexoctaldecode( $cval ), 4 ) ) {
                   $this->karo( true );
                   return;
               }
           }
           $injectattempt = ( ( bool )$this->injectMatch( $ckey ) ) ? true : ( ( bool )$this->injectMatch( $cval ) );
           if ( false !== ( bool )$injectattempt ) {
               $this->karo( true );
               return;
           }
           $i++;
       }
   }



	 function _REQUESTTYPE_SHIELD() {
       if ( false === ( bool )$this->_nonGETPOSTReqs ) return;
       
       $reqType = $_SERVER[ 'REQUEST_METHOD' ];
       $req_whitelist = [ 'GET', 'OPTIONS', 'HEAD', 'POST' ];
       # first check for numbers in REQUEST_METHOD
       if ( false !== ( bool )preg_match( "/[0-9]+/", $reqType ) ) {
           $this->karo( true );
           return;
       }
       # then make sure its all UPPERCASE (for servers that do not filter the case of the request method)
       if ( false === ctype_upper( $reqType ) ) {
           $this->karo( true );
           return;
           # lastly check against the whitelist
       } elseif ( false === in_array( $reqType, $req_whitelist ) ) {
           $this->karo( true );
           return;
       }
   }


	 function _POST_SHIELD() {
       if ( 'POST' !== $_SERVER[ 'REQUEST_METHOD' ] )  return;
       $pnodes = $this->array_flatten( $_POST, false );

       $i = 0;
       while ( $i < count( $pnodes ) ) {
           if ( ( is_string( $pnodes[ $i ] ) ) && ( strlen( $pnodes[ $i ] ) > 0 ) ) {
               $pnodes[ $i ] = strtolower( $pnodes[ $i ] );
               if ( false !== $this->blacklistMatch( $this->hexoctaldecode( $pnodes[ $i ] ), 2 ) ||
                    false !== $this->blacklistMatch( $this->url_decoder( $pnodes[ $i ] ), 2 ) ) {
                       $this->karo( true );
                       return;
               }
           }
       $i++;
       }
       return;
   }

   /**
    * Bad Spider Block
    */
   function _SPIDER_SHIELD() {
        if ( false === empty( $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
           if ( false !== $this->blacklistMatch( strtolower(  $this->hexoctaldecode( $_SERVER[ 'HTTP_USER_AGENT' ] ) ), 3 ) ) {
                   $header = [ 'HTTP/1.1 404 Not Found', 'HTTP/1.1 404 Not Found', 'Content-Length: 0' ];
                   foreach ( $header as $sent ) {
                       header( $sent );
                   }
                   die();
           }
       } else return;
   }    

   # if you are using an older version of PHP ( < 5.4 ) then
   # use the uncommented version of this function below
	/**
	 * @param $code
	 *
	 * @return mixed
	 */
	function hexoctaldecode( $code ) {

	  $y =  is_array($code)?implode("", $code):$code;
	  $code = ( substr_count( $y, '\\x' ) > 0 ) ? $this->url_decoder( str_replace( '\\x', '%', $y ) ) : $y;
	  unset ($y);
      if ( ( substr_count( $code, '&#x' ) > 0 ) && ( substr_count( $code, ';' ) > 0 ) ) {
          $code = $this->url_decoder( str_replace( ';', '', $code ) );
          $code = $this->url_decoder( str_replace( '&#x', '%', $code ) );
       }
       return preg_replace_callback(
           "@\\\(x)?([0-9a-f]{2,3})@",
           function( $x ){
               return chr( $x[1] ? hexdec( $x[2] ):octdec( $x[2] ) );
           },
           $code
       );
    }

  /* function hexoctaldecode( $code ) {
       $code = ( substr_count( $code, '\\x' ) > 0 ) ? $this->url_decoder( str_replace( '\\x', '%', $code ) ) : $code;
       if ( ( substr_count( $code, '&#x' ) > 0 ) && ( substr_count( $code, ';' ) > 0 ) ) {
          $code = $this->url_decoder( str_replace( ';', '', $code ) );
          $code = $this->url_decoder( str_replace( '&#x', '%', $code ) );
       }
       return $code;
   }*/

	/**
	 * @param $b
	 * @param $s
	 *
	 * @return mixed|string
	 */
	function cleanString( $b, $s ) {
     $s = strtolower( $this->url_decoder( $s ) );
       switch ( $b ) {
           case ( 1 ):
               return preg_replace( "/[^\s{}a-z0-9_?,()=@%:{}\/.-]/i", '', $s );
               break;
           case ( 2 ):
               return preg_replace( "/[^\s{}a-z0-9_?,=@%:{}\/.-]/i", '', $s );
               break;
           case ( 3 ):
               return preg_replace( "/[^\s=a-z0-9]/i", '', $s );
               break;
           case ( 4 ): // fwr_security pro
               return preg_replace( "/[^\s{}a-z0-9_\.\-]/i", "", $s );
               break;
           case ( 5 ):
               return str_replace( '//', '/', $s );
               break;
           case ( 6 ):
               return str_replace( '/**/', ' ', $s );
               break;
           default:
               return $this->url_decoder( $s );
       }
   }


	/**
	 * @param $banip
	 */
	function htaccessbanip( $banip ) {
       if ( false === $this->hCoreFileChk( $this->_htaccessfile, true, true ) )
            $this->send403();
       $limitend = "# End of $this->_httphost $this->_psec Ban\n";
       $newline = "deny from $banip\n";
       if ( false !== $this->hCoreFileChk( $this->_htaccessfile, true, true ) ) {
           $mybans = file( $this->_htaccessfile );
           $lastline = "";
           if ( in_array( $newline, $mybans ) )
               exit();
           if ( in_array( $limitend, $mybans ) ) {
               $i = count( $mybans ) - 1;
               while ( $mybans[$i] != $limitend ) {
                   $lastline = array_pop( $mybans ) . $lastline;
                   $i--;
               }
               $lastline = array_pop( $mybans ) . $lastline;
               $lastline = array_pop( $mybans ) . $lastline;
               array_push( $mybans, $newline, $lastline );
           } else {
               array_push( $mybans, "\n\n# $this->_httphost $this->_psec Ban\n", "order allow,deny\n", $newline,
                   "allow from all\n", $limitend );
           }
       } else {
           $mybans = [ "# $this->_httphost $this->_psec Ban\n", "order allow,deny\n", $newline, "allow from all\n",
               $limitend ];
       }
       $myfile = fopen( $this->_htaccessfile, 'w' );
       fwrite( $myfile, implode( $mybans, '' ) );
       fclose( $myfile );
   }


	/**
	 * @param null $f
	 * @param bool $r
	 * @param bool $w
	 *
	 * @return bool
	 */
	private static function hCoreFileChk( $f = NULL, $r = false, $w = false ) {

       $x = file_exists( $f );
       $x = ( false !== $r ) ? is_readable( $f ) : $x;
       $x = ( false !== $w ) ? is_writable( $f ) : $x;
       if ( $x == true ) {
         return true;
       } else return false;
   }

	/**
	 * @param $fname
	 *
	 * @return bool
	 */
	private static function checkfilename( $fname ) {
       if ( ( ! empty( $fname ) ) &&
          ( ( substr_count( $fname, '.php' ) == 1 ) && ( '.php' == substr( $fname, - 4 ) ) ) ) {
            return true;
       } else return false;
   }

	/**
	 * @return null|string
	 */
	function getPHP_SELF() {
       $filename = NULL;
       
       $filename = basename( $this->setReq_uri() );
       if ( ( 1 == strlen( $filename ) ) && ( '/' == $filename ) ) $filename = '/' . $this->_default; // or whatever your default file is
       preg_match( "@[a-z0-9_-]+\.php@i", $filename, $matches );
       if (is_array( $matches ) &&
           array_key_exists( 0, $matches ) &&
           ( '.php' == substr( $matches[ 0 ], -4, 4 ) ) &&
           ( false !== $this->checkfilename( $matches[ 0 ] ) ) &&
           ( is_readable( $matches[ 0 ] ) ) ) {
           $filename = $matches[ 0 ];
       } else $filename = NULL;
       if ( !empty( $filename ) ) {
           return $filename;
       } else {
           return $this->_default;
       }
   }


	/**
	 * @param      $array
	 * @param bool $preserve_keys
	 *
	 * @return array
	 */
	function array_flatten( $array, $preserve_keys = false ) {
       if ( false === $preserve_keys ) {
           $array = array_values( $array );
       }
       $flattened_array = [ ];
       foreach ( $array as $k => $v ) {
           if ( is_array( $v ) ) {
               $flattened_array = array_merge( $flattened_array, $this->array_flatten( $v, $preserve_keys ) );
           } elseif ( $preserve_keys ) {
               $flattened_array[$k] = $v;
           } else {
               $flattened_array[] = $v;
           }
       }
       return $flattened_array;
   }

   /**
    * byPass()
    *
	 * @return bool
	 */
	function byPass() {

       # list of files to bypass. I have added a few for consideration. Try to keep this list short
       $filename_bypass = [ ];

       # bypass all files in a directory. Use this sparingly
       $dir_bypass = [ ];

       # list of IP exceptions. Add bypass ips and uncomment for use
       $exfrmBanlist = [ ];

       $realip = $this->getRealIP();
       if ( false === empty( $exfrmBanlist ) ) {
           foreach ( $exfrmBanlist as $exCeptions ) {
               if ( false !== ( strlen( $realip ) == strlen( $exCeptions ) ) && ( false !== strpos( $realip, $exCeptions ) ) ) {
                   return false;
               }
           }
       }
       if ( false === empty( $filename_bypass ) ) {
           foreach ( $filename_bypass as $filename ) {
               if ( false !== strpos( $this->getPHP_SELF(), $filename ) ) {
                   return false;
               }
           }
       }
       if ( false === empty( $dir_bypass ) ) {
           foreach ( $dir_bypass as $dirname ) {
               if ( false !== strpos( $this->setReq_uri(), $dirname ) ) {
                   return false;
               }
           }
       }
       return true;
   }

   /**
    * getDir()
    *
	 * @return mixed|string
	 */
	function getDir() {

           $rootDir = $this->setReq_uri();
           if ( false !== strpos( $rootDir, '/' ) ) {
               if ( ( strlen( $rootDir ) > 2 ) && ( $rootDir[0] == '/' ) ) {
                   $rootDir = substr( $rootDir, 1 );
                   $pos = strpos( strtolower( $rootDir ), strtolower( '/' ) );
                   $pos += strlen( '.' ) - 1;
                   $rootDir = substr( $rootDir, 0, $pos );
                   if ( strpos( $rootDir, '.php' ) ) $rootDir = '';
                   if ( ( strlen( $rootDir ) > 0 ) && ( '/' !== substr( $rootDir, -1 ) ) ) $rootDir = '/' . $rootDir . '/';
               }
           }
           if ( isset( $this->_doc_root ) && strlen( $this->_doc_root ) > 0 ) {
              $theDIR = $this->_doc_root;
           } elseif ( false !== strpos( $_SERVER[ 'DOCUMENT_ROOT' ], 'usr/local/apache' ) ) {
               $theDIR = $this->_doc_root;
           } else {
               $theDIR = $_SERVER[ 'DOCUMENT_ROOT' ] . $rootDir;
           }
           
           $x = 26;
           $theDIR = $theDIR . '/';
           while ( $x >= 0 ) {
                      $theDIR = str_replace( '//', '/', $theDIR );
                   $x--;
           }
           return $theDIR;
   }

	/**
	 * @param $ip
	 *
	 * @return bool
	 */
	private static function is_server( $ip ) {
       if ( ( $_SERVER[ 'SERVER_ADDR' ] == $ip ) ||
            ( $ip == '127.0.0.1' ) ) return true;
       return false;
    }

	/**
	 * @param $ip
	 *
	 * @return bool|void
	 */
	function check_ip( $ip ) {
       # if ip is the server
       if ( $this->is_server( $ip ) ) return true;
       # simple ip format check
       if ( function_exists( 'filter_var' )
                 && defined( 'FILTER_VALIDATE_IP' )
                 && defined( 'FILTER_FLAG_IPV4' )
                 && defined( 'FILTER_FLAG_IPV6' ) ) {
                 if ( false === filter_var( $ip, FILTER_VALIDATE_IP,
                                                 FILTER_FLAG_IPV4 ||
                                                 FILTER_FLAG_IPV6 ) ) {
                     $this->send403();
                 } else return true;
       }
       if ( preg_match( "/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip ) ) {
           $parts = explode( '.', $ip );
           foreach ( $parts as $ip_parts ) {
                if ( ! is_numeric( $ip_parts ) ||
                   ( ( int )( $ip_parts ) > 255 ) ||
                   ( ( int )( $ip_parts ) < 0 ) ) {
                   $this->send403();
               }
           }

       } else {
		   $this->send403();
	   }
		return true;
   }

   /**
    * getRealIP()
    *
	 * @return string|void
	 */
	function getRealIP() {
     global $_SERVER;
     // check for IPs passing through proxies

     if ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
         // check if multiple ips exist in var
        $x_ff = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        $x_ff = strpos( $x_ff, ',' ) ? str_replace( ' ', '', $x_ff ) : str_replace( ' ', ',', $x_ff );
        if ( false !== strpos( $x_ff, ',' ) ) {
            $iplist = explode( ',', $x_ff );
            foreach( $iplist as $ip ) {
                 if ( false !== $this->check_ip( $ip ) ) {
                     $this->_bypassbanip = true;
                 }
            }
        }

     }
     $serverVars = new ArrayIterator( [ 'HTTP_CLIENT_IP', 'HTTP_PROXY_USER','HTTP_X_CLUSTER_CLIENT_IP',
                                             'HTTP_FORWARDED', 'HTTP_CF_CONNECTING_IP', 'HTTP_FORWARDED_FOR' ] );
     while ( $serverVars->valid() ) {
        if ( array_key_exists( $serverVars->current(), $_SERVER )
              && ! empty( $_SERVER[$serverVars->current()] )
              && false !== $this->check_ip( $_SERVER[$serverVars->current()] ) ) {
              $this->_bypassbanip = true;
        }
        $serverVars->next();
     }
     // never trust any headers
		if( false !== $this->check_ip( getenv( 'REMOTE_ADDR' )) ) {
			return  getenv( 'REMOTE_ADDR' );
		} else {
			$this->send403();
			return false;
		}

   }
   
   function setOpenBaseDir() {
	   if(!self::issetStrlen(ini_get( 'open_basedir' ))) {
		   ini_set( 'open_basedir', $this->getDir() );
	   }
   }

   /**
    * x_secure_headers()
    */
   function x_secure_headers() {
       $errlevel = ini_get( 'error_reporting' );
       error_reporting( 0 );
       if ( false !== ( bool )ini_get( 'expose_php' ) || 'on' == strtolower( @ini_get( 'expose_php' ) ) ) {
           header( 'X-Powered-By: Hokioi-IT ' . $this->_psec );
       }

       header( 'X-Frame-Options: SAMEORIGIN' );
       header( 'X-Content-Type-Options: nosniff' );

       error_reporting( $errlevel );
       return;
   }

	/**
	 * url_decoder()
	 *
	 * @param $var
	 *
	 * @return string
	 */
   private static function url_decoder( $var ) {
       return rawurldecode( urldecode( $var ) );
   }

	/**
	 * @return string
	 */
	private static function getREQUEST_URI() {
       if ( false !== getenv( 'REQUEST_URI' ) ) {
           return getenv( 'REQUEST_URI' );
       } else {
           return $_SERVER[ 'REQUEST_URI' ];
       }
   }

	/**
	 * @param $str
	 *
	 * @return bool
	 */
	private static function issetStrlen( $str ) {
       if ( isset( $str ) && ( strlen( $str ) > 0 ) ) {
           return true;
       } else {
           return false;
       }
   }
   /**
    * setReq_uri()
    *
	 * @return mixed|string
	 */
	private static function setReq_uri() {
       $_request_uri = '';
       if ( empty( $_SERVER[ 'REQUEST_URI' ] ) || ( php_sapi_name() != 'cgi-fcgi' && false !== ( bool )
           preg_match( "/^Microsoft-IIS\//", $_SERVER[ 'SERVER_SOFTWARE' ] ) ) ) {
           if ( false !== getenv( 'REQUEST_URI' ) ) {
               $_request_uri = getenv( 'REQUEST_URI' );
           } else {
               if ( ! isset( $_SERVER[ 'PATH_INFO' ] ) && isset( $_SERVER[ 'ORIG_PATH_INFO' ] ) )
                             $_SERVER[ 'PATH_INFO' ] = $_SERVER[ 'ORIG_PATH_INFO' ];
               if ( isset( $_SERVER[ 'PATH_INFO' ] ) ) {
                   if ( $_SERVER[ 'PATH_INFO' ] == $_SERVER[ 'SCRIPT_NAME' ] ) {
                       $_request_uri = $_SERVER[ 'PATH_INFO' ];
                   } else {
                       $_request_uri = $_SERVER[ 'SCRIPT_NAME' ] . $_SERVER[ 'PATH_INFO' ];
                   }
               }
           }
           
           if ( ! empty( $_SERVER[ 'QUERY_STRING' ] ) ) {
               $_request_uri .= '?' . $_SERVER[ 'QUERY_STRING' ];
           }
           $_SERVER[ 'REQUEST_URI' ] = $_request_uri;
       } else {
           $x = 16;
           $_request_uri = $_SERVER[ 'REQUEST_URI' ];
           while ( $x >= 0 ) {
                      $_request_uri = str_replace( '//', '/', $_request_uri );
                   $x--;
           }
       }
		return $_request_uri;
   }


} // end of class


/**
 * Alex_selfchk()
 *
 */
function Alex_selfchk() {
    $afp = str_replace( DIRECTORY_SEPARATOR, urldecode( '%2F' ), __FILE__ );
    $afp = explode( '/', $afp );
    if ( is_array( $afp ) ) {
        $fileself = $afp[count( $afp ) - 1];
        if ( $fileself[0] == '/' ) {
            return $fileself;
        } else {
            return '/' . $fileself;
        }
    }
	return false;
}

/**
* send404()
*
*/
function send404() {
   $header = [ 'HTTP/1.1 404 Not Found', 'HTTP/1.1 404 Not Found', 'Content-Length: 0' ];

   foreach ( $header as $sent ) {
       header( $sent );
   }
	header( 'Refresh: 0; url=/../../stop.php' ); // �������������� �� �������� ������ ���������� (��� ��������).
	die ('');
}