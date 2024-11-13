import React from "react";
import Link from "next/link";
import styles from "./CharacterCard.module.css";
import CharacterCardProps from "@/app/interfaces/CharacterCardProps";

const CharacterCard: React.FC<CharacterCardProps> = ({
  id,
  name,
  image,
  status,
  species,
  gender,
  location,
}) => {
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
    <Link href={`/characters/id=${id}`} className={styles.externalLink}>
      <article className={styles.cardWrapper}>
        <div className={styles.cardImageWrapper}>
          <img className={styles.cardImage} src={image} alt={name} />
        </div>
        <div className={styles.cardContentWrapper}>
          <div className={styles.section}>
            <h2>
              {name} - {genderTranslation[gender] || gender}
            </h2>

            <span
              className={
                status === "Alive"
                  ? styles.statusAlive
                  : status === "Dead"
                  ? styles.statusDead
                  : status === "unknown"
                  ? styles.statusUnknown
                  : ""
              }
            >
              {statusTranslation[status] || status}
            </span>
          </div>

          <div className={styles.section}>
            <span className={styles.textGray}>Espécie:</span>
            {species}
          </div>

          <div className={styles.section}>
            <span className={styles.textGray}>Localização:</span>
            {location}
          </div>
        </div>
      </article>
    </Link>
  );
};

export default CharacterCard;
