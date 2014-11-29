<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
<?php
    
    //Include usado na classe, se move-los, alterar o patch no arquivo imagecrop.php
    //files used in imagecropt.php, if you move it, change the patch in imagecrop.php to
    include "image.php";
    include "filemanager.php";
    
    
    //Variaveis com as configurações da imagem a ser cortada
    //Variables with the configuration of the image to crop
    //You can use a local file too
    $imageOri    = "http://www.noticiasautomotivas.com.br/img/b/transformers-2.jpg";
    $imageDest   = "teste.jpg";
    $imageWidth  = 160;
    $imageHeight = 130;
    $imageClass  = "classe";
    
    
    include "imagecrop.php";
    
?>
