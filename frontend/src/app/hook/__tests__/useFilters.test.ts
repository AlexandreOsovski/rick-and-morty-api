// src/app/hook/__tests__/useFilters.test.ts
import { renderHook, act } from '@testing-library/react';
import { useFilters } from '../useFilters';

describe('useFilters', () => {
  it('deve inicializar com os filtros vazios', () => {
    const setPage = jest.fn();
    const { result } = renderHook(() => useFilters({ setPage }));

    expect(result.current.filters).toEqual({
      search: '',
      status: '',
      species: '',
      gender: ''
    });
  });

  it('deve atualizar um filtro e resetar a página', async () => {
    const setPage = jest.fn();
    const { result } = renderHook(() => useFilters({ setPage }));

    await act(async () => {
      result.current.updateFilter('search', 'Rick');
    });

    expect(result.current.filters.search).toBe('Rick');
    expect(setPage).toHaveBeenCalledWith(1);
  });

  it('deve manter outros filtros inalterados ao atualizar um específico', () => {
    const setPage = jest.fn();
    const { result } = renderHook(() => useFilters({ setPage }));

    act(() => {
      result.current.updateFilter('status', 'alive');
    });
    act(() => {
      result.current.updateFilter('species', 'human');
    });

    expect(result.current.filters).toEqual({
      search: '',
      status: 'alive',
      species: 'human',
      gender: ''
    });
  });

  it('deve limpar filtros corretamente', () => {
    const setPage = jest.fn();
    const { result } = renderHook(() => useFilters({ setPage }));

    act(() => {
      result.current.updateFilter('status', 'alive');
      result.current.updateFilter('status', '');
    });

    expect(result.current.filters.status).toBe('');
    expect(setPage).toHaveBeenCalledTimes(2);
  });
});