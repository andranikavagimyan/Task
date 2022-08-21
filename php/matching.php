<?php

function getMatchingScore($a, $b) {
	$score = 0;
    if($a["Division"] === $b["Division"])
	    $score += 30;
    if(abs($a["Age"] - $b["Age"]) <= 5)	
	    $score += 30;
    if($a["Timezone"] === $b["Timezone"])	
    	$score += 40;
    return $score;
}

function createMatriz($employees) {
	$count = count($employees);
    $matriz = array();

 	for($i=0; $i<$count; $i++) {
        $matriz[$i][$i] = 0;
     	for($j=$i+1; $j<$count; $j++) {
        	$matriz[$i][$j] = getMatchingScore($employees[$i], $employees[$j]);
        	$matriz[$j][$i] = $matriz[$i][$j];
	 	}
 	}
    
    return $matriz;
}

function getSecondMax($row, $max) {
    $secondMax = -1;
    foreach($row as $item)
        if($item !== $max && $item > $secondMax)
            $secondMax = $item;
    return $secondMax;
}

function getMax($matriz) {
    $count = count($matriz);

    $max = array(
        'value'=> -1, 
        'index1'=> -1, 
        'index2'=> -1
    );

    for($i=0; $i<$count; $i++) {
        for($j=$i+1; $j<$count; $j++) {
            if($matriz[$i][$j] === $max['value']) {

                $row1 = -1;
                $row2 = -1;

                if($i === $max['index1']) {
                    $row1 = $j;
                    $row2 = $max['index2'];
                }
                if($j === $max['index1']) {
                    $row1 = $i;
                    $row2 = $max['index2'];
                }
                if($i === $max['index2']) {
                    $row1 = $j;
                    $row2 = $max['index1'];
                }
                if($j === $max['index2']) {
                    $row1 = $i;
                    $row2 = $max['index1'];
                }

                if($row1 !== -1 && $row2 !== -1) {
                    $secondNew = getSecondMax($matriz[$row1], $max['value']);
                    $secondOld = getSecondMax($matriz[$row2], $max['value']);    
                    if($secondNew < $secondOld) {
                        $max = array(
                            'value'=> $matriz[$i][$j], 
                            'index1'=> $i, 
                            'index2'=> $j
                        );
                    }
                }

            } else if($matriz[$i][$j] > $max['value']) {
                $max = array(
                    'value'=> $matriz[$i][$j], 
                    'index1'=> $i, 
                    'index2'=> $j
                );
            }
        }
    }

    return $max;
}

function removeMax($matriz, $a, $b) {
    $count = count($matriz);

    for($i=0; $i<$count; $i++) {
       $matriz[$a][$i] = 0;
       $matriz[$b][$i] = 0;
       $matriz[$i][$a] = 0;
       $matriz[$i][$b] = 0;
    }

    return $matriz;
}

    if (isset($_FILES["file"]))
    {
        $storagename = "uploaded_file.csv";
        move_uploaded_file($_FILES["file"]["tmp_name"], "../upload/" . $storagename);
        $content = fopen("../upload/" . $storagename, "r");
        $employees = [];
        $header = fgetcsv($content);
        while ($row = fgetcsv($content))
        {
            $employees[] = array_combine($header, $row);
        }

        $matriz = createMatriz($employees);

        $count = count($matriz);
        $pair = 0;
        $ans = 0;
        
        while($pair * 2  < $count) {
            $max = getMax($matriz);
            $ans += $max['value'];
            $pair++;
            $matriz = removeMax($matriz, $max['index1'], $max['index2']);
        }

        $result = $ans * 2 / $count;
        $data= array("employees"=>$employees, "matching"=>$result);
        echo json_encode($data);
        die;
    }
    else
    {
        echo "No file selected www<br />";
    }

?>