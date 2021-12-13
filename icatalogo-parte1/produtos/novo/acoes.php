<?php
require("../../database/conexaoBD.php");

session_start();

// Função para a validação dos campos
function validacaoCampos()
{
    $erros = [];

    if (!isset($_POST["descricao"]) && $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatorio";
    }

    if (!isset($_POST["peso"]) && $_POST["peso"] == "") {
        $erros[] = "O campo peso é obrigatorio";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["peso"]))) {
        $erros[] = "O campo peso deve ser um número";
    }

    if (!isset($_POST["quantidade"]) && $_POST["quantidade"] == "") {
        $erros[] = "O campo quantidade é obrigatorio";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["quantidade"]))) {
        $erros[] = "O campo quantidade deve ser um número";
    }

    if (!isset($_POST["cor"]) && $_POST["cor"] == "") {
        $erros[] = "O campo cor é obrigatorio";
    }

    if (!isset($_POST["valor"]) && $_POST["valor"] == "") {
        $erros[] = "O campo valor é obrigatorio";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
        $erros[] = "O campo valor deve ser um número";
    }

    if (isset($_POST["desconto"]) && $_POST["desconto"] != "") {
        if (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
            $erros[] = "O campo desconto deve ser um número";
        }
    }

    if (!isset($_POST["categoria"]) || $_POST["categoria"] == "") {
        $erros[] = "O campo categoria é obrigatorio, selecione uma categoria";
    }

    //Validação da imagem
    if ($_FILES["foto"]["error"] == UPLOAD_ERR_NO_FILE) {
        $erros[] = "O campo foto é obrigatório";
    } elseif (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] != UPLOAD_ERR_OK) {
        $erros[] = "Ops, houve um erro inesperado, verifique o arquivo e tente novamente";
    } else {
        $fotoInfos = getimagesize($_FILES["foto"]["tmp_name"]);
        if (!$fotoInfos) {
            $erros[] = "O arquivo precisa ser uma imagem";
        } elseif ($_FILES["foto"]["size"] > 1024 * 1024 * 2) {
            $erros[] = "O arquivo não pode ser maior que 2MB";
        }

        //Verificar se a imagem é quadrada
        $width = $fotoInfos[0];
        $height = $fotoInfos[1];
        if ($width != $height) {
            $erros[] = "A foto deve ser quadrada";
        }
    }


    return $erros;
}

function validacaoCamposEditar()
{
    $erros = [];

    if (!isset($_POST["descricao"]) && $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatorio";
    }

    if (!isset($_POST["peso"]) && $_POST["peso"] == "") {
        $erros[] = "O campo peso é obrigatorio";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["peso"]))) {
        $erros[] = "O campo peso deve ser um número";
    }

    if (!isset($_POST["quantidade"]) && $_POST["quantidade"] == "") {
        $erros[] = "O campo quantidade é obrigatorio";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["quantidade"]))) {
        $erros[] = "O campo quantidade deve ser um número";
    }

    if (!isset($_POST["cor"]) && $_POST["cor"] == "") {
        $erros[] = "O campo cor é obrigatorio";
    }

    if (!isset($_POST["valor"]) && $_POST["valor"] == "") {
        $erros[] = "O campo valor é obrigatorio";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
        $erros[] = "O campo valor deve ser um número";
    }

    if (isset($_POST["desconto"]) && $_POST["desconto"] != "") {
        if (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
            $erros[] = "O campo desconto deve ser um número";
        }
    }

    if (!isset($_POST["categoria"]) || $_POST["categoria"] == "") {
        $erros[] = "O campo categoria é obrigatorio, selecione uma categoria";
    }

    //Validação da imagem
    if ($_FILES["foto"]["error"] != UPLOAD_ERR_NO_FILE) {

        $fotoInfos = getimagesize($_FILES["foto"]["tmp_name"]);

        if (!$fotoInfos) {
            $erros[] = "O arquivo precisa ser uma imagem";
        } elseif ($_FILES["foto"]["size"] > 1024 * 1024 * 2) {
            $erros[] = "O arquivo não pode ser maior que 2MB";
        }

        //Verificar se a imagem é quadrada
        $width = $fotoInfos[0];
        $height = $fotoInfos[1];
        if ($width != $height) {
            $erros[] = "A foto deve ser quadrada";
        }
    }


    return $erros;
}

