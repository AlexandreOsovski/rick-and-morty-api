"use client";

import React, { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";
import styles from "./page.module.css";
import CharacterId from "@/app/interfaces/CharacterId";
import { RequestHttpService } from "@/app/services/RequestHttpService";
import CharacterCardProps from "@/app/interfaces/CharacterCardProps";

const CharacterDetails: React.FC<CharacterCardProps> = () => {
  const params = useParams();
  const router = useRouter();
  const [id, setId] = useState<string | null>(null);
  const [character, setCharacter] = useState<CharacterId | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    const resolvedId = Array.isArray(params?.id)
      ? params.id[0]
      : params?.id || null;
    setId(resolvedId);
  }, [params]);

  useEffect(() => {
    if (id) {
      setIsLoading(true);
      RequestHttpService.get(`characters/id=${id}`)
        .then((response) => {
          setCharacter(response.data);
        })
        .catch((error) => {
          console.error("Erro ao buscar detalhes do personagem:", error);
        })
        .finally(() => {
          setIsLoading(false);
        });
    }
  }, [id]);

  const statusTranslation: Record<string, string> = {
    Alive: "Vivo",
    Dead: "Morto",
    unknown: "Desconhecido",
  };

  const genderTranslation: Record<string, string> = {
    Male: "Homem",
    Female: "Mulher",
    unknown: "Desconhecido",
  };

  return (
    <div className={styles.container}>
      <div className={styles.nav}>
        <button className={styles.button} onClick={() => router.back()}>
          Voltar
        </button>
      </div>
      <div className={styles.flex}>
        {isLoading ? (
          <div className={styles.loader}>Carregando...</div>
        ) : (
          <>
            <div className={styles.card}>
              <img
                src={character?.image}
                alt={character?.name}
                className={styles.image}
              />
            </div>
            <div className={styles.card}>
              <div className={styles.info}>
                <h1 className={styles.name}>{character?.name}</h1>
                <p
                  className={
                    character?.status === "Alive"
                      ? styles.statusAlive
                      : character?.status === "Dead"
                      ? styles.statusDead
                      : character?.status === "unknown"
                      ? styles.statusUnknown
                      : ""
                  }
                >
                  <strong>Status:</strong>{" "}
                  {statusTranslation[character?.status] || character?.status}
                </p>
                <p className={styles.paragraph}>
                  <strong>Espécie:</strong> {character?.species}
                </p>
                <p className={styles.paragraph}>
                  <strong>Tipo:</strong> {character?.type || "N/A"}
                </p>
                <p className={styles.paragraph}>
                  <strong>Gênero:</strong>{" "}
                  {genderTranslation[character?.gender] || character?.gender}
                </p>
                <p className={styles.paragraph}>
                  <strong>Origem:</strong>{" "}
                  {character?.origin ? character.origin.name : "Desconhecido"}
                </p>
                <p className={styles.paragraph}>
                  <strong>Última localização:</strong>{" "}
                  {character?.location
                    ? character.location.name
                    : "Desconhecido"}
                </p>
              </div>
            </div>
          </>
        )}
      </div>
    </div>
  );
};

export default CharacterDetails;
