<?php

function crm_date()
{
    return date("m/d/Y");
}

function current_user()
{
    global $current_user;

    return $current_user->first_name . " " . $current_user->last_name;
}

?>