<?php
include "config.php";
include "utils.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id_codigo']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM personas  where id_codigo=:id_codigo");
      $sql->bindValue(':id_codigo', $_GET['id_codigo']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM personas");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO personas
          (nombre_persona, apellido_persona, ci_persona, correo_persona, fecnac_persona, tel_persona, estado_persona)
          VALUES
          (:nombre_persona, :apellido_persona, :ci_persona, :correo_persona, :fecnac_persona, :tel_persona, :estado_persona)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['codigo'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$codigo = $_GET['id_persona'];
  $statement = $dbConn->prepare("DELETE FROM  personas where id_persona=:id_persona");
  $statement->bindValue(':id_persona', $codigo);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postid_codigo = $input['id_persona'];
    $fields = getParams($input);

    $sql = "
          UPDATE personas
          SET $fields
          WHERE id_persona='$postid_codigo'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}


?>

