<?php
session_start();
require("../database/conexaoBD.php");
    if(isset($_POST["produtoId"])){
        $idProduto = $_POST["produtoId"];

        $sqlDelecao = "DELETE FROM tbl_produto WHERE id = $idProduto";
        $resultadoDelecao = mysqli_query($conexao, $sqlDelecao) or die(mysqli_error($conexao));

        $sqlSelecao = "SELECT * FROM tbl_produto";
        $resultadoSelecao = mysqli_query($conexao, $sqlSelecao) or die(mysqli_error($conexao));

        $imagem = mysqli_fetch_array($resultadoSelecao);
        $nomeImagem = $imagem["imagem"];

        unlink("fotos/$nomeImagem");


        if ($resultadoDelecao) {
            $_SESSION["mensagem"] = "Poduto excluído com sucesso.";
        }else{
            $_SESSION["mensagem"] = "Ops, prolemas ao excluir";
        }

    }


    header("location: index.php");
