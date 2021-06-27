<?php
// include_once "../../config/Database.php";
// include_once '../../models/User.php';
// include_once '../../models/Teacher.php';

// $db = new Database();

// $u = new User($db->getConnection());

// $u->email = "tarek@gmail.com";
// $u->password = 'hacktest';
// echo $u->password."</br>";
// echo password_verify($u->password, '$2y$10$et10s9eVhl9oxZAD84shU.wVXWJXIa4MeZDozSKLp1YpdeAvdsiBq')."</br>";
// echo $u->countRows("user_email = '".$u->email."'")."</br>";

// echo $u->countRows("user_pwd = '".$u->password."' AND user_email = '".$u->email."'")."</br>";

// echo 'siginAuth: </br>';
// $u->signinAuth();
// echo 'signin: </br>';

// if(!$u->checkAvalUser()){ // checks if email exists
//     if($u->type === "Teacher"){
//         $teacher = new Teacher($db->getConnection());
//         $teacher->email = $email;
//         $teacher->password = $password;
//         checkSigninAuth($teacher->signinAuth(), $teacher);
//     }
//     else{
//         $student = new Student($db->getConnection());
//         $student->email = $email;
//         $student->password = $password;
//         checkSigninAuth($student->signinAuth(), $student);
//     }
    
// }