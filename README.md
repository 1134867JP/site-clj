# CifraDocs

CifraDocs é uma aplicação web desenvolvida em Laravel para gerenciar cantos litúrgicos de forma prática e organizada. Com uma interface intuitiva e recursos úteis, o CifraDocs facilita o acesso e a administração de cifras e músicas.

## Funcionalidades

- Gerenciamento de cantos litúrgicos.
- Interface amigável e responsiva.
- Sistema de autenticação para usuários.
- Registro e login de novos usuários.
- Suporte a cifras e organização por categorias.

## Tecnologias Utilizadas

- **Backend**: [Laravel](https://laravel.com) 10.x
- **Frontend**: Tailwind CSS e Vite
- **Banco de Dados**: MySQL
- **Outras Dependências**:
  - Alpine.js
  - Axios
  - Tailwind Forms

## Requisitos

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- MySQL ou outro banco de dados compatível

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/cifradocs.git
   cd cifradocs
   ```

2. Instale as dependências:
   ```bash
   composer install
   npm install
   ```

3. Configure o ambiente:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Execute as migrações:
   ```bash
   php artisan migrate
   ```

5. Construa os assets:
   ```bash
   npm run build
   ```

6. Inicie o servidor:
   ```bash
   php artisan serve
   ```

## Deploy no Render.com

Para fazer o deploy no Render.com, siga estes passos:

1. **Fork este repositório** para sua conta do GitHub.

2. **Conecte ao Render.com**:
   - Acesse [Render.com](https://render.com)
   - Conecte sua conta do GitHub
   - Clique em "New+" e selecione "Web Service"
   - Conecte este repositório

3. **Configure o serviço**:
   - **Name**: `cifradocs` (ou o nome que preferir)
   - **Environment**: `PHP`
   - **Build Command**: `./build.sh`
   - **Start Command**: `./start.sh`
   - **Branch**: `main` (ou sua branch principal)

4. **Configure as variáveis de ambiente**:
   Adicione estas variáveis na seção "Environment Variables":
   ```
   APP_NAME=CifraDocs
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=
   DB_CONNECTION=sqlite
   SESSION_DRIVER=file
   CACHE_STORE=file
   QUEUE_CONNECTION=sync
   LOG_LEVEL=info
   ```
   
   ⚠️ **Importante**: O `APP_KEY` será gerado automaticamente durante o build.

5. **Deploy**:
   - Clique em "Create Web Service"
   - O Render.com executará automaticamente o build e deploy
   - Aguarde alguns minutos para o processo completar

### Solução de Problemas

**Erro de permissão durante o build:**
- O script `build.sh` já configura as permissões corretas
- As pastas `storage` e `bootstrap/cache` são criadas com permissões 775

**Erro de dependências do Composer:**
- O build usa `--prefer-dist` para evitar problemas com tokens do GitHub
- As dependências de desenvolvimento são excluídas (`--no-dev`)

**Problemas com banco de dados:**
- Por padrão, usa SQLite que é criado automaticamente
- Para PostgreSQL, adicione as variáveis de ambiente do banco de dados

**Assets não carregam:**
- O comando `npm run build` é executado automaticamente
- Os assets são otimizados para produção com Vite