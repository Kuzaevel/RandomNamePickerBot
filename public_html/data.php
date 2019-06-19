<?php


$settings = include 'settings.php';
$settings = $settings['db'];
$dbConection = new PDO($settings['driver'].":host=" . $settings['host'] . ";dbname=" . $settings['dbname'],$settings['user'], $settings['pass']);
$dbConection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT * from stages_ppd where id= :id";
$stmt = $dbConection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array(
    ":id" => 1 // $_POST['id']
));
$arr = $stmt->fetch(PDO::FETCH_OBJ);

$hpoly = [];
$npoly = [];
$epoly = [];
$np_max = 0;

for($i=$arr->q_allowed_min; $i<=$arr->q_allowed_max; $i++){//$i=($i+50)){

    $hp = 0;
    $hp = $arr->hpoly0 +
        $arr->hpoly1  *  pow($i,1) +
        $arr->hpoly2  *  pow($i,2) +
        $arr->hpoly3  *  pow($i,3) +
        $arr->hpoly4  *  pow($i,4) +
        $arr->hpoly5  *  pow($i,5) +
        $arr->hpoly6  *  pow($i,6) +
        $arr->hpoly7  *  pow($i,7) +
        $arr->hpoly8  *  pow($i,8) +
        $arr->hpoly9  *  pow($i,9) +
        $arr->hpoly10 *  pow($i,10);

    $tmp = [];
    array_push($tmp,$i);
    array_push($tmp,$hp);
    array_push($hpoly, $tmp);

    $np = 0;
    $np = ($arr->npoly0 +
            $arr->npoly1  *  pow($i,1) +
            $arr->npoly2  *  pow($i,2) +
            $arr->npoly3  *  pow($i,3) +
            $arr->npoly4  *  pow($i,4) +
            $arr->npoly5  *  pow($i,5) +
            $arr->npoly6  *  pow($i,6) +
            $arr->npoly7  *  pow($i,7) +
            $arr->npoly8  *  pow($i,8) +
            $arr->npoly9  *  pow($i,9))/1000;

    if ($np_max<= $np) {
        $np_max = $np;
    }

    $tmp = [];
    array_push($tmp,$i);
    array_push($tmp,$np);
    array_push($npoly, $tmp);

    $ep = 0;
    $ep = ($arr->epoly0 +
        $arr->epoly1  *  pow($i,1) +
        $arr->epoly2  *  pow($i,2) +
        $arr->epoly3  *  pow($i,3) +
        $arr->epoly4  *  pow($i,4) +
        $arr->epoly5  *  pow($i,5) +
        $arr->epoly6  *  pow($i,6) +
        $arr->epoly7  *  pow($i,7) +
        $arr->epoly8  *  pow($i,8) +
        $arr->epoly9  *  pow($i,9));

    $tmp = [];
    array_push($tmp,$i);
    array_push($tmp,$ep);
    array_push($epoly, $tmp);
}

echo json_encode(['success' => true, 'hpoly' => $hpoly, 'npoly' => $npoly, 'epoly' => $epoly, 'np_max' => $np_max]);
