<?php

use Carbon\Carbon;

// Empty class for backward compatibility
class emtysession {
    
}

if (! function_exists('FixData')) {
    
}

if (! function_exists('accounting_check')) {
    function accounting_check()
    {
        return 'no';
    }
}

if (! function_exists('get_current_session')) {

}

if (! function_exists('count_student')) {

}

if (! function_exists('family_check')) {
}

if (! function_exists('branchesapi')) {
}

if (! function_exists('gettyperesult')) {
}

if (! function_exists('sendmesssageictcore')) {
    function sendmesssageictcore($first_name, $last_name, $to, $message, $m_name)
    {
        // TODO: Implement with your SMS provider (Twilio, AWS SNS, etc.)
        \Log::info("SMS to {$to}: {$message}");
        
        return 'logged';
    }
}

if (! function_exists('Voucharcheck')) {
}

if (! function_exists('gfee_setup')) {
}

if (! function_exists('gsection_name')) {
}

if (! function_exists('gclass_name')) {
}

if (! function_exists('teacher_details_f')) {
}

if (! function_exists('getdatainvoice')) {
}

if (! function_exists('getrefralindfo')) {
}

if (! function_exists('getinstitueinfo')) {
}
