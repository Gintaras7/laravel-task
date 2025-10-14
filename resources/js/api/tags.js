import axios from '@/plugins/axios';

export const getPopularTagsRequest = () => {
  return axios.get(`/api/v1/tags/popular`);
};
