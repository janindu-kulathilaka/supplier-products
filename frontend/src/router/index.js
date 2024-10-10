import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/HomePage.vue'
import Supplier from '../pages/SupplierPage.vue' // Rename to match your component
import Product from '../pages/ProductPage.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/supplier',
    name: 'Supplier',
    component: Supplier // Use the corrected component name
  },
  {
    path: '/product',
    name: 'Product',
    component: Product
  }
]

const router = createRouter({
  history: createWebHistory(), // Using HTML5 history mode
  routes
})

export default router
