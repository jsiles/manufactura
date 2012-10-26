<?php
    // Copia el archivo temporal al archivo en el server
    // en $dir se especifica directorio de escritura.
    $dir = 'archs/';
    if (isset($_POST['id'])) {
        if (!copy($_FILES[$_POST['id']]['tmp_name'], 'archs/'.$_FILES[$_POST['id']]['name']))
            echo '<script> alert("Error al Subir el Archivo");</script>';
    }
    else
        echo "Archivo subido.";
?>