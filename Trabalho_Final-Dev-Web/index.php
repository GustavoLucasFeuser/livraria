<?php
include 'database.php';
session_start();

// Habilitar a exibição de erros para facilitar o diagnóstico
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja de livros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Livros disponíveis</h2>
    <div class="row">
        <?php
        // Query com JOINs para buscar os dados do livro, autor e categoria
        $sql = "
        SELECT 
            Livro.id, Livro.titulo, Livro.preco, Livro.fotoCapa, 
            Autor.nome AS autor, Categorias.descricao AS categoria
        FROM Livro
        INNER JOIN Autor ON Livro.idAutor = Autor.id
        INNER JOIN Categorias ON Livro.idCategoria = Categorias.id
        ";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Exibe os resultados encontrados
            while($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-3">
                <div class="card">
                    <img src="<?= $row['fotoCapa'] ?>" class="card-img-top" alt="Book Cover">
                    <div class="card-body">
                        <!-- Título com Categoria -->
                        <h5 class="card-title">
                            <?= $row['titulo'] ?> 
                            <small class="text-muted">(<?= $row['categoria'] ?>)</small>
                        </h5>
                        <p class="card-text"><strong>Autor:</strong> <?= $row['autor'] ?></p>
                        <p class="card-text"><strong>Preço($):</strong> $<?= $row['preco'] ?></p>
                        <a href="cart.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger">Comprar</a>
                        <!-- Botão de deletar -->
                        <a href="delete.php?id=<?= $row['id'] ?>" 
                           class="btn btn-outline-dark"
                           onclick="return confirm('Você tem certeza que deseja excluir este livro?');">
                           Excluir
                        </a>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
        } else {
            echo "<p>Não há livros disponíveis no momento</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