switch ($_POST["acao"]) {

    case "inserir":

        $erros = validacaoCampos();

   

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;

            header("location: index.php?erro=houveErro");
        }
        //nome da foto
        $nomeFoto = $_FILES["foto"]["name"];
        //extensão da foto
        $extensao = pathinfo($nomeFoto, PATHINFO_EXTENSION);
        //novo nome para que não haja erro de aquivos com mesmo nome
        $novoNomeFoto = md5(microtime()) . ".$extensao";
        //salvando a foto na pasta reservada para elas
        move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/$novoNomeFoto");

        //campos do form
        $descricao = $_POST["descricao"];
        // Trocando a virgula por ponto
        $peso = str_replace(",", ".", $_POST["peso"]);
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        // Trocando a virgula por ponto
        $valor = str_replace(",", ".", $_POST["valor"]);
        $tamanho = $_POST["tamanho"];
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;
        $categoriaId = $_POST["categoria"];

        $sqlinsert = "INSERT INTO tbl_produto (descricao, peso, quantidade, cor, tamanho, valor, desconto, imagem, categoria_id)
                            VALUES ('$descricao', $peso, $quantidade, '$cor', '$tamanho', $valor, $desconto, '$novoNomeFoto', $categoriaId)";

        $resultado = mysqli_query($conexao, $sqlinsert) or die(mysqli_error($conexao));

        if ($resultado) {
            $mensagem = "Produto inserido com sucesso!";
        } else {
            $mensagem = "Erro ao inserir o produto!";
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: /produtos/index.php");

        break;

    case "deletar":

        $produtoId = $_POST["produtoId"];

        //procura a imagem no banco pelo id do produto
        $sqlImagem = " SELECT imagem FROM tbl_produto WHERE id = $produtoId ";
        $resultado = mysqli_query($conexao, $sqlImagem) or die(mysqli_error($conexao));
        $produto = mysqli_fetch_array($resultado);
        //deletamos a imagem pelo nome
        unlink("../fotos/" . $produto["imagem"]);

        $sql = " DELETE FROM tbl_produto WHERE id = $produtoId ";
        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            $mensagem = "Produto excluído com sucesso!";
        } else {
            $mensagem = "Ops, erro ao excluir!";
        }

        $_SESSION["mensagem"] = $mensagem;
        header("location: /produtos/index.php");

        break;

    case "editar":

        $erros = validacaoCamposEditar();

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;

            header("location: index.php");

            exit();
        }

        $produtoId = $_POST["produtoId"];

        // deletar a imgem antiga
        if ($_FILES["foto"]["error"] != UPLOAD_ERR_NO_FILE) {
            $sql = "SELECT imagem FROM tbl_produto WHERE id = $produtoId";

            $resultado = mysqli_query($conexao, $sql);
            $produto = mysqli_fetch_array($resultado);

            unlink("../fotos/" . $produto["imagem"]) or die(mysqli_error($conexao));
            //subir a imagem nova

            $nomeFoto = $_FILES["foto"]["name"];

            //extensão da foto
            $extensao = pathinfo($nomeFoto, PATHINFO_EXTENSION);

            //novo nome para que não haja erro de aquivos com mesmo nome
            $novoNomeFoto = md5(microtime()) . ".$extensao";

            //salvando a foto na pasta reservada para elas
            move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/$novoNomeFoto");
        }



        //campos do form
        $descricao = $_POST["descricao"];
        // Trocando a virgula por ponto
        $peso = str_replace(",", ".", $_POST["peso"]);
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        // Trocando a virgula por ponto
        $valor = str_replace(",", ".", $_POST["valor"]);
        $tamanho = $_POST["tamanho"];
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;
        $categoriaId = $_POST["categoria"];

        $sql = " UPDATE tbl_produto SET descricao = '$descricao', peso = $peso,
                quantidade = $quantidade, cor = '$cor', tamanho = '$tamanho',
                valor = $valor, desconto = $desconto, categoria_id = $categoriaId ";

        //atualizara imagem no banco
        $sql .= isset($novoNomeFoto) ? " , imagem = '$novoNomeFoto' " : "";

        $sql .= " WHERE id = $produtoId ";

        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            $mensagem = "Produto editado com sucesso.";
        } else {
            $mensagem = "Ops, problemas ao editar o produto.";
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: /produtos/index.php");

        break;
}
