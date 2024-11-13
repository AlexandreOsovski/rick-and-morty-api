"use client";

import React, { useState, useEffect } from "react";
import axios from "axios";
import CharacterCard from "./components/CharacterCard/CharacterCard";
import useScrollToTop from "./hook/useScrollToTop";
import FilterBar from "./components/FilterBar/FilterBar";
import Header from "./components/Header/Header";
import { useFilters } from "./hook/useFilters";
import Character from "../app/interfaces/Character";

import RequestHttpService from "./services/RequestHttpService";

const CharacterList: React.FC = () => {
  const [characters, setCharacters] = useState<Character[]>([]);
  const [page, setPage] = useState<number>(1);
  const [totalPages, setTotalPages] = useState<number>(1);
  const [allStatuses, setAllStatuses] = useState<string[]>([]);
  const [allSpecies, setAllSpecies] = useState<string[]>([]);
  const [allGenders, setAllGenders] = useState<string[]>([]);

  const { filters, updateFilter } = useFilters({ setPage });

  useScrollToTop(page);

  useEffect(() => {
    RequestHttpService.get("characters")
      .then((res) => {
        if (res.status === "success") {
          setCharacters(res.data);
          setAllStatuses(res.data.status);
          setAllSpecies(res.data.species);
          setAllGenders(res.data.type);
        } else {
          console.error("Erro ao buscar opções de filtro:", res.status);
        }
      })
      .catch((error) => {
        console.error("Erro ao buscar opções de filtro:", error);
      });
  }, []);

  useEffect(() => {
    axios
      .get("http://localhost:8000/api/characters/filter", {
        params: {
          name: filters.search,
          status: filters.status,
          species: filters.species,
          gender: filters.gender,
          qtyPage: filters.qtyPage,
          page: page,
        },
      })
      .then((res) => {
        if (res.data && res.data.data) {
          setCharacters(res.data.data);
          setTotalPages(res.data.last_page);
        }
      })
      .catch((error) => {
        console.error("Erro ao buscar personagens:", error);
      });
  }, [filters, page]);

  const handlePrevPage = () => {
    if (page > 1) {
      setPage(page - 1);
    }
  };

  const handleNextPage = () => {
    if (page < totalPages) {
      setPage(page + 1);
    }
  };

  return (
    <div className="min-h-screen">
      <Header />
      <div className="container mx-auto p-4">
        <FilterBar
          search={filters.search}
          setSearch={(value) => updateFilter("search", value)}
          statusFilter={filters.status}
          setStatusFilter={(value) => updateFilter("status", value)}
          speciesFilter={filters.species}
          setSpeciesFilter={(value) => updateFilter("species", value)}
          genderFilter={filters.gender}
          setGenderFilter={(value) => updateFilter("gender", value)}
          qtyFilter={filters.qtyPage}
          setQtyFilter={(value) => updateFilter("qtyPage", value)}
          allStatuses={allStatuses}
          allSpecies={allSpecies}
          allGenders={allGenders}
        />

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          {Array.isArray(characters) && characters.length > 0 ? (
            characters.map((character) => (
              <CharacterCard
                id={character.id || character.id}
                key={character.id || character.id}
                name={character.name}
                image={character.image || "default-image.jpg"}
                status={character.status}
                species={character.species}
                gender={character.gender}
                location={character.location.name}
              />
            ))
          ) : (
            <div className="col-span-full text-center text-gray-400">
              Nenhum personagem encontrado.
            </div>
          )}
        </div>
        <div className="flex justify-between items-center mt-4">
          <button
            onClick={handlePrevPage}
            disabled={page === 1}
            className="px-4 py-2 text-white rounded-lg disabled:bg-gray-400"
          >
            Anterior
          </button>

          <span className="text-gray-500">
            Página {page} de {totalPages}
          </span>

          <button
            onClick={handleNextPage}
            disabled={page === totalPages}
            className="px-4 py-2 text-white rounded-lg disabled:bg-gray-400"
          >
            Próxima
          </button>
        </div>
      </div>
    </div>
  );
};

export default CharacterList;
