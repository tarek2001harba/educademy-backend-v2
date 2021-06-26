<?php
// checks when adding smoething and sends the right reponse and message
function check_create($create_res, $object, $created_name){
    if($create_res === true){
        http_response_code(201);
        echo json_encode(
            array(
                "message" => $created_name." Created Successfully.",
                "uid" => $object->uid,
                "tid" => $object->tid
            )
        );
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to Create ".$created_name."."));
    }
}

function checkSigninAuth($create_res, $object){
    if($create_res === true){
        http_response_code(200); // OK
        echo json_encode(
            array(
                "message" => "Loged in to account Successfully.",
                "uid" => $object->uid,
                "tid" => $object->tid,
                "fname" => $object->fname,
                "lname" => $object->lname,
                "phone" => $object->phone,
                "email" => $object->email,
                "country" => $object->country,
                "spec" => $object->spec,
                "gender" => $object->gender,
                "bdate" => $object->bdate,
                "join_date" => $object->join_date,
                "type" => $object->type,
                "about" => $object->about
            )
        );
    }
    else{
        http_response_code(401); // Unauthorized to log in
        echo json_encode(array("message" => "Unauthorized to log in to account. Either password or email is wrong."));
    }
}