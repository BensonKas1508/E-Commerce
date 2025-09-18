<?php

require_once '../classes/customer_class.php';


function register_user_ctr($name, $email, $password, $phone_number, $role)
{
    $user = new customer();
    $user_id = $user->createUser($name, $email, $password, $phone_number, $role);
    if ($user_id) {
        return $user_id;
    }
    return false;
}

function get_user_by_email_ctr($email)
{
    $user = new customer();
    return $user->getUserByEmail($email);
}