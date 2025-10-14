import { createRouter, createWebHistory } from 'vue-router';
import ProductsList from '../views/ProductsList.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: ProductsList,
    },
    {
      path: '/products/:id',
      name: 'product-details',
      component: () => import('../views/ProductDetails.vue'),
    },
  ],
});

export default router;
