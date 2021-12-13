<?php
require("../../database/conexaoBD.php");
  session_start();
  //Verifica se o usuáriio não está logado
  if (!isset($_SESSION["usuarioId"])) {
    $_SESSION["mensagem"] = "Acesso negado, você precisa logar";
    
    header("location: ../index.php");
  }

  $produtoId = $_GET["id"];

  $sqlProduto = "SELECT * FROM tbl_produto WHERE id = $produtoId";
  $resultado = mysqli_query($conexao, $sqlProduto);
  $produto = mysqli_fetch_array($resultado);

  if(!$produto){
    echo "Produto não encontrado";
    exit();
  }

  $sql = "SELECT * FROM tbl_categoria";
  $resultado = mysqli_query($conexao,$sql);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../styles-global.css" />
  <link rel="stylesheet" href="./editar.css" />
  <title>Editar Produtos</title>
</head>

<body>
<?php
    include("../../componentes/header/header.php");
    ?>
  <div class="content">
    <section class="produtos-container">
      <main>
        <form class="form-produto" method="POST" action="../novo/acoes.php" enctype="multipart/form-data">
          <h1>Editar produto</h1>
          <ul>
          <?php
            //Verifica se existe erro na sessão do usúario
            if(isset($_SESSION["erros"])){
              //Se existe erro lista ele
              foreach($_SESSION["erros"] as $erro){
          ?>
                <li><?= $erro ?></li>
          <?php      
              }
              //eliminar os erros já mostratos
              unset($_SESSION["erros"]);
            }
          ?>
          </ul>

          <div class="input-group span2">
            <label for="descricao">Descrição</label>
            <input type="text" value="<?= $produto['descricao']?>" id="descricao" name="descricao" require>
          </div>
          <div class="input-group">
            <label for="peso">Peso</label>
            <input type="text" value="<?= number_format($produto['peso'], 2, ",", ".") ?>" min="0" id="peso" name="peso" require>
          </div>
          <div class="input-group">
            <label for="quantidade">Quantidade</label>
            <input type="number" value="<?= $produto['quantidade']?>" min="0" id="quantidade" name="quantidade" require>
          </div>
          <div class="input-group">
            <label for="cor">Cor</label>
            <input type="text" value="<?= $produto['cor']?>" id="cor" name="cor" require>
          </div>
          <div class="input-group">
            <label for="tamanho">Tamanho</label>
            <input type="text" value="<?= $produto['tamanho']?>" id="tamanho" name="tamanho">
          </div>
          <div class="input-group">
            <label for="valor">Valor</label>
            <input type="text" value="<?= number_format($produto['valor'], 2, ",", ".")?>" min="0" id="valor" name="valor" require>
          </div>
          <div class="input-group">
            <label for="desconto">Desconto</label>
            <input type="number" value="<?= $produto['desconto']?>" min="0" id="desconto" name="desconto">
          </div>

          <div class="input-group">
            <label for="categoria">Categorias</label>
              <select type="text" name="categoria" id="categorias">
                <option value="">SELECIONE</option>
                <?php
                  while ($categoria = mysqli_fetch_array($resultado)) {
                ?>
                <option value="<?= $categoria["id"] ?>" <?= $categoria["id"] == $produto["categoria_id"] ? "selected" : "" ?>>
                  <?=$categoria["descricao"]?>
                </option>
                <?php
                  }
                ?>
              </select>
          </div>

          <div class="input-group">
            <label for="foto">Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
          </div>

          <button onclick="javascript:window.location.href = '../../produtos'" type="button">Cancelar</button>
          <input type="hidden" name="produtoId" value="<?= $produto['id'] ?>"/>
          <input type="hidden" name="acao" value="editar"/>
          <button>Salvar</button>
        </form>
      </main>
    </section>
  </div>
  <footer>
    SENAI 2021 - Todos os direitos reservados
  </footer>
</body>

</html>