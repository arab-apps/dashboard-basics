<?php
namespace App\Traits;
use Illuminate\Support\Str;
trait ValidPassword{


    function generateValidPassword() {
        $password = '';
    
        // Generate a random uppercase letter
        $uppercase = Str::random(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $password .= $uppercase;
    
        // Generate a random special character
        $specialChar = Str::random(1, '!@#$%^&*()_+{}[]:;<>,.?/~\\-');
        $password .= $specialChar;
    
        // Generate random lowercase letters and digits to fill the remaining characters
        $remainingLength = 8 - strlen($password); // Minimum length of 8 characters
        $lowercaseDigits = Str::random($remainingLength, 'abcdefghijklmnopqrstuvwxyz0123456789');
        $password .= $lowercaseDigits;
    
        // Shuffle the password to mix up the characters
        $password = str_shuffle($password);
    
        return $password;
    }
}