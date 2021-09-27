
<?php
    $_POST = json_decode(file_get_contents('php://input'), true);
    // header('Content-Type: application/json; charset=utf-8');
    function getAction($action){
        return $_POST['action'] === $action;
    }
    // function getUserId(){
    //     $login = $_COOKIE['login'];
    //     $password = $_COOKIE['password'];
    //     $user = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' AND password='$password' LIMIT 1");
    //     return $user['id'];
    // }
    function getUser($conn){
        $userInfo = array();
        // $login = $_COOKIE['login'];
        // $password = $_COOKIE['password'];
        $login = 'login';
        $password = 'password';
        $user = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' AND password='$password' LIMIT 1");
        $avatarID = 0;
        foreach ($user as $result) {
            // array_push($userInfo, $result);
            $avatarID = $result['avatarid'];
            unset($result['avatarid']);
            unset($result['"password"']);
            $userInfo = $result;
        }

        $avatarInfo = mysqli_query($conn,"SELECT * FROM `Avatar` WHERE id='$avatarID' LIMIT 1");
        foreach ($avatarInfo as $result) {
            $userInfo['avatarInfo'] = $result;
        }

        return $userInfo;
    }


    $servername = 'clickdes.mysql.tools';
    $username = 'clickdes_vuebet';
    $password = 'haTd2H)-42';

    $conn = mysqli_connect($servername, $username, $password, $username);
    $conn->set_charset("utf8");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function createBet($conn,$name,$variations,$count){
        mysqli_query($conn,"INSERT INTO Rate (name,statusid,count) VALUES ('$name',0,$count)");
        $id = mysqli_insert_id($conn);
        foreach ($variations as $key => $value) {
            mysqli_query($conn,"INSERT INTO Variation (name,rateid) VALUES ('$value',".$id.")");
        }
        return $id;
    }

    function getRate($conn,$ratId){
        $return = array();
        $results = mysqli_query($conn,"SELECT * FROM `Rate` WHERE id=$ratId");
        foreach ($results as $result) {
            $return = $result;
        }
        return $return;
    }

    function getVariation($conn,$rateid){
        $return = array();
        $results = mysqli_query($conn,"SELECT * FROM `Variation` WHERE rateid='$rateid'");
        
        foreach ($results as $result) {
            array_push($return, $result);
        }
        return $return;
    }

    function getWin($conn,$ratId){
        $return = null;
        $results = mysqli_query($conn,"SELECT * FROM `Win` WHERE rateid='$ratId'");
        foreach ($results as $result) {
            $return = $result;
        }
        return $return;
    }

    function withdrawRate($conn,$ratId){
        $ratId = intval($ratId);
        $jsonArray = array();
        $jsonArray['rateinfo'] = array();
        // $rateid = intval($jsonArray['rateinfo']['id']);
        $jsonArray['userinfo'] = array();
        $jsonArray['uservariations'] = array();
        $jsonArray['variations'] = array();

        
        $jsonArray['rateinfo'] = getRate($conn,$ratId);
        
        
        $jsonArray['variations'] = getVariation($conn,$ratId);
        

        $results = mysqli_query($conn,"SELECT * FROM `Users`");
        foreach ($results as $result) {
            $resultCache = $result;
            $userID = intval($result['id']);
            unset($resultCache['password']);
            unset($resultCache['avatarid']);
            array_push($jsonArray['userinfo'],$resultCache);
            
            $resultsIn = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE rateid='$ratId' AND userid='$userID'");
            foreach ($resultsIn as $resultIn) {
                array_push($jsonArray['uservariations'],$resultIn);
            }

        }

        $jsonArray['wininfo'] = getWin($conn,$ratId);


        $jsonArray['currentUser'] = getUser($conn);
        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function getUserRate($conn,$userId,$ratId){
        $results = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE userid='$userId' AND rateid='$ratId'");
        $jsonArray = array();
        foreach ($results as $result) {
            array_push($jsonArray,$resultCache);
        }

        return $jsonArray;
    }

    function placeBet($conn,$rateId,$varId){
        $user = getUser($conn);
        $userId = $user['id'];
        $result = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE rateid='$rateId' AND userid='$userId'");
        
        if($result->num_rows > 0){
            return '{"errorId":1}';
        }

        mysqli_query($conn,"INSERT INTO `UsersRate` (userid,rateid,varid) VALUES ('$userId','$rateId','$varId')");
        return '{"errorId":0}';

    }
    function history($conn){
        // var_dump($conn);
        $jsonArray = array();
        $results = mysqli_query($conn,"SELECT * FROM `Rate`");
        foreach ($results as $result) {
            array_push($jsonArray,$result);
        }
        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function register($conn,$avatarid,$name,$login,$password){
        $jsonArray = array();
        $result = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' OR name='$name'");
        
        if($result->num_rows > 0){
            return '{"errorId":2}';
        }
        $password = md5($password);
        $userResult = mysqli_query($conn,"INSERT INTO `Users` (avatarid,name,login,password) VALUES ('$avatarid','$name','$login','$password')");

        $jsonArray['userid'] = mysqli_insert_id($conn);
        $jsonArray['login'] = $login;
        $jsonArray['password'] = $password;
        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function login($login,$password){
        $jsonArray = array();
        $result = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' AND password='$password'");
        
        if($result->num_rows < 1){
            return '{"errorId":3}';
        }
        $_COOKIE['login'] = $login;
        $_COOKIE['password'] = $password;
    } 




    if(getAction('createBet')){
        echo createBet($conn,$_POST['name'],$_POST['variations'],$_POST['count']);
    }
    
    if(getAction('withdrawRate')){
        echo withdrawRate($conn,$_POST['rateId']);
    }

    if(getAction('placeBet')){
        echo placeBet($conn,$_POST['rateId'],$_POST['varId']);
    }

    if(getAction('history')){
        echo history($conn);
    }
    
    if(getAction('register')){
        echo register($conn,$_POST['avatarid'],$_POST['name'],$_POST['login'],$_POST['password']);
    }
    if(getAction('getUserRate')){
        echo getUserRate($conn,$_POST['userId'],$_POST['rateId']);
    }
    if(getAction('getUser')){
        echo json_encode(getUser($conn),JSON_UNESCAPED_UNICODE);
    }
    ?>