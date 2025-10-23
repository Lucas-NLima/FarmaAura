<?php


        if (empty($fornecedores)) {
            echo "<p>Nenhum fornecedor encontrado!</p>";
            echo "<a href='app/View/Fornecedores/cadastrar.php?'>Cadastrar</a>";
            return;
        }

        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><td><a href='app/View/Fornecedores/cadastrar.php?'>Cadastrar</a> </td></tr>";
        echo "<tr><th>ID</th><th>Nome</th><th>Tipo</th><th>Quantidade</th><th>Preço</th><th>Ações</th></tr>";
    

        foreach ($fornecedores as $fornecedor) {

            $id = $fornecedor['id'];
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$fornecedor['nome']}</td>";
            echo "<td>{$fornecedor['tipo']}</td>";
            echo "<td>{$fornecedor['quantidade']}</td>";
            echo "<td>{$fornecedor['preco']}</td>";
            echo "<td>
            <a href='app/View/Fornecedores/editar.php?id={$id}'>Editar</a> |
            <a href='app/View/Fornecedores/deletar.php?id={$id}' onclick=\"return confirm ('Tem certeza que deseja excluir este fornecedor?')\" >Deletar</a> 
            </td>";
            echo "</tr>";

        }
        echo "</table";

?>