# Pegar imagem oficial do Node
FROM node:18

# Criar a pasta de trabalho dentro do container
WORKDIR /app

# Copiar apenas package.json e package-lock.json para instalar dependências
COPY package*.json ./

# Instalar as dependências
RUN npm install

# Copiar o restante do código
COPY . .

# Fazer o build (gerar a pasta dist)
RUN npm run build

# Instalar o serve (servidor para rodar o React)
RUN npm install -g serve

# Rodar o frontend no container na porta 3000
CMD ["serve", "-s", "dist", "-l", "3000"]
