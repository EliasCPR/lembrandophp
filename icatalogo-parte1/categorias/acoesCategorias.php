<?php
session_start();
require("../database/conexaoBD.php");
function validarCampos(){
    $erros= [];
    if(!isset($_POST["descricao"]) || $_POST["descricao"] == ""){
        $erros[] = "O campo descrição é obrigatório";
    }

    return $erros;
}
// Criar a tabela de categorias. OK
// Fazer a inclusão, listagem e deleção de categorias. OK??
// Proteger a página de categorias caso o usuário não estiver logado. OK
// DESAFIO: Na página de novo produto, fazer um <select> listando as categorias.

switch ($_POST["acao"]) {

    case "inserir":
        $erros = validarCampos();

        if(count($erros) > 0){
            $_SESSION["mensagem"] = $erros[0];
            header("location: index.php");

            exit();
        }
        $descricao = $_POST["descricao"];

        $sqlInsert= "INSERT INTO tbl_categoria (descricao) VALUES ('$descricao')";
    
        $resultado = mysqli_query($conexao, $sqlInsert) or die(mysqli_error($conexao));

        if($resultado){
            $_SESSION["mensagem"] = "Categoria incluída com sucesso";
        }else{
            $_SESSION["mensagem"] = "Ops, erro ao incluir categorias";
        }

        header("location: index.php");

    break;

    case "delecao":
        $id = $_POST["categoriaId"];

        $sqlDelecao = "DELETE FROM tbl_categoria WHERE id = $id";

        $resultado = mysqli_query($conexao, $sqlDelecao) or die(mysqli_error($conexao));

        if ($resultado) {
            $_SESSION["mensagem"] = "Categoria excluída com sucesso.";
        }else{
            $_SESSION["mensagem"] = "Ops, prolemas ao excluir";
        }

        header("location: index.php");
    break;
}