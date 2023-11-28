<?php

require_once('DataProvider.php');

class Users extends DataProvider {
   public function addUser($username,$pw,$gendre,$roleName,$ville, $quartier,$rue,$codePostal,$email,$phone,$agencyId) {
    $db=$this->connect();
    if($db==null){
        return null;
   }
   $sql= 'START TRANSACTION;


   INSERT INTO adress (ville, quartier,rue,codePostal,email,phone)
   VALUES (:ville, :quartier,:rue,:codePostal,:email,:phone);

   SET @adressId = LAST_INSERT_ID();

        INSERT INTO users (username, pw, gendre,agencyId,adrId)
         VALUES (:username,:pw,:gendre,:agencyId,@adressId);

   
         SET @userId = LAST_INSERT_ID();
   
        INSERT INTO roleofuser (userId, roleName)
        VALUES (@userId, :roleName);
   

   
        COMMIT;';
   $stmt = $db->prepare($sql);
   $stmt->execute([
    ':username'=> $username,
    ":pw" => $pw,
    ":gendre" => $gendre,
    ":agencyId" => $agencyId,
    ":roleName" => $roleName,
    ":ville"=> $ville,
    ":quartier"=> $quartier,
    ":rue"=> $rue,
    ":codePostal"=> $codePostal,
    ":email"=> $email,
    ":phone"=> $phone
   ]);
   $db=null;
   $stmt=null;
}


public function displayUser(){

    $db=$this->connect();
    if($db==null){
        return null;
   }

   $query = $db->query('SELECT 
   users.userId,
   users.username,
   users.agencyId,
   adress.email,
   roleofuser.roleName
FROM users
JOIN roleofuser ON users.userId = roleofuser.userId
JOIN agency ON users.agencyId = agency.agencyId
JOIN adress
WHERE users.adrId = adress.adrId
;');

   $data_users=$query->fetchAll(PDO::FETCH_OBJ);

   $query = null;
   $db=null;
   return $data_users;
}




public function updateUser($id,$username,$pw,$gendre,$agencyId,$roleName,$ville, $quartier,$rue,$codePostal,$email,$phone) {
      
    $db = $this->connect();

    if($db == null) {
        return;
    }

    $sql = "UPDATE users SET username = :username, pw = :pw, gendre = :gendre , agencyId = :agencyId  WHERE userId = :id;
    UPDATE adress SET ville = :ville, quartier = :quartier, rue = :rue, codePostal= :codePostal , email=:email , phone= :phone WHERE adrId IN (SELECT adrId FROM users WHERE userId = :id); ;
    UPDATE roleofuser SET roleName = :roleName WHERE userId = :id ;";

    $stmt = $db->prepare($sql);

    $stmt->execute([
    "id"=> $id,
    ':username'=> $username,
    ":pw" => $pw,
    ":gendre" => $gendre,
    ":agencyId" => $agencyId,
    ":roleName" => $roleName,
    ":ville"=> $ville,
    ":quartier"=> $quartier,
    ":rue"=> $rue,
    ":codePostal"=> $codePostal,
    ":email"=> $email,
    ":phone"=> $phone
       ]);

    $smt = null;
    $db = null;
}




public function displayUserOne($id){

    $db=$this->connect();
    if($db==null){
        return null;
   }

   $query ='SELECT 
   users.*,
   adress.*,
   roleofuser.*
   
FROM users
JOIN roleofuser ON users.userId = roleofuser.userId
JOIN adress ON users.adrid = adress.adrid
WHERE users.userId = :id;

';

$stmt = $db->prepare($query);
$stmt->execute([
    "id"=> $id,]);

   $data_user=$stmt->fetchAll(PDO::FETCH_OBJ);

   $query = null;
   $db=null;
   return $data_user;
}













public function deleteUser($id) {
  
    $db = $this->connect();

    if($db == null) {
        return;
    }

    $sql = "DELETE FROM users WHERE userId = :id";

    $smt = $db->prepare($sql);

    $smt->execute([
        ":id" => $id
    ]);

    $smt = null;
    $db = null;
}





public function displayUserAcc($id){

    $db=$this->connect();
    if($db==null){
        return null;
   }

$query = 'SELECT users.username, account.*
FROM users
JOIN account ON users.userId = account.userId
WHERE users.userId = :id ';


$stmt = $db->prepare($query);
$stmt->execute([
    "id"=> $id,]);

   $acc_user=$stmt->fetchAll(PDO::FETCH_OBJ);

   $query = null;
   $db=null;
   return $acc_user;
}



}







?>