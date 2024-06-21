<?php

namespace App\Libraries;

class AppHelper
{
    public static function saved()
    {
        return "Submitted record has been saved!";
    }

    public static function deleted()
    {
        return "Record has been permanently deleted.";
    }

    public static function archived()
    {
        return "Record has been archived.";
    }

    public static function updated()
    {
        return "Record has been updated.";
    }

    public static function exists()
    {
        return "Submitted data already exists";
    }

    public static function notAllowed()
    {
        return "You are not allowed to delete this data. This record has been used to other pages hence, for security purposes, the system automatically prevents this from being deleted.";
    }

    public static function restored()
    {
        return "Deleted record has been restored.";
    }

}
