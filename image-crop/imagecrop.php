<?php
/* imagecrop.php por Carlson A. Soares - 2010.07.22
   Recorta uma área da imagem.
*/
    class ImageCrop{
        
        //Função que monta a interface do usuario
        //This function print the user interface
        function init( $width, $height, $ori, $dest, $class = "" ) {
            print '<div class="imgcrop ' . $class . '" style="width: 100%; height: 100%;">';
            $this->css( $width, $height );
            $this->javascript( $width, $height, $ori, $dest );
            $this->html( $width, $height, $ori, $dest );
            print '</div>';
        }
        
        //Escreve a parte do CSS
        //Print the css part
        function css( $width, $height ){
            print '<style>
                        div.imgcrop div.imgmain{ position: relative; width: ' . $width . '; height: ' . $height . '; float: left; }
                        div.imgcrop div.form{ position: relative; float: right; }
                        div.imgcrop img#imgori{ position: absolute; top: 0px; left: 0px; z-index: -1; }
                        div.imgcrop div#imgshade{   background-image: url(images/cinza_40.png);
                                                    position: absolute;
                                                    top: 0px;
                                                    left: 0px;
                                                    z-index: 999;
                                                    width: ' . $width . ';
                                                    height: ' . $height . '; }
                        
                    </style>';
        }
        
        //Escreve a parte em javascript
        //Print the javascript part
        function javascript( $width, $height, $ori, $dest ){
            print '
                <script type="text/javascript" language="javascript">
                    var zoom  = 1;
                    var width = ' . $width . ';
                    var height = ' . $height . ';
                    var imgOri = "' . $ori . '";
                    var imgDest = "' . $dest . '";
                
                    function changeZoom( sinal ){
                        if( (sinal == "-" && zoom <= 0.1) || (sinal == "+" && zoom >= 5) ) return;
                        if( sinal == "+" ){
                            if( zoom >= 1 ){
                                zoom += 0.25;
                            } else {
                                zoom += 0.1;
                            }
                        } else {
                            if( zoom > 1 ){
                                zoom -= 0.25;
                            } else {
                                zoom -= 0.1;
                            }
                        }
                        
                        setZoom();
                    }
                    
                    function setZoom(){
                        document.getElementById("imgshade").style.width = (width / zoom) + "px";
                        document.getElementById("imgshade").style.height = (height / zoom) + "px";
                        document.getElementById("zoom").innerText = parseInt(100 * zoom) + "%";
                    }
                    
                    function changePos( c, value ){
                        if( c == "X" ){
                            document.getElementById("imgshade").style.left = value + "px";
                        } else {
                            document.getElementById("imgshade").style.top = value + "px";
                        }
                    }
                    
                    function gerar(){
                        var y = $(imgshade).position().top;
                        var x = $(imgshade).position().left;
                        document.getElementById( "loading" ).style.display = "block";
                        $.post("imagecrop.php", { imageOri: "' . $ori . '", imageDest: "' . $dest . '", width: ' . $width . ' , height: ' . $height . ', zoom: zoom, imgX: x, imgY: y },
                           function(data){
                            tmp = new Date();
                            document.getElementById( "imgthumb" ).src = "' . $dest . '?" + tmp.getTime();
                            document.getElementById( "result" ).innerHTML = data;
                            document.getElementById( "loading" ).style.display = "none";
                        });
                    }
                    
                    $(document).ready(function() {

                          $(\'#imgori\').mousedown(function(e) {
                             var offset = $(this).offset();
                             var x = e.pageX - (offset.left);
                             var y = e.pageY - (offset.top);
                             document.getElementById( "imgx" ).value = x;
                             document.getElementById( "imgy" ).value = y;
                             changePos( "X", x );
                             changePos( "Y", y );
                          });
                          
                          $(\'#imgshade\').mousedown(function(e) {
                             var offset = $(this).offset();
                             objX = document.getElementById( "imgx" );
                             objY = document.getElementById( "imgy" );
                             var x = e.pageX - (offset.left) + parseInt(objX.value);
                             var y = e.pageY - (offset.top) + parseInt(objY.value);
                             objX.value = x;
                             objY.value = y;
                             changePos( "X", x );
                             changePos( "Y", y );
                          });
                        });
                </script>
            ';
        }

        //Escreve a parte do html da interface  
        //Print the html part of the interface
        function html( $width, $height, $ori, $dest ){
            print '
                <div class="imgmain" style="z-index:1">
                    <img src="' . $ori . '" id="imgori"  /><div id="imgshade" style="width: ' . $width . '; height: ' . $height . ';  style="z-index:2"></div>
                </div>
                <div class="form" id="form">
                    <table>
                        <tr>
                            <th>Posição</th>
                        </tr><tr>
                            <td><input type="text" name="imgx" id="imgx" value="0" onkeyup="changePos(\'X\',this.value)" /></td>
                            <td><input type="text" name="imgy" id="imgy" value="0" onkeyup="changePos(\'Y\',this.value)" /></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:changeZoom(\'+\')">+ Zoom</a></td>
                            <td><a href="javascript:changeZoom(\'-\')">- Zoom</a></td>
                            <td><span id="zoom">100%</span></td>
                        </tr>
                        <tr><td><a href="javascript:gerar()">Gerar</a><img src="images/loading.gif" id="loading" style="display:none;" /></td></tr>
                    </table>
                    <div id="thumb">
                        <img src="' . $dest . '" $width="' . $width . '" height="' . $height . '" id="imgthumb" />
                    </div>
                    <div id="result">
                </div>
                </div>
            ';
        }
    }
        
        
        //Executado quando chamado via ajax
        //Executed when called via ajax
        if( isset( $_POST['imageOri'] ) ){

            include 'image.php';
            include 'filemanager.php';
            
            $zoom   = $_POST['zoom'];
            $width  = $_POST['width'];
            $height = $_POST['height'];
            $ori    = $_POST['imageOri'];
            $dest   = $_POST['imageDest'];
            $imgY   = $_POST['imgY'];
            $imgX   = $_POST['imgX'];
            
            @unlink($dest);
            
            list( $oriWidth, $oriHeight ) = getimagesize( $ori );
            
            $oriWidth *= $zoom;
            $oriHeight *= $zoom;
            $imgX *= $zoom;
            $imgY *= $zoom;
            
            $file    = new FileManager();
            $img     = new Image( $file );
            $tmpFile = $file->uniqueName( 'temp', $file->fileExt( $ori ) );
            
            if( $zoom == 1 ){
                $img->imageCrop( $ori, $dest, $width, $height, $imgX, $imgY );
            } else {
                $img->imageCopy($ori, $tmpFile, $oriWidth, $oriHeight, 90);
                $img->imageCrop( $tmpFile, $dest, $width, $height, $imgX, $imgY );
            }
            @unlink( $tmpFile );
            print 'Thumb gerado com sucesso';
            exit;
            
        //Inicia as funções para cortar a imagem
        //Init the imagecrop funcions
        } elseif( !isset( $_GET['init'] ) ) {
        
            if( isset($imageOri) && isset($imageDest) )
            {
                $width  = 100;
                $height = 100;
                $class  = '';
                $ori    = $imageOri;
                $dest   = $imageDest;
                
                if( isset( $imageWidth ) ){
                    $width = $imageWidth;
                }
                
                if( isset( $imageHeight ) ){
                    $height = $imageHeight;
                }
                
                if( isset( $imageClass ) ){
                    $class = $imageClass;
                }
                
                $oImg = new ImageCrop();
                $oImg->init( $width, $height, $ori, $dest, $class );
            }
        }
?>