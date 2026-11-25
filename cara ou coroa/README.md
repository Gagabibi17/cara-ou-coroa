# Cara ou Coroa 🪙

Site dinâmico e responsivo para lançamento de moeda (Cara ou Coroa), desenvolvido com foco em alta performance, usabilidade móvel (**Mobile-First**) e fidelidade estrita aos requisitos técnicos.

---

## 🎯 Objetivo & Proposta
Criar um site dinâmico com a proposta de girar uma moeda com dois lados (**cara ou coroa**), ideal para tomada de decisões no cotidiano, disputas ou jogos rápidos.

---

## 🛠️ Stack Tecnológica
- **Backend:** PHP estruturado com sessões nativas (`session_start()`, isolado em scripts modulares).
- **Banco de Dados:** MySQL com **PDO** (Prepared Statements obrigatórios para proteção total contra SQL Injection).
- **Frontend:** HTML5, CSS customizado com física 3D, e Tailwind CSS (Mobile-First).
- **Efeitos Sonoros:** Síntese de áudio em tempo real com a **Web Audio API** (funciona 100% offline, sem dependência de mídias externas).

---

## 📋 Regras de Negócio e Globais Atendidas

1. **Acesso Livre (CORE):** Sem formulário de usuário e senha para entrar no site.
2. **Botão Chamativo (CORE):** Na tela inicial há um botão proeminente, grande e chamativo escrito **GIRAR**, que arremessa a moeda com animação física tridimensional e exibe o resultado.
3. **Segurança Máxima com PDO:** Conexões e queries no MySQL preparadas com `PDO::ATTR_EMULATE_PREPARES => false` e tratamento via `try/catch`.
4. **Arquitetura Desacoplada (Anti-Monólito):**
   - Arquivo exclusivo para conexão (`backend/bd.php`).
   - Scripts de backend isolados (`backend/girar.php`, `backend/historico.php`, `backend/estatisticas.php`, `backend/limpar_historico.php`).
   - Views separadas (`views/header.php`, `views/moeda.php`, `views/placar.php`, `views/historico.php`, `views/footer.php`).
5. **Estilização Mobile-First com Tailwind CSS:** Interface fluida e responsiva, otimizada desde telas de smartphones compactos até monitores ultra-wide.
6. **Mensagens e Erros via TOAST:** Notificações em tempo real elegantes com temporizador e barra de progresso.
7. **Modais Customizados no lugar de Caixas Nativas:** Todas as mensagens de alerta e confirmação do navegador são tratadas através de modais customizados com suporte à tecla ESC e backdrop blur.
8. **Resiliência e Fallback Gracioso:** Caso o MySQL ainda não tenha sido iniciado pelo usuário, o sistema opera automaticamente em modo de **Sessão Nativa PHP**, mantendo 100% da usabilidade sem quebrar a página, e avisando via Toast amigável.

---

## 📂 Estrutura de Arquivos

```
cara-ou-coroa/
├── assets/
│   ├── css/
│   │   └── style.css            # Física 3D da moeda, relevo metálico e keyframes
│   └── js/
│       ├── audio.js             # Síntese de som via Web Audio API (flip & land)
│       ├── toast.js             # Sistema dinâmico de notificações Toast
│       ├── modal.js             # Sistema de Modais (substitui alert e confirm nativos)
│       └── app.js               # Orquestrador: clique, animação 3D e AJAX
├── backend/
│   ├── bd.php                   # Conexão segura PDO MySQL com fallback
│   ├── girar.php                # Sorteio aleatório justo, persistência e resposta JSON
│   ├── historico.php            # Endpoint de listagem do histórico via PDO/Sessão
│   ├── estatisticas.php         # Consolidação de totais, porcentagens e sequências
│   └── limpar_historico.php     # Reset seguro do histórico com Prepared Statements
├── views/
│   ├── header.php               # Cabeçalho, Tailwind CDN e barra superior
│   ├── moeda.php                # Moeda 3D com faces Cara/Coroa e botão chamativo GIRAR
│   ├── placar.php               # Métricas e barras de porcentagem Mobile-First
│   ├── historico.php            # Lista de lançamentos e ação de limpar
│   ├── toasts.php               # Container para os toasts
│   ├── modais.php               # Container para os modais
│   └── footer.php               # Rodapé e inclusão ordenada dos scripts JS
├── database.sql                 # Script SQL para criação do banco e tabelas no MySQL
├── index.php                    # Front Controller modular
├── LICENSE                      # Licença MIT
├── README.md                    # Documentação do projeto
└── requisitos.MD                # Especificações oficiais do repositório
```

---

## 🚀 Como Executar o Projeto

### Opção 1: Servidor Embutido do PHP (Rápido e Simples)
Caso tenha o PHP instalado no seu terminal:
```bash
# Na pasta do projeto:
php -S localhost:8000
```
Em seguida, acesse no navegador: `http://localhost:8000`

### Opção 2: Utilizando XAMPP / WAMP / Laragon
1. Copie ou mova a pasta `cara ou coroa` para a pasta pública do seu servidor:
   - **XAMPP:** `C:\xampp\htdocs\cara-ou-coroa`
   - **WAMP:** `C:\wamp64\www\cara-ou-coroa`
   - **Laragon:** `C:\laragon\www\cara-ou-coroa`
2. Inicie os módulos **Apache** e **MySQL** no painel do XAMPP/Laragon.
3. No navegador, acesse: `http://localhost/cara-ou-coroa`

### Configuração do Banco de Dados MySQL (Opcional):
- O sistema já cria o banco `cara_ou_coroa` e a tabela `lancamentos` automaticamente ao se conectar ao MySQL (com usuário `root` e senha vazia padrão).
- Se preferir criar manualmente, importe o arquivo `database.sql` no phpMyAdmin ou no terminal:
  ```bash
  mysql -u root -p < database.sql
  ```

---

## 📜 Licença
Distribuído sob a licença MIT. Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.
