# Módulo Nathan_ButtonColor para Magento 2

## 📦 Visão Geral

Este módulo adiciona um novo comando de console ao Magento 2 que permite alterar dinamicamente a cor de todos os botões do site para uma cor hexadecimal especificada. A alteração é feita por meio da injeção de CSS diretamente na configuração da loja.

---

## 🧩 Comando Disponível

```
bin/magento color:change <cor_hexadecimal> <store_id>
```

## 🔧 Parâmetros:
* <cor_hexadecimal>: Cor no formato hexadecimal (ex: 000000, ff0000).

* <store_id>: ID da store view Magento onde a alteração deve ser aplicada.

## ✅ Exemplo:
```
bin/magento color:change 00ff00 1
```

## ⚙️ Como Funciona
* O comando salva uma tag \<style> com uma variável CSS (--button-background) na configuração design/head/includes da store view especificada.

* Um layout XML (default_head_blocks.xml) já está configurado para garantir que esse conteúdo seja carregado no <head> das páginas.

* O frontend deve usar var(--button-background) nos estilos dos botões para refletir a cor definida.

## 🎨 Exemplo de CSS no tema
Para que o tema utilize a cor configurada, adicione o seguinte trecho no seu CSS:

```css
:root { --button-background: #ff0000; }

:root {
    --button-background: #ff0000;
}
button,
.button,
button.action,
.action.primary {
    background-color: var(--button-background, #000000) !important;
    border-color: var(--button-background, #000000) !important;
}
```

## 🛠️ Implementação
O comando `color:change` salva a cor no campo de configuração `design/head/includes` da store view.

Um bloco (`Block\Head\ButtonColor`) foi adicionado ao layout XML para ler esse valor e gerar dinamicamente uma \<style> no <head> da página.

A variável CSS `--button-background` é definida no `:root`, e estilos para elementos como button, .button, .action.primary são automaticamente aplicados com essa cor.

A flag !important é usada para garantir que os estilos sobrescrevam os temas nativos do Magento.

A abordagem não exige mudanças manuais no tema ou no código CSS/LESS do frontend.

## 📦 Instalação
1. Copie o módulo para o diretório `app/code`.
2. Execute os seguintes comandos:

```
bin/magento setup:upgrade
bin/magento cache:clean
```

## 📄 Observações
* O módulo não altera arquivos de tema diretamente, apenas injeta a variável no HTML da loja.

* Para ambientes com cache full-page habilitado, limpe o cache após executar o comando.


## 📚 Requisitos
Magento 2.4.x

PHP >= 8.1

Tema compativel (Testado no Luma).

## 👨‍💻 Desenvolvido por
Nathan Alves – Desenvolvedor Magento 2.


