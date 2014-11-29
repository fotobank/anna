<?php
/* filemanager.php por Carlson A. Soares - 2010.07.22
   Classe de controle de arquivos.
   Copia, deleta, lê, cria, lista, etc.
*/

    class FileManager{
        
        //Retorna a extensão do arquivo informado
        function fileExt($filename){ return end(explode(".", $filename)); }

        //Gera um nome unico
        function uniqueName( $prefix, $ext){
            $return='';
            for ($i=0;$i<7;$i++){
                $return.=chr(rand(97,122));
            }
            $return="$prefix-$return-".time()."-X.$ext";	
            return $return;	
        }
        
        //Converte um nome de pasta relativo, para um nome de pasta absoluto
        function dirToUrl( $dir ){
            $url = $this->curPageURL();
            $url = substr( $url, 0, strrpos( $url, '/', 7 ) + 1 );
            
            
            if( strlen( $url ) < 10 ){
                $url = $this->curPageURL();
                if( strpos( $url, '?' ) > 0 ){
                    $url = substr( $url, 0, strrpos( $url, '?') + 1 );
                }
            }
            
            while( strpos($dir, '../') === 0 ){
                if( strpos($dir, '../') == 0 ){
                    $dir = substr( $dir, 3, strlen( $dir ) - 3);
                    $url = substr( $url, 0, strrpos( $url, '/', -2 ) + 1 );
                }
            }
            
            if( substr( $url, strlen($url) - 1,1) == '/' ) $url = substr( $url, 0, strlen($url) - 1);
            if( substr( $dir, strlen($dir) - 1,1) !== '/' ) $dir .= '/';
            if( substr( $dir, 0,1) !== '/' ) $dir = '/' . $dir;

            return $url . $dir;
        }
        
        
        //Pega a url atual
        function curPageURL( $clean = false ) {
            $pageURL = 'http://';
            
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            
            if( $clean ){
                if( strrpos( $pageURL, '?' ) > 0 ) {
                    $pageURL = substr( $pageURL, 0, strrpos( $pageURL, '?' ));
                }
            }
            return $pageURL;
        }
        
    }
?>