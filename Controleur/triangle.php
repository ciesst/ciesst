<?php
//allow to see if a point is in a triangle or not

function pointInTriangle($x, $y, $x1, $y1, $x2, $y2, $x3, $y3)
{   
    return side($x, $y, $x1, $y1, $x2, $y2, $x3, $y3) &&
           side($x, $y, $x1, $y1, $x3, $y3, $x2, $y2) &&
           side($x, $y, $x3, $y3, $x2, $y2, $x1, $y1);
}

function side($x, $y, $x1, $y1, $x2, $y2, $x3, $y3)
{
    if ($x1 - $x2 != 0) {
        $k    = ($y1 - $y2) / ($x1 - $x2);
        $s1   = $y3 - $y1 - $k * ($x3 - $x1);
        $s2   = $y - $y1 - $k * ($x - $x1);
    }
    else {
        $s1   = $x3 - $x1;
        $s2   = $x - $x1;
    }
    return ($s1 * $s2) >= 0;
}



?>