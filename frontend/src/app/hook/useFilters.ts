import { useState, Dispatch, SetStateAction, useCallback, useEffect } from 'react';
import UseFilter from '../interfaces/UseFilter';


interface UseFiltersProps {
  setPage: Dispatch<SetStateAction<number>>;
}

export const useFilters = ({ setPage }: UseFiltersProps) => {
  const [filters, setFilters] = useState<UseFilter>({
    search: '',
    status: '',
    species: '',
    gender: '',
    qtyPage: ''
  });

  const updateFilter = useCallback((key: keyof UseFilter, value: string) => {
    setFilters(prevFilters => {
      const newFilters = {
        ...prevFilters,
        [key]: value,
      };
      console.log(`Atualizando filtro ${key} para ${value}`);
      setPage(1);
      return newFilters;
    });
  }, [setPage]);

  useEffect(() => {
    console.log("Filtros atualizados:", filters);
  }, [filters]);

  return {
    filters,
    updateFilter
  };
};
