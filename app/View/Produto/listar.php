<?php


        if (empty($produtos)) {
            echo "<p>Nenhum produto encontrado!</p>";
            echo "<a href='View/Produto/cadastrar.php?'>Cadastrar</a>";
            return;
        }

        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><td><a href='View/Produto/cadastrar.php?'>Cadastrar</a> </td></tr>";
        echo "<tr><th>ID</th><th>Nome</th><th>Tipo</th><th>Quantidade</th><th>Validade</th><th>Marca</th><th>Preço</th><th>Pagamento</th><th>Ações</th></tr>";
    

        foreach ($produtos as $produto) {

            $id = $produto['id'];
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$produto['nome']}</td>";
            echo "<td>{$produto['tipo']}</td>";
            echo "<td>{$produto['quantidade']}</td>";
            echo "<td>{$produto['validade']}</td>";
            echo "<td>{$produto['marca']}</td>";
            echo "<td>{$produto['preco']}</td>";
            echo "<td>{$produto['formapagamento']}</td>";
            echo "<td>
            <a href='View/Produto/editar.php?id={$id}'>Editar</a> |
            <a href='View/Produto/deletar.php?id={$id}' onclick=\"return confirm ('Tem certeza que deseja excluir este produto?')\" >Deletar</a> 
            </td>";
            echo "</tr>";

        }
        echo "</table";

?>