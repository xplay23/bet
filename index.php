
<?php
    $siteURL = 'http://localhost:8080/#/';
    $_POST = json_decode(file_get_contents('php://input'), true);
    function getAction($action){
        return $_POST['action'] === $action;
    }
    
    $servername = 'clickdes.mysql.tools';
    $username = 'clickdes_vuebet';
    $password = 'haTd2H)-42';
    
    $conn = mysqli_connect($servername, $username, $password, $username);
    $conn->set_charset("utf8");
   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function getUserData($user){
        global $conn;
        $avatarInfo = array();
        $avatarID = 0;
        $userInfo = null;
        foreach ($user as $result) {
            $avatarID = $result['avatarid'];
            unset($result['avatarid']);
            unset($result['password']);
            $userInfo = $result;
        }
        $avatarInfo = mysqli_query($conn,"SELECT * FROM `Avatar` WHERE id='$avatarID' LIMIT 1");
        
        $row = $avatarInfo->fetch_assoc();
        
        $userInfo['avatarInfo'] = $row;
        return $userInfo;
    }
   

    function getUser($token){
        global $conn;
        $userId = mysqli_query($conn,"SELECT * FROM `Token` WHERE token='$token' LIMIT 1");
        
        $row = $userId->fetch_assoc();
        
        $userId = intval($row['userid']);

        $user = mysqli_query($conn,"SELECT * FROM `Users` WHERE id='$userId' LIMIT 1");
        return getUserData($user);
    }

   

    function getUserById($userID){
        global $conn;
        $userID = intval($userID);
        $user = mysqli_query($conn,"SELECT * FROM `Users` WHERE id='$userID' LIMIT 1");
    
        return getUserData($user);
    }

    function getUsers($users){
        global $conn;
        $userInfo = array();
        
        foreach ($users as $result) {
            unset($result['avatarid']);
            unset($result['password']);

            array_push($userInfo, $result);
        }

        return $userInfo;
    }

    function getAllUsers(){
        global $conn;
        $users = mysqli_query($conn,"SELECT * FROM `Users`");
    
        return getUsers($users);
    }


 
    function createBet($name,$variations,$count,$participating,$token){
        global $conn;
        global $siteURL;
        $userID = getUser($token)['id'];
        $result = array();
        if($userID){
            mysqli_query($conn,"INSERT INTO Rate (name,statusid,count,userid,createdate) VALUES ('$name',0,$count,$userID,'".date('Y-m-d')."')");
            $id = mysqli_insert_id($conn);
            foreach ($variations as $key => $value) {
                mysqli_query($conn,"INSERT INTO Variation (name,rateid) VALUES ('$value',".$id.")");
            }
            foreach ($participating as $key => $value) {
                mysqli_query($conn,"INSERT INTO UserCanRate (userid,rateid) VALUES ('$value',".$id.")");

                $userInfo = mysqli_query($conn,"SELECT * FROM `Users` WHERE id='$value' LIMIT 1");
                $res = $userInfo->fetch_assoc();
                $email = $res['email'];
                if(isset($email) && $email != ''){
                    $headers = 'From: ratbet@example.com';
                    mail(
                        $email,
                        'Новая ставОчка',
                        $siteURL."/bet/".$id,
                        $headers
                    );
                }
            }
            $result['errorId'] = 0;
            $result['rateID'] = $id;
        }else{
            $result['errorId'] = 4;
        }
        return $result;
    }

    function getRate($ratId){
        global $conn;
        $return = array();
        $results = mysqli_query($conn,"SELECT * FROM `Rate` WHERE id=$ratId");
        foreach ($results as $result) {
            $return = $result;
        }
        return $return;
    }

    function getVariation($rateid){
        global $conn;
        $return = array();
        $results = mysqli_query($conn,"SELECT * FROM `Variation` WHERE rateid='$rateid'");
        
        foreach ($results as $result) {
            array_push($return, $result);
        }
        return $return;
    }

    function getWin($ratId){
        global $conn;
        $return = null;
        $results = mysqli_query($conn,"SELECT * FROM `Win` WHERE rateid='$ratId'");
        foreach ($results as $result) {
            $return = $result;
        }
        return $return;
    }

    function getUserToFront(){
        global $conn;
        $return = array();
        $results = mysqli_query($conn,"SELECT * FROM `Users`");
        foreach ($results as $result) {
            $resultCache = $result;
            unset($resultCache['password']);
            unset($resultCache['avatarid']);
            array_push($return, $resultCache);
        }
        return $return;
    }

    function getUserVariations($userinfo,$ratId){
        global $conn;
        $return = array();
        foreach ($userinfo as $key => $value) {
            $userID = intval($value['id']);
            $resultsIn = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE rateid='$ratId' AND userid='$userID'");
            foreach ($resultsIn as $resultIn) {
                array_push($return ,$resultIn);
            }
        }
        return $return;

    }

    function getParticipating($ratId){
        global $conn;
        $return = array();
        $ratId = intval($ratId);
        $resultsIn = mysqli_query($conn,"SELECT * FROM `UserCanRate` WHERE rateid='$ratId'");
        foreach ($resultsIn as $resultIn) {
            array_push($return, getUserById($resultIn['userid']));
        }
        return $return;
    }

    function withdrawRate($ratId,$token){
        $jsonArray = array();

        $ratId = intval($ratId);
        
        $jsonArray['rateinfo'] = getRate($ratId);
        
        $jsonArray['variations'] = getVariation($ratId);
        
        $jsonArray['userinfo'] = getParticipating($ratId);

        $jsonArray['uservariations'] = getUserVariations($jsonArray['userinfo'],$ratId);

        $jsonArray['wininfo'] = getWin($ratId);

        $jsonArray['currentUser'] = getUser($token);

        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function getUserRate($userId,$ratId){
        global $conn;
        $results = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE userid='$userId' AND rateid='$ratId'");
        $jsonArray = array();
        foreach ($results as $result) {
            array_push($jsonArray,$resultCache);
        }

        return $jsonArray;
    }

    function placeBet($rateId,$varId,$token){
        global $conn;
        $user = getUser($token);
        $userId = $user['id'];
        $result = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE rateid='$rateId' AND userid='$userId'");
        
        if($result->num_rows > 0){
            return '{"errorId":1}';
        }

        mysqli_query($conn,"INSERT INTO `UsersRate` (userid,rateid,varid) VALUES ('$userId','$rateId','$varId')");
        return '{"errorId":0}';

    }
    function endBet($rateId,$varId){
        global $conn;
        $rateId = intval($rateId);
        $varId = intval($varId);
        mysqli_query($conn,"UPDATE `Rate` SET statusid='1' WHERE id='$rateId'");
        mysqli_query($conn,"INSERT INTO Win (rateid,varid) VALUES ('$rateId','$varId')");
    }
    function getAvatars(){
        global $conn;
        $return = array();
        $results = mysqli_query($conn,"SELECT * FROM `Avatar`");
        foreach ($results as $result) {
            array_push($return,$result);
        }
        return json_encode($return,JSON_UNESCAPED_UNICODE);
    }


    function history(){
        global $conn;
        $jsonArray = array();
        $results = mysqli_query($conn,"SELECT * FROM `Rate`");
        foreach ($results as $result) {
            $rateID = intval($result['id']);
            $result['UserCanRate'] = array();
            $resultsCanRate = mysqli_query($conn,"SELECT * FROM `UserCanRate` WHERE rateid='$rateID'");
            foreach ($resultsCanRate as $resultCanRate) {
                array_push($result['UserCanRate'],$resultCanRate);
            }

            array_push($jsonArray,$result);
        }
        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function myRates($token){
        global $conn;
        $jsonArray = array();
        $id =  intval(getUser($token)['id']);
        $results = mysqli_query($conn,"SELECT * FROM `Rate` WHERE userid=$id");
        foreach ($results as $result) {
            array_push($jsonArray,$result);

        }
        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function winRatesByToken($token){
        global $conn;
        $id =  intval(getUser($token)['id']);

        return json_encode(winRates($id),JSON_UNESCAPED_UNICODE);
    }

    function winRates($id){
        global $conn;
        $jsonArray = array();
        $rates = array();
        $jsonArray['rates'] = array();
        $jsonArray['count'] = array();

        $results = mysqli_query($conn,"SELECT * FROM `Win`");
        
        foreach ($results as $result) {

            $varId = intval($result['varid']);

            $resultsWin = mysqli_query($conn,"SELECT * FROM `UsersRate` WHERE userid=$id AND varid=$varId LIMIT 1");
            $row = $resultsWin->fetch_assoc();

            array_push($rates,$row['rateid']);
        }
        foreach ($rates as $key => $idInner) {
            $resultsWin = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `Rate` WHERE id=$idInner LIMIT 1"));
            
            if(!is_null($resultsWin)){
                array_push($jsonArray['rates'],$resultsWin);
                array_push($jsonArray['count'],$resultsWin['count']);
            }
        }

        return $jsonArray;
    }

    function loseRatesByToken($token){
        global $conn;
        $id =  intval(getUser($token)['id']);

        return json_encode(loseRates($id),JSON_UNESCAPED_UNICODE);
    }

    function SQLToArray($sql,$key){
        $return = array();
        while ($inner = mysqli_fetch_assoc($sql)) {
            if(!is_null($key)){
                array_push($return,$inner[$key]);
            }else{
                array_push($return,$inner);
            }
        }
        return $return;
    }

    function getWinRate($rate,$winArray){
        $newValue = array_filter($winArray,function($k) use($rate){
            return $rate['rateid'] == $k['rateid'] && $rate['varid'] == $k['varid'];
        });
        return array_shift($newValue)['rateid'];
    }

    function loseRates($id){
        global $conn;
        $jsonArray = array();
        $jsonArray['rates'] = array();
        $jsonArray['count'] = array();


        $endRates = SQLToArray(mysqli_query($conn,"SELECT id FROM `Rate` WHERE statusid=1"),'id');

        $canRate = SQLToArray(mysqli_query($conn,"SELECT rateid FROM `UserCanRate` WHERE userid=$id"),'rateid');

        array_filter($endRates,function($k) use($canRate){
            return in_array($k,$canRate);
        });

        $win = SQLToArray(mysqli_query($conn,"SELECT rateid,varid FROM `Win`"),null);

        $userRates = SQLToArray(mysqli_query($conn,"SELECT rateid,varid FROM `UsersRate` WHERE userid=$id"),null);
        $isWin = array();
        foreach ($userRates as $userRate) {
            array_push($isWin,getWinRate($userRate,$win) );
        }
        
        $isWin = array_filter($isWin,function($k){
            return $k != '';
        });

        $endRates = array_filter($endRates,function($k) use($isWin){
            return !in_array($k,$isWin);
        });

        // var_dump($endRates);

        foreach ($endRates as $key => $rate) {
            $idInner = intval($rate);
            $resultsWin = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `Rate` WHERE id=$idInner LIMIT 1"));
            
            if(!is_null($resultsWin)){
                array_push($jsonArray['rates'],$resultsWin);
                array_push($jsonArray['count'],$resultsWin['count']);
            }
        }

        return $jsonArray;
    }

    function leaderboard(){
        global $conn;
        $return = array();
        
        $users = SQLToArray(mysqli_query($conn,"SELECT id,name,login FROM `Users`"),null);
        // var_dump($users);
        foreach ($users as $user) {
            $id = intval($user['id']);
            $return[$user['login']] = array();
            $wins = winRates($id)['count'];
            $loose = loseRates($id)['count'];

            $return[$user['login']]['name'] = $user['name'];
            $return[$user['login']]['win'] = $wins;
            $return[$user['login']]['loose'] = $loose;
        }
        // var_dump($return);

        return $return;
    }

    function register($avatarid,$name,$login,$password,$email){
        global $conn;
        $jsonArray = array();
        $result = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' OR name='$name'");
        
        if($result->num_rows > 0){

            $resultIn = mysqli_query($conn,"SELECT * FROM `Errors` WHERE id=2  LIMIT 1");
            $toReturn = array();
            $toReturn['errorId'] = 2;
            foreach ($resultIn as $key => $value) {
                $toReturn['errorText'] = $value['name'];
            }
            return json_encode($toReturn,JSON_UNESCAPED_UNICODE);
        }
        $password = md5($password);
        $userResult = mysqli_query($conn,"INSERT INTO `Users` (avatarid,name,login,password,email,createdate) VALUES ('$avatarid','$name','$login','$password','$email','".date('Y-m-d')."')");

        $jsonArray['userid'] = mysqli_insert_id($conn);

        return json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    }

    function updateAvatar($avatarid,$token){
        global $conn;
        $jsonArray = array();
        
        $userID = intval(getUser($token)['id']);
        $avatarid = intval($avatarid);

        mysqli_query($conn,"UPDATE `Users` SET avatarid=$avatarid WHERE id=$userID");
    }

    function gen_token() {
        $bytes = openssl_random_pseudo_bytes(20, $cstrong);
        return bin2hex($bytes); 
    }

    function login($login,$password){
        global $conn;
        $jsonArray = array();

        $password = md5($password);

        $result = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' AND password='$password' LIMIT 1");
        
        $toReturn = array();
        if($result->num_rows < 1){
            $toReturn['errorId'] = 3;
            $resultIn = mysqli_query($conn,"SELECT * FROM `Errors` WHERE id=3  LIMIT 1");
            foreach ($resultIn as $key => $value) {
                $toReturn['errorText'] = $value['name'];
            }
            return json_encode($toReturn,JSON_UNESCAPED_UNICODE);
        }


        $token = gen_token();
        $id = intval($result->fetch_assoc()['id']);
        
        mysqli_query($conn,"INSERT INTO `Token` (token,userid) VALUES ('$token',$id)");
        $toReturn['token'] = $token;

        return json_encode($toReturn,JSON_UNESCAPED_UNICODE);
    } 

    function resetPass($login){
        global $conn;
        global $siteURL;
        $jsonArray = array();

        $result = mysqli_query($conn,"SELECT * FROM `Users` WHERE login='$login' LIMIT 1");
        
        $toReturn = array();
        if($result->num_rows < 1){
            $toReturn['errorId'] = 5;
            $resultIn = mysqli_query($conn,"SELECT * FROM `Errors` WHERE id=5  LIMIT 1");
            foreach ($resultIn as $key => $value) {
                $toReturn['errorText'] = $value['name'];
            }
            return json_encode($toReturn,JSON_UNESCAPED_UNICODE);
        }
        $res = $result->fetch_assoc();
        $email = $res['email'];
        $id = intval($res['id']);
        var_dump($id);
        if(isset($email) && $email != ''){
            $token = gen_token();
            
            mysqli_query($conn,"INSERT INTO TokenEmail (token,userid,createdate) VALUES ('$token',$id,'".date('Y-m-d')."')");
            $headers = 'From: ratbet@example.com';
            return mail(
                $email,
                'Сброс пароля',
                $siteURL."/changepass/".$token,
                $headers
            );
        }

        $toReturn['errorId'] = 6;
        $resultIn = mysqli_query($conn,"SELECT * FROM `Errors` WHERE id=6  LIMIT 1");
        foreach ($resultIn as $key => $value) {
            $toReturn['errorText'] = $value['name'];
        }
        return json_encode($toReturn,JSON_UNESCAPED_UNICODE);
        

    }

    function changePass($token,$password){
        global $conn;
        $jsonArray = array();

        

        $result = mysqli_query($conn,"SELECT * FROM `TokenEmail` WHERE token='$token' LIMIT 1");
        
        $toReturn = array();
        if($result->num_rows < 1){
            $toReturn['errorId'] = 5;
            $resultIn = mysqli_query($conn,"SELECT * FROM `Errors` WHERE id=5  LIMIT 1");
            foreach ($resultIn as $key => $value) {
                $toReturn['errorText'] = $value['name'];
            }
            return json_encode($toReturn,JSON_UNESCAPED_UNICODE);
        }

        $password = md5($password);

        $id = intval($result->fetch_assoc()['userid']);

        mysqli_query($conn,"UPDATE `Users` SET password='$password' WHERE id=$id");
        mysqli_query($conn,"DELETE FROM `TokenEmail` WHERE token='$token'");
        


        return $id;

    } 

    function unLogin($token){
        global $conn;
        $result = mysqli_query($conn,"DELETE FROM `Token` WHERE token='$token'");
    } 




    if(getAction('createBet')){
        echo json_encode(
            createBet($_POST['name'],$_POST['variations'],$_POST['count'],$_POST['participating'],$_POST['token']),
            JSON_UNESCAPED_UNICODE
        );
    }
    
    if(getAction('withdrawRate')){
        echo withdrawRate($_POST['rateId'],$_POST['token']);
    }

    if(getAction('placeBet')){
        echo placeBet($_POST['rateId'],$_POST['varId'],$_POST['token']);
    }

    if(getAction('history')){
        echo history();
    }
    if(getAction('myRates')){
        echo myRates($_POST['token']);
    }
    if(getAction('winRates')){
        echo winRatesByToken($_POST['token']);
    }
    if(getAction('loseRates')){
        echo loseRatesByToken($_POST['token']);
    }
    
    if(getAction('login')){
        echo login($_POST['login'],$_POST['password']);
    }
    if(getAction('resetPass')){
        echo resetPass($_POST['login']);
    }
    if(getAction('changePass')){
        echo changePass($_POST['token'],$_POST['password']);
    }
    if(getAction('unLogin')){
        echo unLogin($_POST['token']);
    }
    if(getAction('register')){
        echo register($_POST['avatarid'],$_POST['name'],$_POST['login'],$_POST['password'],$_POST['email']);
    }
    if(getAction('updateAvatar')){
        updateAvatar($_POST['avatarid'],$_POST['token']);
    }
    if(getAction('getUserRate')){
        echo getUserRate($_POST['userId'],$_POST['rateId']);
    }
    if(getAction('getUser')){
        echo json_encode(getUser($_POST['token']),JSON_UNESCAPED_UNICODE);
    }
    if(getAction('getUserById')){
        echo json_encode(getUserById($_POST['userId']),JSON_UNESCAPED_UNICODE);
    }
    if(getAction('getAllUsers')){
        echo json_encode(getAllUsers(),JSON_UNESCAPED_UNICODE);
    }
    if(getAction('endBet')){
        echo endBet($_POST['rateId'],$_POST['varId']);
    }
    if(getAction('getAvatars')){
        echo getAvatars();
    }
    if(getAction('leaderboard')){
        echo json_encode(leaderboard(),JSON_UNESCAPED_UNICODE);
    }

    if($_FILES['file']['name']){
        $filename = $_FILES['file']['name'];
        // Valid file extensions
        $valid_extensions = array("jpg","jpeg","png","svg");
        
        // // File extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // // Check extension
        $token = bin2hex(random_bytes(4));
        $newFileName =  $token . $filename;

        if(in_array(strtolower($extension),$valid_extensions) ) {
            if(move_uploaded_file($_FILES['file']['tmp_name'], __DIR__."/uploads/".$newFileName)){
                $link = 'http://devlink1.tk//bm/vue_lessons/betting_admin/uploads/'.$newFileName;
                mysqli_query($conn,"INSERT INTO `Avatar` (path) VALUES ('$link')");
                $arr = array();
                $arr['url'] = $link;
                $arr['avatarId'] = mysqli_insert_id($conn);
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }else{
                $arr['errorId'] = 1;
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $arr['errorId'] = 1;
            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        }

    }

    


    // $files1 = scandir('icons');

    // foreach ($files1 as $key => $name) {
    //     $nameT = 'https://devlink1.tk/bm/vue_lessons/betting_admin/icons/'.$name;
    //     mysqli_query($conn,"INSERT INTO Avatar (path) VALUES ('$nameT')");
    // }
    ?>