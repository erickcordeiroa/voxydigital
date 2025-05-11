# Voxy Digital - Catálogo Online com CMS

Bem-vindo ao repositório do **Voxy Digital**, um projeto de catálogo online. Este sistema foi desenvolvido para facilitar a gestão de produtos, categorias e pedidos, além de oferecer uma interface moderna e responsiva para os usuários finais.

## 🚀 Funcionalidades

- **Gestão de Categorias**: Criação, edição e exclusão de categorias.
- **Gestão de Produtos**: Adicione, edite e organize seus produtos.
- **Gestão de Pedidos**: Clientes efetuarão pedidos na sua loja.
- **SEO e Analytics**: Configurações de SEO e integração com Google Analytics e Meta Pixel.
- **Design Responsivo**: Interface otimizada para dispositivos móveis e desktops.

## 🛠️ Tecnologias Utilizadas

- **Frontend**: Vue 3, Inertia.js, Tailwind CSS
- **Backend**: Laravel 12
- **Banco de Dados**: SQLite (configurável para outros bancos)
- **Outras Ferramentas**:
  - Axios para requisições HTTP
  - Lucide Icons para ícones
  - Reka UI para componentes de interface

## 📂 Estrutura do Projeto

```plaintext
.
├── app/                # Código backend (Models, Providers, etc.)
├── bootstrap/          # Arquivos de inicialização do Laravel
├── config/             # Configurações do Laravel
├── database/           # Arquivos de migração e seeds
├── public/             # Arquivos públicos (imagens, CSS, JS compilado)
├── resources/          # Arquivos frontend (Vue, Tailwind, etc.)
│   ├── js/             # Código JavaScript/Vue
│   ├── css/            # Estilos CSS
├── routes/             # Arquivos de rotas do Laravel
├── storage/            # Arquivos de cache e logs
├── tests/              # Testes automatizados
├── [.env.example](http://_vscodecontentref_/1)        # Exemplo de configuração de ambiente
├── [composer.json](http://_vscodecontentref_/2)       # Dependências PHP
├── [package.json](http://_vscodecontentref_/3)        # Dependências Node.js
└── [vite.config.ts](http://_vscodecontentref_/4)      # Configuração do Vite

```
## ⚙️ Configuração do Ambiente

1 - Clone o repositório
```
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

2 - Instale as dependências do backend
```
composer install
```

3 - Instale as dependências do frontend
```
npm install
```

4 - Configure o arquivo .env
```
cp .env.example .env
```
-- Atualize as variáveis de ambiente conforme necessário.

5 - Gere a chave da aplicação
```
php artisan key:generate
```

6 - Execute as migrações do banco de dados
```
php artisan migrate
```

7 - Inicie o servidor de desenvolvimento
```
npm run dev
php artisan serve
```

## 📄 Licença

- Esse projeto foi desenvolvido pela equipe da Voxy Digital.
