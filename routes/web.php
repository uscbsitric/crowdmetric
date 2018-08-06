<?php

/********************************************************************************************
define("REPEATINGKEYWORD", 'ninja');  // the keyphrase
define("COMPLETECHARACTERSET", array('-1', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
                                     'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
                                     'T',  'U', 'V', 'W', 'X', 'Y', 'Z',
                                     '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
                                    )
      );
*********************************************************************************************/

//algorithm adaptation https://en.wikipedia.org/wiki/Vigen%C3%A8re_cipher

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**********************************
function createVigenereTable()
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
                        /////echo $alphabetsAndNumbers[$traverseCounter];
                        $vigenereTable[$rowCounter][$columnCounter] = $alphabetsAndNumbers[$traverseCounter];
                    }
                    else // loop through till the end of the columnCounter count
                    {
                        /////echo $alphabetsAndNumbers[$traverseCounter];
                        $vigenereTable[$rowCounter][$columnCounter] = $alphabetsAndNumbers[$traverseCounter];
                        $traverseCounter++;
                    }
                }
                else // first row
                {
                    /////echo $alphabetsAndNumbers[$columnCounter];
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

function createMatchingLengthKeyword($wordToEncrypt)
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

function determineIntersection($wordToEncryptCharacter, $matchingLengthKeywordCharacter) // column == xCoordinate, row == yCoordinate
{
    $cipherText = '';
    $vTable = createVigenereTable();
    $xCoordinate = array_search($wordToEncryptCharacter, COMPLETECHARACTERSET);
    $yCoordinate = array_search($matchingLengthKeywordCharacter, COMPLETECHARACTERSET);
    $cipherText = $vTable[$xCoordinate][$yCoordinate];
    
    return $cipherText;
}

function determineColumn($wordToDecryptCharacter, $matchingLengthKeywordCharacter) // column == xCoordinate, row == yCoordinate
{
    $decipheredText = '';
    $vTable = createVigenereTable();
    $indexOfWordToDecryptCharacter = array_search($wordToDecryptCharacter, COMPLETECHARACTERSET) + 1;
    $indexOfMatchingLengthKeywordCharacter = array_search($matchingLengthKeywordCharacter, COMPLETECHARACTERSET) + 1;
    
    // Therefore, to decrypt {\displaystyle R{\widehat {=}}17} R \widehat{=} 17 with key letter {\displaystyle E{\widehat {=}}4} E \widehat{=} 4 the calculation would result in {\displaystyle 13{\widehat {=}}N} 13 \widehat{=} N.
    //{\displaystyle 13=(17-4)\mod {26}} 13 = (17-4) \mod {26}  as per wikipedia

    $decipheredTextIndex = (($indexOfWordToDecryptCharacter - $indexOfMatchingLengthKeywordCharacter) % 37) + 1;
    $decipheredText = COMPLETECHARACTERSET[$decipheredTextIndex];

    return $decipheredText;
}

function cipher($wordToEncrypt)
{
    $cipheredText = '';
    $matchingLengthKeyword = createMatchingLengthKeyword($wordToEncrypt);
    
    for($counter = 0; $counter < strlen($wordToEncrypt); $counter++)  // loop through the matchingLengthKeyword
    {
        // matchingLengthKeyword == row, wordToEncrypt == column
        $cipheredText .= determineIntersection($wordToEncrypt[$counter], $matchingLengthKeyword[$counter]);
    }

    return $cipheredText;
}

function deCipher($wordToDecrypt)
{
    $decipheredText = '';
    $matchingLengthKeyword = createMatchingLengthKeyword($wordToDecrypt);

    for($counter = 0; $counter < strlen($matchingLengthKeyword); $counter++)
    {
        $decipheredText .= determineColumn($wordToDecrypt[$counter], $matchingLengthKeyword[$counter]); // column == xCoordinate, row == yCoordinate
    }

    return $decipheredText;
}
************************************/

Route::get('/', function () {
    /////$vTable = createVigenereTable();
    /////$cipheredText = cipher(strtoupper('globe'));
    /////$cipheredText = 'N16JCX';
    /////$decipheredText = decipher($cipheredText);

    return view('welcome');
});
