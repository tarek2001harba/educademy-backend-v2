<?php
// checks when adding smoething and sends the right reponse and message
function check_create($create_res, $object, $created_name){
    if($create_res === true){
        http_response_code(201);
        $udata = array(
                "message" => $created_name." Created Successfully.",
                "uid" => $object->uid,
                "join_date" => $object->join_date,
        );
        if($object->type === "Teacher"){
            $udata["tid"] = $object->tid;
        }
        else{
            $udata["sid"] = $object->sid;
        }
        echo json_encode($udata);
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to Create ".$created_name."."));
    }
}

function checkSigninAuth($create_res, $object){
    if($create_res === true){
        http_response_code(200); // OK
        $udata = array(
            "message" => "Loged in to account Successfully.",
            "uid" => $object->uid,
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
        );
        if($object->type === "Teacher"){
            $udata["tid"] = $object->tid;
        }
        else{
            $udata["sid"] = $object->sid;
        }
        echo json_encode($udata);
    }
    else{
        http_response_code(401); // Unauthorized to log in
        echo json_encode(array("message" => "Unauthorized to log in to account. Either password or email is wrong."));
    }
}

function check_create_course($create_res){
    if($create_res === true){
        http_response_code(201);
        $udata = array(
                "message" => "Course Created Successfully."
        );
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to Create Course."));
    }
}