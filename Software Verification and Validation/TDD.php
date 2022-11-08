<?php
// Request:- Develope  a Function Named getGrade that returns the Alphabetic equivalent of the numerical input provided to the function 
// e.g 90=A+, 85=A, 80=A-,50=C 
/*
Functional Requirements:
•	The identifier “getGrade” must point to a function 
•	The function must accept a Numerical value 
•   The function must check the parameter is positive integer >=0 and <=100
•	REQ 4 :- The output value from the function must be an alphabetic equivalent of the numerical value 
*/
// echo getGrade();
// function getGrade(){}
// function getGrade(){}
$values=array(
    20,30,40,45,50,60,65,70,75,80,85,90
);
// function getGrade($value){
//     if(intval($value)&&$value>=0&&$value<=100){
     
//     }
// }
// $grade=getGrade(80);
// echo "Grade equivalent of 80 : ".$grade;
// // echo getGrade($values);
// // function getGrade($value){
// //     if(intval($value)){
// //     }
// // }


// function getGrade($value){
//     if(intval($value)&&$value>=0&&$value<=100){
//         if( $value<30){
//             return "F";
//         }else if( 30<=$value&& $value<40){
//             return "Fx";
//         }else if( 40<=$value && $value<45){
//             return "D";
//         }else if( 45<=$value && $value<50){
//             return "C-";
//         }else if( 50<=$value && $value<60){
//             return "C";
//         }else if( 60<=$value && $value<65){
//             return "C+";
//         }else if( 65<=$value && $value<70){
//             return "B-";
//         }else if( 70<=$value && $value<75){
//             return "B";
//         }else if( 75<=$value && $value<80){
//             return "B+";
//         }else if( 80<=$value && $value<85){
//             return "A-";
//         }else if( 85<=$value && $value<90){
//             return "A";
//         }else if( $value>=90){
//             return "A+";        
//         }  }}
//         for($i=0;$i<count($values);$i++){
//             echo "Grade for ".$values[$i]."    is     :     ".getGrade($values[$i])."<br>";
//             }








// $values=array(
//     20,30,40,45,50,60,65,70,75,80,85,90


// );
function getGrade($value){
    global $grade;
    if(intval($value)&&$value>=0&&$value<=100){
     switch($value){
         case $value<30:
            return "F";
        case 30<=$value&& $value<40:
            return "Fx";
        case 40<=$value && $value<45:
            return "D";
        case 45<=$value && $value<50:
            return "C-";
        case 50<=$value && $value<60:
            return "C";
        case 60<=$value && $value<65:
            return "C+";
        case 65<=$value && $value<70:
            return "B-";
        case 70<=$value && $value<75:
            return "B";
        case 75<=$value && $value<80:
            return "B+";
        case 80<=$value && $value<85:
            return "A-";
        case 85<=$value && $value<90:
            return "A";
        case $value>=90:
            return "A+";        
     }    
    }
}
// echo "the grade equivalent calculator  ".getGrade(80);
for($i=0;$i<count($values);$i++){
echo "Grade for ".$values[$i]."    is     :     ".getGrade($values[$i])."<br>";
}
?>