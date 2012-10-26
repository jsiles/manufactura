<html>
    <head>
        <title>AJAX File uploader</title>
        <script type="text/javascript" src="uploader.js" ></script>
    </head>
    <style>
    h3 {color: #F00}
    p {color: #00F; border: 1px solid #CCC; margin: 2px; padding: 2px;}
    </style>
    <body>
    
<?php
    // Carpeta donde se guardan los archivos subido
    $upDir = "archs/";
    
    //    Incluimos Clase
    require_once("AjaxFileUploader.inc.php");
    
    //    Creamos Objeto
    $Archivo = new AjaxFileuploader($upDir);
    
    // Escaneamos los archivos en el servidor
    $archs = $Archivo->scanDir();
    echo '<h3>Archivos Subidos</h3>';
    foreach ($archs as $nombre_archivo)
        echo '<p>'.$upDir.'<b>'.$nombre_archivo.'</b></p>';
    
    
    echo '<br><br><p>Archivos a Subir</p>';
    
        // Formulario de subida 1
        echo $Archivo->showFileUploader('id1');

        // Formulario de subida 2
        echo $Archivo->showFileUploader('id2');
    
?>
    </body>
</html>