# Módulo Nathan\_LanguageTag para Magento 2

## 🧩 Descrição

O módulo `Nathan_LanguageTag` adiciona automaticamente \*\*tags \*\***`<link rel="alternate" hreflang="...">`** no `<head>` das páginas CMS do Magento 2. Isso permite que mecanismos de busca, como o Google, identifiquem e indexem corretamente versões traduzidas de uma mesma página em diferentes store views (idiomas/países), otimizando o SEO internacional da loja.

---

## 🚀 Funcionalidades

* Detecta quando a página atual é uma CMS (`cms_page_view`).
* Verifica se essa página existe e está ativa em outros store views.
* Gera links alternativos (`hreflang`) no formato adequado (ex: `pt-br`, `en-us`, etc).
* Insere automaticamente essas tags no cabeçalho HTML da página.
* Ignora store views onde a página está inativa ou sem URL reescrita válida.
* Logger incluído para rastrear erros silenciosamente.

---

## 🛠️ Implementação

### Principais Componentes

* `Block\LanguageTag`
  Renderiza as tags HTML no `<head>` da página CMS com base nas informações retornadas pelo service.

* `Service\LanguageTagGenerator`
  Responsável por buscar a página CMS em todos os store views, verificar se está ativa e recuperar as URLs reescritas para construir os links `hreflang`.

* `view/frontend/layout/default_head_blocks.xml`
  Define a inclusão automática do bloco `Nathan\LanguageTag\Block\LanguageTag` no container `<head.additional>`.

---

## 🧪 Cobertura de Testes

Este módulo já conta com testes unitários completos:

* Testes para o bloco `LanguageTag`, cobrindo diferentes cenários (sem ID, página errada, exceções, etc.).
* Testes para o service `LanguageTagGenerator`, incluindo simulações de store views, URLs e identificadores de página.

---

## 📦 Instalação

1. Copie o módulo para o diretório `app/code`.
2. Execute os seguintes comandos:

```
bin/magento setup:upgrade
bin/magento cache:clean
```

3. Verifique se as tags hreflang estão sendo renderizadas acessando uma página CMS com versões em múltiplos store views.

📄 Exemplo de saída gerada
```html
<link rel="alternate" hreflang="en-us" href="https://sualoja.com/en-us/about-us"/>
<link rel="alternate" hreflang="pt-br" href="https://sualoja.com/pt-br/about-us"/>
```

## 📚 Requisitos
Magento 2.4.x

PHP >= 8.1

Store views devidamente configuradas com idiomas e códigos regionais.



## 👨‍💻 Desenvolvido por
Nathan Alves – Desenvolvedor Magento 2.

