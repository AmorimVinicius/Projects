# Pegar a imagem oficial do Python
FROM python:3.11

# Criar uma pasta dentro do container
WORKDIR /app

# Copiar tudo da pasta modelo para dentro do container
COPY . .

# Instalar as bibliotecas do requirements.txt
RUN pip install -r requirements.txt

# Rodar a API quando o container iniciar
CMD ["uvicorn", "api:app", "--host", "0.0.0.0", "--port", "8000", "--reload"]

