<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Começar uma transação para garantir que tudo seja excluído corretamente
    $conn->begin_transaction();

    try {
        // Excluir os itens de compra relacionados ao livro
        $sqlItensCompra = "DELETE FROM ItensCompra WHERE idLivro = ?";
        $stmtItensCompra = $conn->prepare($sqlItensCompra);
        $stmtItensCompra->bind_param("i", $id);
        $stmtItensCompra->execute();

        // Agora, excluir o livro
        $sql = "DELETE FROM Livro WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Se tudo ocorrer bem, comitar as mudanças
        $conn->commit();

        // Redirecionar para a página principal com uma mensagem de sucesso
        header("Location: index.php?message=Book+deleted+successfully");
        exit;
    } catch (Exception $e) {
        // Em caso de erro, desfazer a transação
        $conn->rollback();
        // Exibir mensagem de erro
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirecionar caso o ID não seja fornecido
    header("Location: index.php?error=Invalid+book+ID");
    exit;
}
?>
