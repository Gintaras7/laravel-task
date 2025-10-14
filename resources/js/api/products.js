import axios from '@/plugins/axios';

export const getProductsListRequest = (page = 1) => {
  return axios.get(`/api/v1/products?page=${page}`);
};

export const getProductRequest = (id) => {
  return axios.get(`/api/v1/products/${id}`);
};
