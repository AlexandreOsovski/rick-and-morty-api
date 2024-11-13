import { useEffect } from 'react';

const useScrollToTop = (page: number) => {
  useEffect(() => {
    requestAnimationFrame(() => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }, [page]);
};

export default useScrollToTop;