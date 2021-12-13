<?php
session_start();
    require("../database/conexaoBD.php");
    //Verifica se o usuáriio não está logado
      if (!isset($_SESSION["usuarioId"])) {
        $_SESSION["mensagem"] = "Acesso negado, você precisa logar";
        
        header("location: ../produtos/index.php");
      }

    $sql = "SELECT * FROM tbl_categoria";
    $resultado = mysqli_query($conexao,$sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles-global.css" />
    <link rel="stylesheet" href="./categorias.css" />
    <title>Administrar Categorias</title>
</head>
<body>
    <?php
    include("../componentes/header/header.php");
    ?>
    <div class="content">
        <section class="categorias-container">
            <main>
                <form class="form-categorias" method="POST" action="acoesCategorias.php">
                    <h1 class="span2">Adicionar Categorias</h1>
                    <div class="input-group span2">
                        <label for="descricao">Descricao</label>
                        <input type="text" name="descricao" id="descricao" />
                    </div>
                    <button onclick="javascript:window.location.href ='../produtos'" type="button">Cancelar</button>

                    <input type="hidden" name="acao" value="inserir"/>
                    <button>Salvar</button>
                </form>
                <h1>Lista de categorias</h1>

                <?php
                if(mysqli_num_rows($resultado) == 0){
                    echo "<center> Nenhuma categoria cadastrada</center>";
                }

                while ($categoria = mysqli_fetch_array($resultado)) {
                ?>
                    <div class="card-categorias">
                        
                        <?=$categoria["descricao"]?>
                        <img onclick="deletar(<?= $categoria['id']?>)" src="https://icons.veryicon.com/png/o/construction-tools/coca-design/delete-189.png" />

                    </div>
                <?php
                }
                ?>
                <form id="form-delecao" style="display: none;" method="POST" action="acoesCategorias.php">
                    <input type="hidden" name="acao" value="delecao"/>
                    <input id="categoriaId" type="hidden" name="categoriaId" value=""/> 
                </form>
            </main>
        </section>
    </div>
<script lang="javascript">
    function deletar(categoriaId){
        document.querySelector('#categoriaId').value = categoriaId;
        document.querySelector('#form-delecao').submit();
    }
</script>
</body>
</html>