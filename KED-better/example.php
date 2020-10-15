<?php
function everything_in_tags($string, $tagname)
{
    // first regex: find title of the song in the title
    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
    preg_match($pattern, $string, $matches);
    return $matches[1];
}

$orig1 = preg_split( "/ (\n|' ') /", file_get_contents('file1.xml') );
$orig1 = preg_replace('/\s+/', '_', $orig1[0]);
$title1 = everything_in_tags($orig1, 'title');


$orig2 = preg_split( "/ (\n|' ') /", file_get_contents('file3.xml') );
$orig2 = preg_replace('/\s+/', '_', $orig2[0]);
$title2 = everything_in_tags($orig2, 'title');

// second regex, change all '_' to ' ' (spaces)
$title1 = preg_replace('/(?<! )\_(?! )/', ' ', $title1);
$title2 = preg_replace('/(?<! )\_(?! )/', ' ', $title2);


$INDEX_PHP= "example.php";

include_once("class_textEditor.php");
include_once("class_diffTool.php");

$res = '';

$res .= "
        <!DOCTYPE html>
        <head>
            <meta charset=\"utf-8\">
            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <link  href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />
            <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\" ></script>
            <title>Diff Tool</title>
        </head>
        <body>";

        $tool = new diffTool;
        $res .= $tool->renderDiffTool("file1.xml","file2.xml");

$res .= "</body>";


echo $res;


// table 1 
$orig      =  explode("\n", file_get_contents('file1.xml') );
$gettingString = implode("\n", $orig);
$words = str_word_count($gettingString, 1);
$words = array_count_values($words);
arsort($words);
$pretable = '<table id="pre01">
          <tr>
            <th>';
$pretable .= $title1;
$pretable .=   ' - TOP 10 WORDS</th>
          </tr>';

$table = '<table id="t01">
          <tr>
            <th>Word</th>
            <th>Count</th>
          </tr>';
$count = 0;
foreach ($words as $key => $value) {
            $count++;
            if($count < 11){
            $table .= 
            '<tr>
                <td><i>\'';
            $table .= $key;
            $table .=    
                '\'</i></td>
                <td>';
            $table .= $value;
            $table .=   '</td>
            </tr>';
            }
          }          

$table .=        '</table>';



//table 2

$orig      =  explode("\n", file_get_contents('file3.xml') );
$gettingString = implode("\n", $orig);
$words = str_word_count($gettingString, 1);
$words = array_count_values($words);
arsort($words);
$pretable2 = '<table id="pre02">
          <tr>
            <th>';
$pretable2 .= $title2;
$pretable2 .= ' - TOP 10 WORDS</th>
          </tr>';

$table2 = '<table id="t02">
          <tr>
            <th>Word</th>
            <th>Count</th>
          </tr>';
$count = 0;
foreach ($words as $key => $value) {
            $count++;
            if($count < 11){
            $table2 .= 
            '<tr>
                <td><i>\'';
            $table2 .= $key;
            $table2 .=    
                '\'</i></td>
                <td>';
            $table2 .= $value;
            $table2 .=   '</td>
            </tr>';
            }
          }          

$table2 .=        '</table>';


// table 3 
$orig      =  explode("\n", file_get_contents('file1.xml') );
$gettingString = implode("\n", $orig);
$words = str_word_count($gettingString, 1);
$words = array_count_values($words);
arsort($words);
$pretable3 = '<table id="pre03">
          <tr>
            <th>
            ';
$pretable3 .= $title1;
$pretable3 .= '
             - TOP 10 WORDS (longer than 3 characters)</th>
          </tr>';

$table3 = '<table id="t03">
          <tr>
            <th>Word</th>
            <th>Count</th>
          </tr>';
$count = 0;
foreach ($words as $key => $value) {
            if($count < 11 && strlen($key) > 3){
            $count++;
            $table3 .= 
            '<tr>
                <td><i>\'';
            $table3 .= $key;
            $table3 .=    
                '\'</i></td>
                <td>';
            $table3 .= $value;
            $table3 .=   '</td>
            </tr>';
            }
          }          

$table3 .=        '</table>';


//table 4

$orig      =  explode("\n", file_get_contents('file3.xml') );
$gettingString = implode("\n", $orig);
$words = str_word_count($gettingString, 1);
$words = array_count_values($words);
arsort($words);
$pretable4 = '<table id="pre04">
          <tr>
            <th>';
$pretable4 .= $title2;
$pretable4 .= '

             - TOP 10 WORDS (longer than 3 characters)</th>
          </tr>';

