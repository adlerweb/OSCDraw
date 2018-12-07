<?php

    /*
        OSCDraw

        2018 Florian Knodt · https://www.adlerweb.info

        Licensed under the Apache License, Version 2.0 (the "License");
        you may not use this file except in compliance with the License.
        You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

        Unless required by applicable law or agreed to in writing, software
        distributed under the License is distributed on an "AS IS" BASIS,
        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
        See the License for the specific language governing permissions and
        limitations under the License.
    */

    //Unsupported:
    // - Curves
    // - mixing absolute and relative positioning

    $in = $argv[1];

    $detail = 5;

    $gmax_x = 0;
    $gmin_x = 99999;
    $gmax_y = 0;
    $gmin_y = 99999;

    $data = file_get_contents($in);

    $out = array(array(),array());

    //h 11.783747 v -12.21026
    $regex = array(
        array(
            '/ h (-?[\d\.]+)/',
            '/ v (-?[\d\.]+)/',
            '/ H (-?[\d\.]+)/',
            '/ V (-?[\d\.]+)/',
            '/ L/',
            '/ l/'
        ),array(
            ' \1,0',
            ' 0,\1',
            '',
            '',
            '',
            ''
        )
    );
    $data = preg_replace($regex[0], $regex[1], $data);
    echo $data;

    preg_match_all('/d="(m|M)( ([\d\.\-]+),([\d\.\-]+))+( z)?"/', $data, $match);
    unset($data);

    for($l=0; $l<count($match[0]); $l++) {
        preg_match_all('/( ([\d\.\-]+),([\d\.\-]+))/', $match[0][$l], $match2);
        
        $rel = false;
        if($match[1][$l] == 'm') $rel = true;

        if($rel) {
            for($i=1; $i<count($match2[0]); $i++) {
                $match2[3][$i] = $match2[3][$i-1] + $match2[3][$i];
                $match2[2][$i] = $match2[2][$i-1] + $match2[2][$i];
            }
        }

        $lx = ($match2[3][0]);
        $ly = ($match2[2][0]);
        $out[0][] = $lx;
        $out[1][] = $ly;

        if($lx > $gmax_x) $gmax_x = $lx;
        if($ly > $gmax_y) $gmax_y = $ly;
        if($lx < $gmin_x) $gmin_x = $lx;
        if($ly < $gmin_y) $gmin_y = $ly;

        if($match[5][$l] == ' z') {
            $match2[0][] = true;
            $match2[3][] = $match2[3][0];
            $match2[2][] = $match2[2][0];
        }
        
        echo 'Next Point - S - X:'.round($lx).' Y:'.round($ly)."\n";

        for($i=1; $i<count($match2[0]); $i++) {

            echo 'Next Point - '.$match[1][$l].' - X:'.round($match2[2][$i]).' Y:'.round($match2[3][$i])."\n";

            $max = 0;

            $ix = false;
            $iy = false;

            $dx = ($match2[3][$i]) - $lx;
            if($dx < 0) { $dx *= -1; $ix=true; }
            if($max < $dx) $max = $dx;

            $dy = ($match2[2][$i]) - $ly;
            if($dy < 0) { $dy *= -1; $iy=true; }
            if($max < $dy) $max = $dy;

            $max *= $detail;
            
            $sx = $dx/$max;
            $sy = $dy/$max;
            if($ix) $sx *= -1;
            if($iy) $sy *= -1;

            for($j=0; $j<$max; $j++) {
                $out[0][] = (($lx + ($j*$sx)));
                $out[1][] = (($ly + ($j*$sy)));
            }

            $lx = ($match2[3][$i]);
            $ly = ($match2[2][$i]);

            if($lx > $gmax_x) $gmax_x = $lx;
            if($ly > $gmax_y) $gmax_y = $ly;
            if($lx < $gmin_x) $gmin_x = $lx;
            if($ly < $gmin_y) $gmin_y = $ly;
        }
    }

    $gmax_x = ceil($gmax_x);
    $gmin_x = floor($gmin_x);
    $gmax_y = ceil($gmax_y);
    $gmin_y = floor($gmin_y);
    echo "Global Max: $gmax_x - $gmax_y\n";
    echo "Global Min: $gmin_x - $gmin_y\n";

    $korr_x = 255/($gmax_x-$gmin_x);
    $korr_y = 255/($gmax_y-$gmin_y);

    $ar = 1;
    $ar = $korr_x / $korr_y;

    echo "Korr: $korr_x - $korr_y - $ar\n";
    for($i=0; $i<count($out[0]); $i++) {
        $out[0][$i] -= $gmin_x;
        $out[1][$i] -= $gmin_y;

        $out[0][$i] *= $korr_x;
        $out[1][$i] *= $korr_y;

        if($ar > 1) {
            $out[0][$i] /= $ar;
        }else{
            $out[1][$i] *= $ar;
        }
        
        $out[0][$i] = 255-floor($out[0][$i]);
        $out[1][$i] = floor($out[1][$i]);
    }

    echo json_encode($out)."\n";
?>