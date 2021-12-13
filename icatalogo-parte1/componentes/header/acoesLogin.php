<?php
session_start();
require("../../database/conexaoBD.php");

function validarCampos(){
    $erros= [];

    if (!isset($_POST["usuario"]) || $_POST["usuario"] == "") {
        $erros[] = "O campo usuário é obrigatorio";
    }

    if(!isset($_POST["senha"]) || $_POST["senha"] == ""){
        $erros[] = "O campo senha é obrigatorio";
    }

    return $erros;
}

switch ($_POST["acao"]) {
    case 'login':

        $erros = validarCampos();

        if(count($erros) > 0){
            $_SESSION["erros"] = $erros;

            header("location: ../../index.php");
        }

        // implentear hoje
        // receber os campos do usuario do post
        $usuario = $_POST["usuario"];
        $senha = $_POST["senha"];

        //montar sql select na tabela tbl_adiministrador
        //SELECT * FROM tbl_administrador where usuario = $usuario
        $sql = "SELECT * FROM tbl_administrador where usuario = '$usuario'";
        
        //executar o sql
        $resultado = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));   
        
        $dadosUsuario = mysqli_fetch_array($resultado);
        
        //verificar so o usuario existe
        //verificar se a senha está corrta
        if (!$dadosUsuario || !password_verify($senha, $dadosUsuario["senha"])) {
            //se a senha estiver errada, criar um amensagem de "usuário e/ou senha invalidos"
            $mensagem = "usuário e/ou senha invalidos";
        }else{
            //se estiver correte, salvar o id e o nome do usuario na sesão $_SESSION   
            $_SESSION["usuarioId"] = $dadosUsuario["id"];
            $_SESSION["ussuarioNome"] = $dadosUsuario["nome"];

            $mensagem = "Bem vindo, " . $dadosUsuario["nome"];
        }

        $_SESSION["mensagem"] = $mensagem;

        //redirecionar para a tela dde listagem de produtos
        header("location: ../../produtos/index.php");

        break;
        
    case "logout":
        //implementar futuramente
        session_destroy();
        header("location: ../../produtos/index.php");
        break;
    }