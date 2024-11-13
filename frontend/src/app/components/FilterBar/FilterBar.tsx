import React from "react";
import styles from "./FilterBar.module.css";
import FilterBarProps from "@/app/interfaces/FilterBarProps";

const FilterBar: React.FC<FilterBarProps> = ({
  search,
  setSearch,
  statusFilter,
  setStatusFilter,
  speciesFilter,
  setSpeciesFilter,
  genderFilter,
  setGenderFilter,
  qtyFilter,
  setQtyFilter,
  allStatuses,
  allSpecies,
  allGenders,
}) => {
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    console.log("Digitando no input:", e.target.value);
    e.preventDefault();
    setSearch(e.target.value);
  };

  return (
    <div className={styles.filterBarContainer}>
      <input
        type="text"
        placeholder="Pesquisar por nome"
        className={styles.input}
        value={search}
        onChange={handleInputChange}
        aria-label="Pesquisar por nome"
      />

      <select
        className={styles.select}
        value={statusFilter}
        onChange={(e) => {
          setStatusFilter(e.target.value);
        }}
        aria-label="Filtro de status"
      >
        <option value="">Todos os Status</option>
        <option value="Alive">Vivo</option>
        <option value="Dead">Morto</option>
        <option value="unknown">Desconhecido</option>
      </select>

      <select
        className={styles.select}
        value={speciesFilter}
        onChange={(e) => {
          setSpeciesFilter(e.target.value);
        }}
        aria-label="Filtro de espécies"
      >
        <option value="">Todas as Espécies</option>
        <option value="Human">Humano</option>
        <option value="Alien">Alien</option>
        <option value="Mythological Creature">Criatura Mitologica</option>
        <option value="Poopybutthole">Poopybutthole</option>
        <option value="Cronenberg">Cronenberg</option>
        <option value="Disease">Disease</option>
      </select>

      <select
        className={styles.select}
        value={genderFilter}
        onChange={(e) => {
          setGenderFilter(e.target.value);
        }}
        aria-label="Filtro de gênero"
      >
        <option value="">Todos os Gêneros</option>
        <option value="Female">Mulher</option>
        <option value="Male">Homen</option>
        <option value="unknown">Desconhecido</option>
      </select>

      <select
        className={styles.select}
        value={qtyFilter}
        onChange={(e) => {
          setQtyFilter(e.target.value);
        }}
        aria-label="Filtro de quantidade"
      >
        <option>Quantidade exibição por página</option>
        <option value="10">10</option>
        <option value="30">30</option>
        <option value="50">50</option>
        <option value="100">1000</option>
      </select>
    </div>
  );
};

export default FilterBar;
