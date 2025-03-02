from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
import pandas as pd
import random
import os

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

BASE_PATH = os.path.dirname(__file__)

# Arquivos
HISTORICO_FILE = os.path.join(BASE_PATH, "arquivos/historico.csv")
NOTICIAS_CLUSTERIZADAS_FILE = os.path.join(BASE_PATH, "arquivos/noticias_clusterizadas.csv")
VALIDACAO_FILE = os.path.join(BASE_PATH, "arquivos/validacao.csv")
RECOMENDACOES_FILE = os.path.join(BASE_PATH, "arquivos/recomendacoes_usuarios.csv")
TOP10_FILE = os.path.join(BASE_PATH, "arquivos/top10_noticias.csv")

# Carregar DataFrames
df_historico = pd.read_csv(HISTORICO_FILE)
df_noticias = pd.read_csv(NOTICIAS_CLUSTERIZADAS_FILE)
df_validacao = pd.read_csv(VALIDACAO_FILE)
df_recomendacoes = pd.read_csv(RECOMENDACOES_FILE)
df_top10 = pd.read_csv(TOP10_FILE)

# Garantir colunas lowercase
for df in [df_historico, df_noticias, df_validacao, df_recomendacoes, df_top10]:
    df.columns = [col.lower() for col in df.columns]

# Filtros
users_with_validation = set(df_validacao['userid'].unique())
df_historico = df_historico[df_historico['userid'].isin(users_with_validation)]

# Pegar apenas a primeira notícia lida na validação por cada user
df_validacao = df_validacao.sort_values(by=['userid', 'timestamphistory'])
df_validacao = df_validacao.groupby('userid').head(1).reset_index(drop=True)

# Enriquecer com informações de notícias
df_historico = df_historico.merge(df_noticias, on="page", how="left")
df_validacao = df_validacao.merge(df_noticias, on="page", how="left")
df_recomendacoes = df_recomendacoes.merge(df_noticias, on="page", how="left")

# Simulação
simulated_users = {}
profile_indexes = {}

@app.get("/prepare-simulated-users")
def prepare_simulated_users():
    global simulated_users, profile_indexes

    # Criar 20 users fake
    fictitious_users = [f"fake_user_{i+1}" for i in range(20)]

    # Contar histórico por usuário
    user_history_counts = df_historico.groupby('userid')['page'].count()

    users_historico_curto = user_history_counts[user_history_counts <= 2].index.tolist()
    users_historico_longo = user_history_counts[user_history_counts >= 3].index.tolist()

    # Garantir no máximo 20 de cada perfil real
    def get_random_users(user_list, count=20):
        return random.sample(user_list, min(len(user_list), count))

    simulated_users = {
        "user_fake": fictitious_users,
        "user_historico_curto": get_random_users(users_historico_curto),
        "user_historico_longo": get_random_users(users_historico_longo)
    }

    profile_indexes = {key: 0 for key in simulated_users}

    return {
        "status": "Simulated users prepared",
        "counts": {k: len(v) for k, v in simulated_users.items()}
    }

@app.get("/get-next-simulated-user")
def get_next_simulated_user(profile: str):
    if profile not in simulated_users:
        raise HTTPException(status_code=400, detail=f"Perfil inválido: {profile}")

    if len(simulated_users[profile]) == 0:
        raise HTTPException(status_code=404, detail=f"Nenhum usuário disponível para o perfil {profile}")

    if profile_indexes[profile] >= len(simulated_users[profile]):
        profile_indexes[profile] = 0  # Resetar ciclo

    user_id = simulated_users[profile][profile_indexes[profile]]
    profile_indexes[profile] += 1

    return {"userId": user_id, "profile": profile}


@app.get("/user-with-history")
def user_with_history(userId: str):
    # Buscar histórico do usuário (pode ser vazio)
    user_history = df_historico[df_historico["userid"] == userId].sort_values(by="timestamphistory", ascending=False)
    history_size = len(user_history)

    # Buscar a primeira notícia lida (validação)
    validation_news = df_validacao[df_validacao["userid"] == userId]
    validation_first_read = None
    last_read_cluster = None

    if not validation_news.empty:
        row = validation_news.iloc[0]
        validation_first_read = {
            "page": row["page"],
            "title": row["title"],
            "cluster": int(row["cluster"]) if pd.notnull(row["cluster"]) else None,
            "url": row.get("url", "#"),
            "issued": row.get("issued", "Desconhecido"),
            "timestampHistory": row.get("timestamphistory", "Desconhecido")
        }
        last_read_cluster = validation_first_read["cluster"]

    # Montar histórico (do mais recente para o mais antigo)
    history_list = [
        {
            "page": row["page"],
            "title": row["title"],
            "cluster": int(row["cluster"]) if pd.notnull(row["cluster"]) else None,
            "timestampHistory": row["timestamphistory"] if pd.notnull(row["timestamphistory"]) else None
        }
        for _, row in user_history.iterrows()
    ]

    # Buscar recomendações prontas
    user_recommendations = df_recomendacoes[df_recomendacoes["userid"] == userId]
    user_recommendations = user_recommendations.sort_values(by=["rn"])

    cluster_previsto = None  # Inicialmente sem cluster previsto

    if user_recommendations.empty:
        # Cold Start - Top 5 da Top 10
        recommendations_list = [
            {
                "page": row["page"],
                "title": row["title"],
                "cluster": int(row["cluster"]) if pd.notnull(row["cluster"]) else None
            }
            for _, row in df_top10.head(5).iterrows()
        ]
        message = "Cold Start - Mostrando Top 5 notícias."
    else:
        # Apenas repassa as recomendações já criadas (short ou long history)
        recommendations_list = [
            {
                "page": row["page"],
                "title": row["title"],
                "cluster": int(row["cluster"]) if pd.notnull(row["cluster"]) else None
            }
            for _, row in user_recommendations.iterrows()
        ]

        # Cluster previsto = cluster da primeira recomendação
        if len(recommendations_list) > 0:
            cluster_previsto = recommendations_list[0]["cluster"]

        message = "Mostrando recomendações personalizadas do usuário."

    return {
        "userId": userId,
        "historySize": history_size,
        "clusterPrevisto": cluster_previsto,
        "page": history_list,
        "recommendations": recommendations_list,
        "validation_first_read": validation_first_read,
        "message": message
    }
    
    if __name__ == "__main__":
        import uvicorn
        uvicorn.run(app, host="0.0.0.0", port=8000)
    