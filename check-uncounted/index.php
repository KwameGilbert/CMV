<?php
// Database connection
include '../database/db_connect.php';

// List of references
$references = [
    "Gili297735544", "Gili238680455", "Gili88914288", "Gili704248706", "Gili907946589",
    "Gili796571296", "Gili2396715", "Gili358263076", "Gili244178989", "Gili652291803",
    "Gili23258094", "Gili411666109", "Gili41330125", "Gili840879770", "Gili783556486",
    "Gili732218565", "Gili862345541", "Gili465426397", "Gili151398283", "Gili577597214",
    "Gili787290507", "Gili972138169", "Gili135401944", "Gili311700259", "Gili504762564",
    "Gili723879025", "257e9ywatoce9sg", "Gili559296874", "Gili989593805", "Gili854847528",
    "Gili756336963", "Gili966087988", "Gili832821521", "Gili933836953", "Gili627116186",
    "Gili972722372", "Gili837420012", "Gili602978271", "Gili537916717", "Gili126187144",
    "Gili976261526", "Gili690251296", "Gili562587387", "Gili833906338", "Gili827347658",
    "Gili91185508", "Gili345348395", "Gili342936772", "Gili550664490", "Gili942032229",
    "Gili518447342", "Gili306318210", "Gili527736161", "Gili363131197", "Gili699061284",
    "Gili836625012", "Gili477948326", "Gili369156555", "Gili416385215", "Gili465396628",
    "Gili781517555", "Gili992802414", "Gili273382944", "Gili111613003", "Gili482453651",
    "Gili484782871", "Gili927731121", "Gili36979520", "Gili360784556", "Gili316581492",
    "Gili530312214", "Gili852453158", "Gili627144057", "Gili454239431", "Gili550212257",
    "Gili777435379", "Gili278988361", "Gili235713064", "Gili557907045", "Gili76099026",
    "Gili440356423", "Gili434254122", "Gili903022617", "Gili502483376", "Gili602291575",
    "Gili566473490", "Gili338203261", "Gili358035312", "Gili135934981", "Gili844935235",
    "Gili796480127", "Gili923108047", "Gili488085084", "Gili891180228", "Gili740666732",
    "Gili43463274", "Gili558669314", "Gili487281907", "Gili401373637", "Gili305527559",
    "Gili863641838", "Gili173781777", "Gili275971059", "Gili24611344", "Gili448471932",
    "Gili4053752", "Gili378099019", "Gili457941715", "Gili419345804", "Gili425646829",
    "Gili547467743", "Gili708909224", "Gili698393143", "Gili908671172", "Gili69927777",
    "Gili574793225", "Gili336278454", "Gili180930033", "Gili841746390", "Gili963495023",
    "Gili804224085", "Gili256692588", "Gili437629971", "Gili595612590", "Gili857685590",
    "Gili675095058", "Gili772673278", "Gili47786721", "Gili83535900", "Gili233170175",
    "Gili205479131"
];

// Table and column to check
$table = "votes";
$column = "reference";

// Prepare the SQL statement
$sql = "SELECT $table.$column FROM $table WHERE $column = ?";

$stmt = $conn->prepare($sql);

$notFound = [];

foreach ($references as $reference) {
    // Bind parameters and execute the statement
    $stmt->bind_param("s", $reference);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 0) {
        $notFound[] = $reference;
    }
}

$stmt->close();
$conn->close();

// Output the references not found in the database
echo "References not found in the database: \n ";
$count = 1;
foreach ($notFound as $ref) {
    echo $count ." - " . $ref . " \n
     ";
    $count++;
}
?>
