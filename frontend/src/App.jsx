import { useState, useEffect } from "react";
import axios from "axios";
import "./styles.css";

const profiles = [
    { key: "user_historico_longo", label: "Long History User" },
    { key: "user_historico_curto", label: "Short History User" },
    { key: "user_fake", label: "Cold Start User" }
];

const profileLabels = {
    "user_historico_longo": "Long History User",
    "user_historico_curto": "Short History User",
    "user_fake": "Cold Start User"
};

const recommendationMessages = {
    "user_historico_longo": "🔎 3 notícias com melhor score do cluster previsto + 2 top scores notícias",
    "user_historico_curto": "🔎 1 notícia com melhor score do último cluster lido + 4 top scores notícias",
    "user_fake": "🔎 5 top score notícias"
};

export default function App() {
    const [userData, setUserData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [selectedNews, setSelectedNews] = useState("https://g1.globo.com/");
    const [currentProfile, setCurrentProfile] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    const API_URL = "http://localhost:8000";

    useEffect(() => {
        const prepareAndLoadFirstUser = async () => {
            try {
                const res = await axios.get(`${API_URL}/prepare-simulated-users`);
                console.log("Usuários preparados", res.data.counts);

                if (res.data.counts["user_historico_longo"] > 0) {
                    loadNextUser("user_historico_longo");
                }
            } catch (err) {
                setErrorMessage("Erro ao preparar usuários simulados.");
            }
        };

        prepareAndLoadFirstUser();
    }, []);

    const fetchUserData = async (userId, profile) => {
        setLoading(true);
        setErrorMessage("");
        setUserData(null);
        setCurrentProfile(profile);

        try {
            const response = await axios.get(`${API_URL}/user-with-history`, { params: { userId } });
            setUserData(response.data);

            if (response.data.validation_first_read?.url) {
                setSelectedNews(response.data.validation_first_read.url);
            } else {
                setSelectedNews("https://g1.globo.com/");
            }
        } catch (error) {
            setErrorMessage(`Erro ao buscar dados para o usuário: ${userId}`);
        } finally {
            setLoading(false);
        }
    };

    const loadNextUser = async (profile) => {
        setLoading(true);
        setUserData(null);
        setCurrentProfile(profile);
        setErrorMessage("");

        try {
            const response = await axios.get(`${API_URL}/get-next-simulated-user`, { params: { profile } });
            fetchUserData(response.data.userId, profile);
        } catch (error) {
            if (error.response?.status === 404) {
                setErrorMessage(`Sem mais usuários para o perfil: ${profile}`);
            } else {
                setErrorMessage("Erro ao carregar próximo usuário.");
            }
            setLoading(false);
        }
    };

    return (
        <div className="container">
            <div className="header-area">
                <div className="header-buttons">
                    {profiles.map((profile) => (
                        <button key={profile.key} onClick={() => loadNextUser(profile.key)}>
                            {profile.label}
                        </button>
                    ))}
                </div>
                {loading && (
                    <div className="loading-indicator">
                        <span className="spinner"></span> Carregando...
                    </div>
                )}
            </div>

            <div className="main-content">
                {/* Coluna Esquerda - Dados do Usuário */}
                <div className="user-data-list">
                    <h2>Dados do Usuário</h2>
                    <p><strong>ID:</strong> {userData?.userId || "Nenhum usuário selecionado"}</p>
                    <p><strong>Perfil:</strong> {profileLabels[currentProfile] || "Nenhum perfil selecionado"}</p>
                    <p><strong>History Size:</strong> {userData?.historySize || 0}</p>
                    <p><strong>Cluster Previsto:</strong> {userData?.clusterPrevisto ?? "N/A"}</p>

                    <h3>Histórico</h3>
                    <div className="history-scroll">
                    {userData?.page?.length > 0 ? (
                        <ul>
                            {userData.page.map((item, index) => (
                                <li key={index}>
                                    <strong>Título:</strong> {item.title}<br />
                                    <strong>Cluster:</strong> {item.cluster}<br />
                                    <strong>Page:</strong> {item.page}<br />
                                    <strong>Data de Leitura:</strong> {item.timestampHistory || "Desconhecida"}
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p>Nenhum histórico disponível.</p>
                    )}
                </div>
                </div>

                {/* Coluna Direita - Última Notícia e Recomendações */}
                <div className="news-container">
                    <div className="news-top">
                        <div className="last-news-block">
                        <h3>Primeira Notícia Lida (Validação)</h3>
                            {userData?.validation_first_read ? (
                                <>
                                    <p><strong>Title:</strong> {userData.validation_first_read.title}</p>
                                    <p><strong>Page:</strong> {userData.validation_first_read.page}</p>
                                    <p><strong>Cluster:</strong> {userData.validation_first_read.cluster}</p>
                                    <p><strong>Data de Criação:</strong> {userData.validation_first_read.issued || "Desconhecida"}</p>
                                    <p><strong>Data de Leitura:</strong> {userData.validation_first_read.timestampHistory || "Desconhecida"}</p>
                                </>
                            ) : (
                                <p>Nenhuma notícia lida.</p>
                            )}
                        </div>

                        <iframe src={selectedNews} className="news-frame" title="Primeira Notícia"></iframe>
                    </div>

                    <h3 style={{ marginBottom: "4px" }}>Recomendações</h3>
                    <p className="recommendation-explanation">
                        {recommendationMessages[currentProfile] || "Nenhuma recomendação disponível para este perfil"}
                    </p>

                    <div className="recommendations-header">
                        <div>Notícia</div>
                        <div>Mesma Página</div>
                        <div>Mesmo Cluster</div>
                    </div>
                    <ul className="recommendations-list">
                        {Array.isArray(userData?.recommendations) && userData.recommendations.length > 0 ? (
                            userData.recommendations.map((rec, index) => {
                                const firstReadPage = String(userData?.validation_first_read?.page || "").trim();
                                const firstReadCluster = String(userData?.validation_first_read?.cluster || "").trim().toLowerCase();

                                const recPage = String(rec.page || "").trim();
                                const recCluster = String(rec.cluster || "").trim().toLowerCase();

                                const samePage = recPage === firstReadPage;
                                const sameCluster = recCluster === firstReadCluster;

                                return (
                                    <li key={index} className="recommendation-item">
                                        <div className="rec-text">
                                            <strong>Title:</strong> {rec.title}<br />
                                            <strong>Page:</strong> {rec.page}<br />
                                            <strong>Cluster:</strong> {rec.cluster}
                                        </div>
                                        <div className="checks">{samePage ? '✅' : '❌'}</div>
                                        <div className="checks">{sameCluster ? '✅' : '❌'}</div>
                                    </li>
                                );
                            })
                        ) : (
                            <p>Nenhuma recomendação disponível.</p>
                        )}
                    </ul>
                </div>
            </div>

            {errorMessage && (
                <p style={{ color: "red", fontWeight: "bold", marginTop: "10px" }}>
                    {errorMessage}
                </p>
            )}
        </div>
    );
}