$table4 = '<table id="t04">
          <tr>
            <th>Word</th>
            <th>Count</th>
          </tr>';
$count = 0;
foreach ($words as $key => $value) {
            
            if($count < 11 && strlen($key) > 3){
            $count++;
            $table4 .= 
            '<tr>
                <td><i>\'';
            $table4 .= $key;
            $table4 .=    
                '\'</i></td>
                <td>';
            $table4 .= $value;
            $table4 .=   '</td>
            </tr>';
            }
          }          

$table4 .=        '</table>';



echo $pretable2;
echo $pretable;
echo $table2;
echo $table;

echo "<div class='space'></div>";

echo $pretable4;
echo $pretable3;
echo $table4;
echo $table3;



// find words in first song

        $orig = preg_split( "/ (\n|' ') /", file_get_contents('file1.xml') );
        $orig = preg_replace('/\s+/', '_', $orig[0]);
        $orig = explode("_",$orig);

        $findWord = ['nigga', 'fuck', 'bitch'];

        foreach ($findWord as $key => $foundWord) {
          $exactWord[$foundWord] = 0;  
          $countCharacters[$foundWord] = 0;
        }

        // counting exactWords
        foreach ($orig as $key => $w) {

          $d=array_shift($orig);
          $search = substr($d,0);

          foreach ($findWord as $counter => $thisWord) {
            # code...
          

          // third regex: find exact match

          if(preg_match("/\b($thisWord)\b/", $search)){
            $exactWord[$thisWord]++;
          }

          // fourth regex:  find any match 
          if(preg_match("/($thisWord)/i", $search)){
            $countCharacters[$thisWord]++;
          } 

          }

        }
        //echo $exactWord[$thisWord];
        echo " <u><i>*KONIEC TABELI  POROWNAWCZEJ</u></i><br>";
        echo "<br><div class='howMany'> Ile razy pojawiło się <b>całe słowo</b> w piosence  ";
        echo $title1;
        echo ":<br><br>";
        foreach ($exactWord as $key => $value) {
            echo '\'<b><i>'.ucfirst($key).'\'</i></b> pojawiło się <b>'.$value.'</b> raz(y)<br>';
        }
        echo "<br><br>";
        echo "Ile razy pojawił się dany <b>ciąg znaków</b> w piosence ";
        echo $title1;
        echo ":<br><br>";

        foreach ($countCharacters as $key => $value) {
            echo '\'<b><i>'.ucfirst($key).'\'</i></b> pojawiło się <b>'.$value.'</b> raz(y)<br>';
        }
        echo "</div>";
        echo "<br><br>----------------------------------------------------------";




        // find words in first song

                $orig = preg_split( "/ (\n|' ') /", file_get_contents('file3.xml') );
                $orig = preg_replace('/\s+/', '_', $orig[0]);
                $orig = explode("_",$orig);

                $findWord = ['nigga', 'fuck', 'bitch'];

                foreach ($findWord as $key => $foundWord) {
                  $exactWord[$foundWord] = 0;  
                  $countCharacters[$foundWord] = 0;
                }

                // counting exactWords
                foreach ($orig as $key => $w) {

                  $d=array_shift($orig);
                  $search = substr($d,0);

                  foreach ($findWord as $counter => $thisWord) {
                    # code...
                  

                  // third regex: find exact match

                  if(preg_match("/\b($thisWord)\b/", $search)){
                    $exactWord[$thisWord]++;
                  }

                  // fourth regex:  find any match 
                  if(preg_match("/($thisWord)/i", $search)){
                    $countCharacters[$thisWord]++;
                  } 

                  }

                }
                //echo $exactWord[$thisWord];
                echo "<br><div class='howMany'> Ile razy pojawiło się <b>całe słowo</b> w piosence  ";
                echo $title2;
                echo ":<br><br>";
                foreach ($exactWord as $key => $value) {
                    echo '\'<b><i>'.ucfirst($key).'\'</i></b> pojawiło się <b>'.$value.'</b> raz(y)<br>';
                }
                echo "<br><br>";
                echo "Ile razy pojawił się dany <b>ciąg znaków</b> w piosence ";
                echo $title2;
                echo ":<br><br>";

                foreach ($countCharacters as $key => $value) {
                    echo '\'<b><i>'.ucfirst($key).'\'</i></b> pojawiło się <b>'.$value.'</b> raz(y)<br>';
                }
                echo "</div>";
?>
