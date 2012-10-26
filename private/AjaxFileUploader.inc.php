<?php
    class AjaxFileuploader {
        
        // PHP 4.x users replace "PRIVATE" from the following lines with VAR
        var $uploadDirectory = '';
        var $uploaderIdArray = array();
        
        /**
         * Constructor Function
         * 
         */
        function AjaxFileuploader($uploadDirectory) {
            
            if (trim($uploadDirectory) != '' && is_dir($uploadDirectory))
                $this->uploadDirectory = $uploadDirectory;
            else
                die("<b>ERROR:</b> No Se puede abrir el directorio: $uploadDirectory");
        }
    
    
    
        
        /**
        * Metodo que retorna un Array con todos los archivos en el uploadDir
        * @return array
        */
        function scanDir() {
            $returnArray = array();
            
            if ($handle = opendir($this->uploadDirectory)) {
               
                while (false !== ($file = readdir($handle))) {
                     if (is_file($this->uploadDirectory."/".$file)) {
                         $returnArray[] = $file;
                     }
                }
            
               closedir($handle);
            }
            else {
                die("<b>ERROR: </b> No se puede leer el directorio <b>". $this->uploadDirectory.'</b>');
            }
            return $returnArray;            
        }


        /**
         * Esta Funcion Retorna el formulario html para subir el archivo al server
         * @param string $uploaderId
         * @return string
         */
        function showFileUploader($uploaderId) {
            if (in_array($uploaderId, $this->uploaderIdArray)) {
                die($uploaderId." already used. please choose another id.");
                return '';
            }
            else {
                $this->uploaderIdArray[] = $uploaderId;
            
                return '<form id="formName'.$uploaderId.'" method="post" enctype="multipart/form-data" action="imageupload.php" target="iframe'.$uploaderId.'">
                            <input type="hidden" name="id" value="'.$uploaderId.'" />
                            <span id="uploader'.$uploaderId.'" style="font-family:verdana;font-size:10;">
                                Upload File: <input name="'.$uploaderId.'" type="file" value="'.$uploaderId.'" onchange="return uploadFile(this)" />
                            </span>
                            <iframe name="iframe'.$uploaderId.'" src="imageupload.php" width="400" height="100" style="display:none"> </iframe>
                        </form>';
            }
        }
    }
?>