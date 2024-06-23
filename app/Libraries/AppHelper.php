<?php

namespace App\Libraries;

use App\Models\User;
use App\Models\Setting;

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

    public static function officeHoursOnly()
    {
        $query = new Setting; 
        $officeHoursOnly = $query->where('name', 'office_hours_only')->first();

        if (!empty($officeHoursOnly) && $officeHoursOnly['value']) {
            return true;
        }

        return false;
    }

    public static function weekendsAllowed()
    {
        $query = new Setting; 
        $weekends = $query->where('name', 'allow_weekends')->first();

        if (!empty($weekends) && $weekends['value']) {
            return true;
        }

        return false;
    }

    public static function pastOfficeHours()
    {
        $query = new Setting; 

        if (self::officeHoursOnly()) {
            $start = $query->where('name', 'office_hours_start')->first();
            $end = $query->where('name', 'office_hours_end')->first();

            if (!empty($start) && !empty($end)) {

                if (strtotime($start['value']) === false || strtotime($end['value']) === false) {
                    return false;
                }

                $start = now()->parse($start['value']);
                $end = now()->parse($end['value']);

                if (now()->gte($start) && now()->lte($end)) {
                    return false;
                }

                return true;
            }
        }

        return false;
    }
}
