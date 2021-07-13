<?php

function enc($plain){
    return password_hash(md5($plain), true);
}

function vail($enc, $hash){
    password_verify(password_hash(md5($enc), PASSWORD_DEFAULT), $hash);
}