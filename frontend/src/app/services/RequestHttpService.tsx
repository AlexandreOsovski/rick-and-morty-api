import axios from "axios";
import { environment } from "../environments/environment";

const api = axios.create({
  baseURL: environment.api,
  headers: {
    "Content-Type": "application/json",
  },
});

export const RequestHttpService = {
  /**
   * Função genérica para realizar requisições HTTP
   * @param method O método HTTP (GET, POST, PUT, DELETE)
   * @param endpoint O endpoint da API
   * @param data Dados a serem enviados (para POST/PUT)
   * @param params Parâmetros de consulta (para GET)
   * @returns Dados da resposta
   */
  request: async (
    method: string,
    endpoint: string,
    data?: null,
    params?: Record<string, unknown>
  ) => {
    try {
      const config = {
        method,
        url: endpoint,
        data: data,
        params: params,
      };

      const response = await api(config);
      return response.data;
    } catch (error) {
      console.error(`Erro na requisição ${method} para ${endpoint}:`, error);
      throw error;
    }
  },

  /**
   * Realiza uma requisição GET com parâmetros de consulta (query params)
   * @param endpoint O endpoint da API
   * @param params Parâmetros de consulta para incluir na URL
   * @returns Dados da resposta
   */
  get: async (endpoint: string, params?: Record<string, any> | null) => {
    return RequestHttpService.request("get", endpoint, null, params);
  },

  /**
   * Realiza uma requisição POST
   * @param endpoint O endpoint da API
   * @param data Os dados a serem enviados no corpo da requisição
   * @returns Dados da resposta
   */
  post: async (endpoint: string, data: null) => {
    return RequestHttpService.request("post", endpoint, data);
  },

  /**
   * Realiza uma requisição PUT
   * @param endpoint O endpoint da API
   * @param data Os dados a serem enviados no corpo da requisição
   * @returns Dados da resposta
   */
  put: async (endpoint: string, data: null) => {
    return RequestHttpService.request("put", endpoint, data);
  },

  /**
   * Realiza uma requisição DELETE
   * @param endpoint O endpoint da API
   * @param id O ID do recurso a ser deletado
   * @returns Dados da resposta
   */
  delete: async (endpoint: string, id: number) => {
    return RequestHttpService.request("delete", endpoint + "/" + id);
  },
};

export default RequestHttpService;
