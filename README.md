# SAEP - Sistema de Seleção de Alunos da Escola Profissional

<div align="center">
  
  ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
  ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
  ![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
  ![Soft UI Dashboard](https://img.shields.io/badge/Soft_UI_Dashboard-1E88E5?style=for-the-badge&logo=material-design&logoColor=white)
  ![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

## 📋 Sobre o Projeto

O **SAEP** é uma plataforma digital desenvolvida em **PHP com PDO** para gerenciar de forma eficiente, transparente e automatizada o processo seletivo de alunos para uma Escola Profissional. O sistema substitui processos manuais e burocráticos, garantindo precisão nos cálculos, conformidade com as políticas de cotas estabelecidas em editais e agilidade na divulgação de resultados.

### 🎨 Interface
Para garantir uma experiência de usuário moderna, intuitiva e visualmente agradável, o sistema foi desenvolvido utilizando a **template Soft UI Dashboard** como base para a construção de seu layout. Esta escolha proporciona uma interface limpa, com componentes visuais bem definidos e uma navegação fluida entre os diferentes módulos do sistema.

### 🎯 Objetivo Principal
Classificar os candidatos com base no seu desempenho escolar histórico (notas do 6º ao 9º ano), respeitando rigorosamente os critérios de distribuição por cotas definidos pela secretaria de educação do Ceará.

## 👥 Arquitetura de Perfis de Usuários

### 🔐 **A) Perfil Administrador**
*Perfil com máximo privilégio no sistema, responsável pela configuração geral do processo seletivo.*

**Funcionalidades Principais:**
- ✅ **Gestão de Cursos**: Cadastrar, editar ou inativar cursos profissionais
- ✅ **Abertura de Processos Seletivos**: Criar e configurar novas turmas
- ✅ **Gestão de Usuários**: Cadastrar novos operadores (Admin ou Inscrição)
- ✅ **Gestão de Editais**: Definir quantitativo de vagas por modalidade:
  - Escola Pública (Ampla Concorrência)
  - Escola Pública (Cota de Proximidade)
  - Escola Particular (Ampla Concorrência)
  - Escola Particular (Cota de Proximidade)
  - Alunos com Deficiência ou Neurodivergentes (PCD)
- ✅ **Supervisão Geral**: Dashboard completo e relatórios visuais

### 📝 **B) Perfil Usuário de Inscrição**
*Perfil operacional, focado na inserção e verificação dos dados dos candidatos.*

**Funcionalidades Principais:**
- ✅ **Cadastro de Alunos/Candidatos**: Inserção completa de dados
- ✅ **Dados Obrigatórios para Inscrição**:
  1. Nome completo do aluno
  2. Curso pretendido
  3. Ano de ingresso almejado
  4. **Notas Escolares Históricas** (6º ao 9º ano)
  5. Informações para enquadramento nas cotas

## ⚙️ Algoritmo de Seleção

### 🔢 **Processo Automatizado:**
```math
Média Geral = (soma da média de cada ano - 6º, 7º, 8º e 9º) / (Número total de médias)
```

### 📊 **Etapas do Processo:**
1. **Cálculo da Média Geral** para cada aluno
2. **Classificação por Curso** (ordem decrescente)
3. **Aplicação das Cotas** do edital
4. **Geração dos Resultados Finais**:
   - 🟢 **Classificados (Aprovados)**
   - 🟡 **Classificáveis (Lista de Espera)**

## 📈 Módulo de Relatórios

### 📋 **Funcionalidades:**
- 🔍 **Página de Relatórios** com filtros avançados
- 📊 **Busca por**: nome, curso ou status
- 🖨️ **Exportação para PDF** dos resultados
- 📑 **Documentos Oficiais** gerados automaticamente

## 🚀 Instalação e Configuração

### Pré-requisitos:
- ✅ Servidor Web (Apache, Nginx, etc.)
- ✅ PHP 7.4 ou superior
- ✅ MySQL 5.7 ou superior
- ✅ Extensão PHP para MySQL habilitada

### 📥 Passo a passo de Instalação:

```bash
# 1. Clone o repositório
git clone https://github.com/leandrolti/saep.git

# 2. Acesse o diretório do projeto
cd saep

# 3. Configure o banco de dados MySQL
# - Crie um banco de dados no seu MySQL
# - Importe o arquivo de banco: mysql/saep2025.sql
# Exemplo via linha de comando:
mysql -u seu_usuario -p nome_do_banco < mysql/saep2025.sql

# 4. Configure a conexão com o banco de dados
# Edite o arquivo config/conexao.php:
'host' => 'localhost',
'dbname' => 'nome_do_banco_criado',
'user' => 'seu_usuario_mysql',
'pass' => 'sua_senha_mysql'

# 5. Copie os arquivos para o diretório do servidor web
# Para Apache (Linux):
sudo cp -r saep /var/www/html/

# 6. Ajuste as permissões (se necessário)
sudo chmod -R 755 /var/www/html/saep/
sudo chown -R www-data:www-data /var/www/html/saep/  # Linux/Apache

# 7. Acesse o sistema no navegador
# http://localhost/saep/
```

### ⚙️ **Configuração Detalhada:**

#### 1. **Configuração do Banco de Dados:**
```sql
-- Via phpMyAdmin ou linha de comando:
CREATE DATABASE saep_sistema;
USE saep_sistema;

-- Importar o arquivo SQL:
SOURCE caminho/para/saep/mysql/saep2025.sql
```

#### 2. **Configuração do Arquivo `config/conexao.php`:**
```php
<?php
return [
    'host' => 'localhost',      // Endereço do servidor MySQL
    'dbname' => 'saep_sistema', // Nome do banco de dados criado
    'user' => 'root',           // Usuário do MySQL
    'pass' => ''                // Senha do MySQL
];
```

#### 3. **Configuração do Servidor Web:**
- **Apache**: Certifique-se de que o módulo `mod_rewrite` está habilitado
- **Permissões**: Diretórios de upload e cache devem ter permissão de escrita
- **PHP**: Extensões necessárias: `pdo_mysql`, `mbstring`, `gd`

### 🔧 **Solução de Problemas Comuns:**

1. **Erro de conexão com o banco de dados:**
   - Verifique as credenciais em `config/conexao.php`
   - Confirme se o MySQL está em execução
   - Teste a conexão manualmente com as credenciais

2. **Página em branco ou erros 500:**
   - Verifique logs de erro do PHP e Apache
   - Confirme permissões de arquivo
   - Habilite `display_errors` no PHP para depuração

3. **Problemas com importação do banco de dados:**
   - Verifique o tamanho do arquivo SQL
   - Confirme a versão do MySQL
   - Execute importação via linha de comando se possível

## 📊 Dados de Acesso Inicial

Após a instalação, você pode acessar o sistema com as seguintes credenciais:

- **Usuário Administrador:**
  - Login: `admin@gmail.com`
  - Senha: `admin`

- **Usuário de Inscrição:**
  - Login: `ins@gmail.com`
  - Senha: `ins`

**⚠️ Importante:** Altere estas senhas após o primeiro acesso!

## 🏛️ Contexto Institucional
O SAEP foi desenvolvido para atender às necessidades específicas do **Estado do Ceará**, garantindo que o processo seletivo para escolas profissionais seja **justo, eficiente e baseado em mérito acadêmico**, dentro das políticas de inclusão social estabelecidas pela secretaria de educação.

## 📄 Estrutura de Diretórios básica

```
saep/
├── .github/                 # Configurações do GitHub
├── assets/                  # Recursos estáticos (CSS, JS, imagens)
│   ├── css/
│   ├── js/
│   └── img/
├── config/                  # Configurações do sistema
│   └── conexao.php         # Configuração do banco de dados
├── include/                 # Classes e funções PHP
├── mySql/                   # Scripts do banco de dados
│   └── saep2025.sql        # Estrutura e dados iniciais
├── pages/                   # Páginas do sistema
├── vendor/                  # Dependências do Composer
├── LICENSE                  # Licença MIT
├── README.md               # Este arquivo
├── composer.json           # Dependências do projeto
├── composer.lock          # Versões travadas das dependências
└── index.php              # Ponto de entrada do sistema(login)
```

## 📄 Licença

Este projeto está licenciado sob a **Licença MIT** - veja o arquivo [LICENSE](LICENSE) para detalhes.

```
MIT License

Copyright (c) 2025 SAEP - Sistema de Seleção de Alunos da Escola Profissional

Permissão é concedida, gratuitamente, a qualquer pessoa que obtenha uma cópia
deste software e dos arquivos de documentação associados (o "Software"), para lidar
no Software sem restrição, incluindo, sem limitação, os direitos de usar, copiar,
modificar, mesclar, publicar, distribuir, sublicenciar e/ou vender cópias do Software,
...
```

## 🤝 Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para:

1. Reportar bugs [abrindo uma issue](https://github.com/leandrolti/saep/issues)
2. Sugerir novas funcionalidades
3. Enviar pull requests com melhorias

## 📞 Suporte

Para suporte com instalação ou problemas técnicos:

1. **Issues do GitHub**: [Abrir Issue](https://github.com/leandrolti/saep/issues)
2. **Verifique os logs** do servidor para erros específicos
3. **Confira a seção** de Solução de Problemas acima

---

<div align="center">
  
  **🎓 Desenvolvido por alunos e atualizado e mantido por Leandro Costa Professor e Coordenador da EEEP José Maria Falcão**
  
  [![Acessar Sistema](https://img.shields.io/badge/🌟_Acessar_SAEP-1E88E5?style=for-the-badge&logo=google-chrome&logoColor=white)](#)
  [![GitHub Repo](https://img.shields.io/badge/📂_Repositório-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/leandrolti/saep)

</div>

<p align="center">
  <i>Se este projeto ajudou você, considere dar uma ⭐ no repositório!</i>
</p>
