<?php
/* image.php por Carlson A. Soares - 2010.07.22
   Classe de controle de imagens.
   Cria thumbs, recorta partes da imagem, pega informações, etc.
*/

    class Image{
        
        private $objFile;
        
        //Construtor da classe
        function __construct( $file = '' ) {
            if( empty( $file ) ){
                if( class_exists( 'FileManager' ) ){
                    $file = new FileManager();
                } else {
                    throw new Exception('Classe FileManager não localizada.');
                }
            }
           $this->objFile = $file;
        }

        //Cria uma copia da imagem(jpg, jpeg, gif, png) com a resolução desejada
        function imageCopy($imgOri, $imgDest, $max_x, $max_y, $quality = 80){
        
            if( is_null( $this->objFile ) ) return false;
        
            //pega o tamanho da imagem
            list($width, $height) = getimagesize($imgOri);
            
            //pega a extensão e cria uma imagem em memoria
            $image_p = imagecreatetruecolor($max_x,$max_y);
            $ext     = $this->objFile->fileExt( $imgOri );
            
            //De acordo com o formato da imagem, cria um thumb
            if( $ext == 'jpg' || $ext == 'jpeg' ){

                $image = imagecreatefromjpeg($imgOri);
                //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $max_x,$max_y, $width, $height);
                imagecopyresized($image_p, $image, 0, 0, 0, 0, $max_x,$max_y, $width, $height);
                return imagejpeg($image_p, $imgDest, $quality);

            } elseif( $ext == 'gif' ){

                $image = imagecreatefromgif($imgOri);
                //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $max_x,$max_y, $width, $height);
                imagecopyresized($image_p, $image, 0, 0, 0, 0, $max_x,$max_y, $width, $height);
                return imagegif($image_p, $imgDest, $quality);
            
            } elseif( $ext == 'png' ) {

                $image = imagecreatefrompng($imgOri);
                //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $max_x,$max_y, $width, $height);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $max_x,$max_y, $width, $height);
                return imagepng($image_p, $imgDest, $quality);
                
            }
            return false;
        }
    
        //Recorta a area de uma imagem(jpg, jpeg, gif, png)
        function imageCrop($imgOri, $imgDest, $width, $height, $x = 0, $y = 0, $quality = 80){
            if( is_null( $this->objFile ) ) return false;
        
            //pega o tamanho da imagem
            list($ori_width, $ori_height) = getimagesize($imgOri);
            
            //pega a extensão e cria uma imagem em memoria
            $image_p = imagecreatetruecolor($width,$height);
            $ext     = $this->objFile->fileExt( $imgOri );
            
            //De acordo com o formato da imagem, cria um thumb
            if( $ext == 'jpg' || $ext == 'jpeg' ){

                $image = imagecreatefromjpeg($imgOri);
                //imagecopyresampled($image_p, $image, 0, 0, $x, $y, $width,$height, $width, $height);
                imagecopyresized($image_p, $image, 0, 0, $x, $y, $width,$height, $width, $height);
                return imagejpeg($image_p, $imgDest, $quality);

            } elseif( $ext == 'gif' ){

                $image = imagecreatefromgif($imgOri);
                //imagecopyresampled($image_p, $image, 0, 0, $x, $y, $width,$height, $width, $height);
                imagecopyresized($image_p, $image, 0, 0, $x, $y, $width,$height, $width, $height);
                return imagegif($image_p, $imgDest, $quality);
            
            } elseif( $ext == 'png' ) {

                $image = imagecreatefrompng($imgOri);
                //imagecopyresampled($image_p, $image, 0, 0, $x, $y, $width,$height, $width, $height);
                imagecopyresized($image_p, $image, 0, 0, $x, $y, $width,$height, $width, $height);
                return imagepng($image_p, $imgDest, $quality);
                
            }
            return false;
        }
    }
    
?>