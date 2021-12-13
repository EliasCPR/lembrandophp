<?php

/**
 * NOTICE => notas de erros não críticos
 * WARNINGS => alertas de erros, mas não fatais. Devem ser tratados.
 * FATAL_ERRORS => erros graves que impedem o funcionamento do código.
 */
session_start();
require("../database/conexaoBD.php");

$sql = " SELECT p.*, c.descricao as categoria FROM tbl_produto p
        INNER JOIN tbl_categoria c ON p.categoria_id = c.id ";

if (isset($_GET["p"]) && $_GET["p"] != "") {
    $p = $_GET["p"];
    $sql .= " WHERE p.descricao LIKE '%$p%' OR c.descricao LIKE '%$p%' ";
}

$sql .= " ORDER BY p.id DESC ";

$resultado = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles-global.css" />
    <link rel="stylesheet" href="./produtos.css" />
    <title>Administrar Produtos</title>
</head>

<body>
    <?php
    include("../componentes/header/header.php");
    ?>
    <div class="content">

        <section class="produtos-container">

            <?php
            //verificar o $_SESSION
            if (isset($_SESSION["usuarioId"])) {
                //mostar os botoes somente caso o usuario esteja logado
            ?>
                <header>
                    <button onclick="javascript:window.location.href ='./novo/'">Novo Produto</button>
                    <button onclick="javascript:window.location.href ='../categorias'">Adicionar Categoria</button>
                </header>
            <?php
            }
            ?>

            <main>
                <?php
                while ($produto = mysqli_fetch_array($resultado)) {
                    if ($produto["desconto"] > 0) {
                        $desconto = $produto["desconto"] / 100;
                        $novoValor = $produto["valor"] - $desconto * $produto["valor"];
                    } else {
                        $novoValor = $produto["valor"];
                    }

                    $qtdeParcelas = $novoValor > 1000 ? 12 : 6;
                    $valorParcela = $novoValor / $qtdeParcelas;
                    $valorParcela = number_format($valorParcela, 2, ",", ".");
                ?>

                    <article class="card-produto">
                        <?php
                        if (isset($_SESSION["usuarioId"])) {
                        ?>
                            <div class="acoes">
                                <img onclick="javascript:window.location.href ='./editar/index.php?id=<?= $produto['id'] ?>'" src="../imgs/edit.svg">
                                <img onclick="deletar(<?= $produto['id'] ?>)" src="../imgs/trash.svg">
                            </div>
                        <?php
                        }
                        ?>
                        <figure>
                            <img src="fotos/<?= $produto["imagem"] ?>" />
                        </figure>
                        <section>
                            <span class="preco">R$<?= number_format($novoValor, 2, ",", ".") ?>
                                <?php
                                if ($produto["desconto"] > 0) {
                                ?>
                                    <em>
                                        <?= $produto["desconto"] ?>% off
                                    </em>
                                <?php
                                }
                                ?>
                            </span>

                            <span class="parcelamento">ou em <em><?= $qtdeParcelas ?>x <?= $valorParcela ?> sem juros</em></span>

                            <span class="descricao"><?= $produto["descricao"] ?></span>
                            <span class="categoria">
                                <em><?= $produto["categoria"] ?></em>
                            </span>

                            <!-- <?php
                                    //verificar o $_SESSION
                                    if (isset($_SESSION["usuarioId"])) {
                                    ?>
                                <img onclick="deletarProduto(<?= $produto['id'] ?>)" src="https://icons.veryicon.com/png/o/construction-tools/coca-design/delete-189.png" />
                            <?php
                                    }
                            ?> -->

                        </section>
                        <footer>

                        </footer>
                    </article>
                <?php
                }
                ?>
                <form id="form-deletar" method="POST" action="/produtos/novo/acoes.php">
                    <input type="hidden" name="acao" value="deletar" />
                    <input id="produtoId" type="hidden" name="produtoId" />
                </form>

            </main>
        </section>
    </div>
    <footer>
        SENAI 2021 - Todos os direitos reservados
    </footer>
</body>
<script lang="javascript">
    function deletar(produtoId) {
        if (confirm("Deseja realmente excluir este produto?")) {
            document.querySelector('#produtoId').value = produtoId;
            document.querySelector('#form-deletar').submit();
        }
    }
</script>

</html>