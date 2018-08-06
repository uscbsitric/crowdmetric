<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Blurt;
use App\Secondkey;
use Carbon\Carbon;
define("REPEATINGKEYWORD", 'ninja');  // the keyphrase
define("COMPLETECHARACTERSET", array('-1', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
                                     'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
                                     'T',  'U', 'V', 'W', 'X', 'Y', 'Z',
                                     '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
                                    )
      );
define("NONCEEXPIREINMINUTES", '5');


//algorithm adaptation https://en.wikipedia.org/wiki/Vigen%C3%A8re_cipher

class MachineUser extends Controller
{
    private function createVigenereTable()
    {
        // a total of 37 items (max index is 36)
        $alphabetsAndNumbers =  array('-1', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
            'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
            'T',  'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        );
        
        $vigenereTable = [];
        
        $alphabetsAndNumbersLength = count($alphabetsAndNumbers);
        
        $shiftCounter = 1;
        
        for($rowCounter = 0; $rowCounter < count($alphabetsAndNumbers); $rowCounter++) // row loop
        {
            $traverseCounter = $shiftCounter;
            
            /////echo " <br>";
            
            for($columnCounter = 0; $columnCounter < count($alphabetsAndNumbers); $columnCounter++) // column loop
            {
                if( (0 == $rowCounter && $columnCounter > 0) ||  // 0, n series
                    ($rowCounter > 0 && $columnCounter >= 0) // // x, y series  , where row is greater than zero and column is greater than or equal to 0
                    )
                {
                    if($rowCounter > 0)  // second row and onwards
                    {
                        if(37 == $traverseCounter)
                        {
                            $traverseCounter = 1; // reposition traverse counter to the 'A' character
                        }
                        
                        if(0 == $columnCounter) // print once
                        {
                            /////echo ' ' . $alphabetsAndNumbers[$traverseCounter];
                            $vigenereTable[$rowCounter][$columnCounter] = $alphabetsAndNumbers[$traverseCounter];
                        }
                        else // loop through till the end of the columnCounter count
                        {
                            /////echo ' ' . $alphabetsAndNumbers[$traverseCounter];
                            $vigenereTable[$rowCounter][$columnCounter] = $alphabetsAndNumbers[$traverseCounter];
                            $traverseCounter++;
                        }
                    }
                    else // first row
                    {
                        /////echo ' ' . $alphabetsAndNumbers[$columnCounter];
                        $vigenereTable[$rowCounter][$columnCounter] = $alphabetsAndNumbers[$columnCounter];
                    }
                }
                else // coordinate 0, 0  , print once
                {
                    /////echo '_';
                    $vigenereTable[$rowCounter][$columnCounter] = '_';
                }
            }
            
            
            if($rowCounter > 0)
            {
                $shiftCounter++;
            }
        }

        return $vigenereTable;
    }

    private function createMatchingLengthKeyword($wordToEncrypt)
    {
        $matchingLengthKeyword = '';
        $maxTraverseCounterLength = strlen(REPEATINGKEYWORD);
        
        for($counter = 0, $traverseCounter = 0; $counter < strlen($wordToEncrypt); $counter++, $traverseCounter++)
        {
            if($traverseCounter == $maxTraverseCounterLength) // reposition traverse counter to begining character of the repeating keyword
            {
                $traverseCounter = 0;
            }
            
            $matchingLengthKeyword .= REPEATINGKEYWORD[$traverseCounter];
        }
        
        return strtoupper($matchingLengthKeyword);
    }

    private function determineIntersection($wordToEncryptCharacter, $matchingLengthKeywordCharacter) // column == xCoordinate, row == yCoordinate
    {
        $cipherText = '';
        $vTable = $this->createVigenereTable();
        $xCoordinate = array_search($wordToEncryptCharacter, COMPLETECHARACTERSET);
        $yCoordinate = array_search($matchingLengthKeywordCharacter, COMPLETECHARACTERSET);
        $cipherText = $vTable[$xCoordinate][$yCoordinate];
        
        return $cipherText;
    }

    private function determineColumn($wordToDecryptCharacter, $matchingLengthKeywordCharacter) // column == xCoordinate, row == yCoordinate
    {
        $decipheredText = '';
        $indexOfWordToDecryptCharacter = array_search($wordToDecryptCharacter, COMPLETECHARACTERSET) + 1;
        $indexOfMatchingLengthKeywordCharacter = array_search($matchingLengthKeywordCharacter, COMPLETECHARACTERSET) + 1;
        
        // Therefore, to decrypt {\displaystyle R{\widehat {=}}17} R \widehat{=} 17 with key letter {\displaystyle E{\widehat {=}}4} E \widehat{=} 4 the calculation would result in {\displaystyle 13{\widehat {=}}N} 13 \widehat{=} N.
        //{\displaystyle 13=(17-4)\mod {26}} 13 = (17-4) \mod {26}  as per wikipedia

        $decipheredTextIndex = (($indexOfWordToDecryptCharacter - $indexOfMatchingLengthKeywordCharacter) % 37) + 1;
        $decipheredText = COMPLETECHARACTERSET[$decipheredTextIndex];
        
        return $decipheredText;
    }

    private function determineColumnV2($wordToDecryptCharacter, $matchingLengthKeywordCharacter) // column == xCoordinate, row == yCoordinate
    {
        
    }
    
    private function cipher($wordToEncrypt, $saveToDatbase = false)
    {
        $wordToEncrypt = strtoupper($wordToEncrypt);
        $cipheredText = '';
        $secretNumber = '';
        $matchingLengthKeyword = $this->createMatchingLengthKeyword($wordToEncrypt);
        
        for($counter = 0; $counter < strlen($wordToEncrypt); $counter++)  // loop through the matchingLengthKeyword
        {
            if($saveToDatbase)
            {
                $secretNumber .= strval(array_search($wordToEncrypt[$counter], COMPLETECHARACTERSET)) . '-';
            }
                                                                                 // matchingLengthKeyword == row, wordToEncrypt == column
            $cipheredText .= $this->determineIntersection($wordToEncrypt[$counter], $matchingLengthKeyword[$counter]);
        }

        if($saveToDatbase)
        {
            $secretNumber = rtrim($secretNumber, '-');
            $cipheredText .= '#' . $secretNumber;
        }

        return $cipheredText;
    }

    private function deCipher($wordToDecrypt)
    {
        $wordToDecrypt = strtoupper($wordToDecrypt);
        $decipheredText = '';
        $matchingLengthKeyword = $this->createMatchingLengthKeyword($wordToDecrypt);
        
        for($counter = 0; $counter < strlen($matchingLengthKeyword); $counter++)
        {
            $decipheredText .= $this->determineColumn($wordToDecrypt[$counter], $matchingLengthKeyword[$counter]); // column == xCoordinate, row == yCoordinate
        }
        
        return $decipheredText;
    }

    private function deCipher2($secretNumberObject)
    {
        $plainText = '';
        $secretNumbers = explode('-', $secretNumberObject->secretNumber);
        
        foreach($secretNumbers as $secretNumber)
        {
            $plainText .= COMPLETECHARACTERSET[$secretNumber];
        }
        
        return $plainText;
    }
    
    private function checkIfNonceIsExpired($nonce)
    {
        $userModel = new User();
        $user = $userModel->where('nonce', $nonce)->first();
        $nonceDate = Carbon::parse($user->updated_at->toDateTimeString());

        ($nonceDate->diffInMinutes(Carbon::now()) <= NONCEEXPIREINMINUTES) ? $validity = true : $validity = false;
        
        return $validity;
    }

    private $nonce = '';
    
    private $results = [];
    
    function getUser($username, $password)
    {
        $username = strtoupper($username);
        $password = strtoupper($password);
        $userModel = new User();
        $user = $userModel->where('username', $this->cipher($username) )
                          ->where('password', $this->cipher($password) )
                          ->first();
        
        return $user;
    }

    function register(Request $request)
    {
        $userModel = new User();

        $user = $this->getUser($request->input('username'), 
                               $request->input('password')
                              );

        if($user == null)
        {
            $cipherAndSecondKey = explode("#", $this->cipher($request->input('username'), true) );
            $userModel->username = $cipherAndSecondKey[0];
            $secondKey1 = $cipherAndSecondKey[1];

            $cipherAndSecondKey = explode("#", $this->cipher($request->input('password'), true) );
            $userModel->password = $cipherAndSecondKey[0];
            $secondKey2 =  $cipherAndSecondKey[1];

            $userModel->nonce = '';
            $userModel->save();

            DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // just needed to persist these records in the second key table w/o having to have a relationship
            $userModel->secondKeys()->saveMany([new Secondkey(['secret_number' => $secondKey1, 'blurt_id' => '0']),
                                                new Secondkey(['secret_number' => $secondKey2, 'blurt_id' => '0'])
                                               ]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // just needed to persist these records in the second key table w/o having to have a relationship

            return response()->json(['result' => 'Username and Password Saved']);
        }
        else
        {
            return response()->json(['result' => 'Error, duplicate username and password combination']);
        }
    }

    function authorize(Request $request)
    {
        $user =  $this->getUser($request->input('username'), 
                                $request->input('password'));

        if($user != null)
        {
            $userPass = $request->input('username').$request->input('password');
            $user->nonce = $this->cipher($userPass.rand());
            $user->save();

            return response()->json(['result' => $user->nonce]);
        }
        else
        {
            return response()->json(['result' => 'Error, such a user does not exist.']);
        }
    }

    function input(Request $request)
    {
        if( $this->checkIfNonceIsExpired($request->input('nonce')) )
        {
            $userModel = new User();
            $nonceOwner = $userModel->where('nonce', $request->input('nonce'))->first();
            $cipheredAndSecondKey = explode("#", $this->cipher($request->input('inputString'), true) );
            $blurtModel = new Blurt(['input_string' => $cipheredAndSecondKey[0]]);
            $blurtModel = $nonceOwner->blurts()->save($blurtModel);
            DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // because there will never be a user id 0, 
                                                        // its just an indication that all user id = 0 are second keys that belong to blurts only
            $secondKeyModel = new Secondkey(['user_id' => '0',
                                             'blurt_id' => $blurtModel->id,
                                             'secret_number' => $cipheredAndSecondKey[1]
                                            ]);
            $secondKeyModel = $blurtModel->secondKeys()->save($secondKeyModel);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json(['result' => 'insert success']);
        }
        else
        {
            return response()->json(['result' => 'invalid nonce']);
        }
    }

    function blurt(Request $request)
    {
        $this->nonce = $request->input('nonce');
        $blurts = [];

        if( $this->checkIfNonceIsExpired($this->nonce) )
        {
            // return all strings given the nonce in the correct sequence
            DB::transaction(function(){
                // lock down the affected rows while reading and fetching
                $this->results = DB::select('SELECT cipheredandkey.secretNumber
                                             FROM users
                                             LEFT JOIN (SELECT blurts.user_id AS userBlurt,
                                                               secondkeys.secret_number AS secretNumber
                                                        FROM blurts
                                                        LEFT JOIN secondkeys
                                                        ON blurts.id = secondkeys.blurt_id
                                                        FOR UPDATE
                                                        ORDER BY secondkeys.updated_at) AS cipheredandkey
                                             ON users.id = cipheredandkey.userBlurt 
                                             WHERE users.nonce = ?
                                             FOR update', [$this->nonce]);

                // delete affected rows
                $user = new User();
                $user->where('nonce', $this->nonce)->first()->blurts()->delete();
                // unlock the locks that where in place previously
            }, 10); // reattempt maximum 10 times when in a deadlock
            
            // decipher each result
            foreach($this->results as $result)
            {
                $blurts[] = $this->deCipher2($result);
            }
            
            return response()->json(['result' => $blurts]);
        }
        else
        {
            return response()->json(['result' => 'invalid nonce']);
        }

        

    }
}
