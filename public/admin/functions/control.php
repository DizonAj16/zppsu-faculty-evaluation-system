<?php

function fileSize_notCompatible(array $profile){
    if($profile["size"] > 5 * 1024 * 1024){
        return true;
    }else{
        return false;
    }
}

function image_notCompatible(array $profile, array $allowed_types){
    if(!in_array($profile["type"], $allowed_types)){
        return true;
    }else{
        return false;
    }
}

function file_notUploaded(array $profile, string $target_file){
    if (!move_uploaded_file($profile["tmp_name"], $target_file)) {
        return true;
    }else{
        return false;
    }
}
function empty_image(array $profile){
    if(empty($profile)){
        return true;
    }else{
        return false;
    }
}