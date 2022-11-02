<?php
session_start();
$datasession = @$_SESSION['array'];
$putdatasession = @$_SESSION['finaldata'];
$filenameget = @$_SESSION['filename'];
function add_quotes($str)
{
    return sprintf('"%s"', $str);
}

if (isset($_POST['submit1'])) {
    $filename = basename($filenameget, "");

    $text = "";
    $j = 0;
    foreach ($putdatasession as $fields) {
        if ($j < 2) {
            $numItems = count($fields);
            $i = 0;
            foreach ($fields as $value) {
                if (++$i === $numItems) {
                    $text .= '"' . $value . '"';
                } else {
                    if ($value == "1") {
                        $text .= '"' . $value . '",';
                    } else if (is_numeric($value)) {
                        $text .= $value . ",";
                    } else {
                        $text .= '"' . $value . '",';
                    }
                }
            }
            $text .= "\n";
        } else {
            $numItems = count($fields);
            $i = 0;
            foreach ($fields as $value) {
                if (++$i === $numItems) {
                    $text .= '"' . $value . '"';
                } else {
                    if (is_numeric($value)) {
                        $text .= $value . ",";
                    } else {
                        $text .= '"' . $value . '",';
                    }
                }
            }
            $text .= "\n";
        }
        $j++;
    }
    $fh = fopen("fullfiled/" . $filename, "w") or die("Could not open log file.");
    fwrite($fh, $text) or die("Could not write file!");
    fclose($fh);
    unlink($filenameget);
    echo "<script>alert('les données sont validée ');</script>";
    session_destroy();
    echo "<script>window.location = 'index.php'; </script>";
}
