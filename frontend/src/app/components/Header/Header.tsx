import React from "react";
import styles from "./Header.module.css";

const Header: React.FC = () => (
  <header className={styles.headerContainer}>
    <div className={styles.textCenter}>
      <h1 className={styles.title}>The Rick and Morty API</h1>
      <p className={styles.subtitle}>me contrata na moralzinha</p>
    </div>
  </header>
);

export default Header;
