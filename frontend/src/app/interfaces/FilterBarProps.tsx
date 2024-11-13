export default interface FilterBarProps {
  search: string;
  setSearch: (value: string) => void;
  statusFilter: string;
  setStatusFilter: (value: string) => void;
  speciesFilter: string;
  setSpeciesFilter: (value: string) => void;
  genderFilter: string;
  setGenderFilter: (value: string) => void;
  qtyFilter: string;
  setQtyFilter: (value: string) => void;
  allStatuses: string[];
  allSpecies: string[];
  allGenders: string[];
}
