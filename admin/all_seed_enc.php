<?include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
$errors = array();
$str = array();
$result = array();
$return = array();
$return2 = array();
if (empty($_POST["str"])) {
    echo '';
} else {
    //$str = str_replace(" ", "+", $_POST["str"]);
    $str = $_POST["str"];
    foreach ($str as $key => $val){
        $result[$key] = all_seed_enc($val);
    }
    foreach($result as $key => $val){
        $return = null;
        $return[$str[$key]] = $val;
        array_push($return2, $return);
    }
    echo json_encode($return2);
}?>