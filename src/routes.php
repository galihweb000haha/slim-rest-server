<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    
    $app->get("/items/", function (Request $request, Response $response){
        $sql = "SELECT * FROM tbl_barang";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/items/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM tbl_barang WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/items/search/", function (Request $request, Response $response, $args){
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM tbl_barang WHERE kode_barang LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR merk LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->post("/items/", function (Request $request, Response $response){

        $new_item = $request->getParsedBody();
    
        $sql = "INSERT INTO tbl_barang (kode_barang, nama, merk, jumlah, keterangan, no_register) VALUE (:kode_barang, :nama, :merk, :jumlah, :keterangan, :no_register)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":kode_barang" => $new_item["kode_barang"],
            ":nama" => $new_item["nama"],
            ":merk" => $new_item["merk"],
            ":jumlah" => $new_item["jumlah"],
            ":keterangan" => $new_item["keterangan"],
            ":no_register" => $new_item["no_register"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->put("/items/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_item = $request->getParsedBody();
        $sql = "UPDATE tbl_barang SET kode_barang=:kode_barang, nama=:nama, merk=:merk, jumlah=:jumlah, keterangan=:keterangan, no_register=:no_register WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id,
            ":kode_barang" => $new_item["kode_barang"],
            ":nama" => $new_item["nama"],
            ":merk" => $new_item["merk"],
            ":jumlah" => $new_item["jumlah"],
            ":keterangan" => $new_item["keterangan"],
            ":no_register" => $new_item["no_register"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->delete("/items/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "DELETE FROM tbl_barang WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
};
