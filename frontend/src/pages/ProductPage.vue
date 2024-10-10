<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import NavBar from '@/components/NavBar.vue'

const products = ref([]) // Stores product data
const currentPage = ref(1) // Current page number
const totalPages = ref(0) // Total number of pages
const searchQuery = ref('') // Search query input
const pageSize = ref(10) // Number of products per page
const loading = ref(false) // Loading state for API requests

const fetchProducts = async () => {
  try {
    loading.value = true

    const response = await axios.get('http://localhost:8000/api/getProductDetails', {
      params: {
        page: currentPage.value,
        search: searchQuery.value,
        pageSize: pageSize.value
      }
    })

    // Update products and pagination info
    products.value = response.data.data
    totalPages.value = response.data.last_page
    currentPage.value = response.data.current_page
  } catch (error) {
    console.error('Error fetching product details:', error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchProducts)
watch([searchQuery, currentPage], fetchProducts)
</script>

<template>
  <NavBar />
  <div class="bg-primary min-h-screen overflow-auto">
    <div
      class="bg-[#00000095] min-h-screen w-full flex items-center justify-center overflow-auto pb-10"
    >
      <div class="bg-white shadow-md rounded-lg p-6 mt-5 max-w-4xl mx-auto">
        <!-- Search Bar -->
        <div class="mb-4">
          <input
            v-model="searchQuery"
            @input="currentPage = 1"
            type="text"
            placeholder="Search products by name"
            class="w-full p-2 border border-gray-300 rounded-md"
          />
        </div>

        <!-- Product Cards -->
        <div v-if="loading" class="text-center">Loading products...</div>
        <div v-else-if="products.length === 0" class="text-center">No Products found.</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="product in products"
            :key="product.id"
            class="bg-gray-100 p-4 rounded-lg shadow-lg"
          >
            <h3 class="text-xl font-bold">{{ product.product_name }}</h3>
            <p><strong>Price:</strong> {{ product.product_price }}</p>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div class="mt-6 flex justify-center items-center gap-4">
          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg"
          >
            Previous
          </button>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <button
            @click="currentPage++"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
