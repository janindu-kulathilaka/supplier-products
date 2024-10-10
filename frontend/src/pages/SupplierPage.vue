<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import NavBar from '@/components/NavBar.vue'
import SupplierForm from '@/components/SupplierForm.vue'

// Reactive variables
const suppliers = ref([]) // Stores supplier data
const currentPage = ref(1) // Current page number
const totalPages = ref(0) // Total number of pages
const searchQuery = ref('') // Search query input
const pageSize = ref(10) // Number of suppliers per page
const loading = ref(false) // Loading state for API requests

const showForm = ref(false) // Show/Hide form
const selectedSupplier = ref(null) // Store the supplier to be edited

// Method to toggle the form visibility
const toggleForm = async (supplier = null) => {
  if (supplier) {
    try {
      const response = await axios.get(`http://localhost:8000/api/suppliers/${supplier.id}`)
      selectedSupplier.value = response.data
    } catch (error) {
      console.error('Error fetching supplier data:', error)
    }
  } else {
    selectedSupplier.value = null // Reset for adding a new supplier
  }
  showForm.value = !showForm.value
}

// Fetch suppliers from the API
const fetchSuppliers = async () => {
  try {
    loading.value = true
    const response = await axios.get('http://localhost:8000/api/getSupplierDetails', {
      params: {
        page: currentPage.value,
        search: searchQuery.value,
        pageSize: pageSize.value
      }
    })
    // Update suppliers and pagination info
    suppliers.value = response.data.data
    totalPages.value = response.data.last_page
    currentPage.value = response.data.current_page
  } catch (error) {
    console.error('Error fetching supplier details:', error)
  } finally {
    loading.value = false
  }
}

// Method to delete a supplier
const deleteSupplier = async (supplierId) => {
  try {
    await axios.delete(`http://localhost:8000/api/delete/${supplierId}`)
    // Refresh the supplier list after deletion
    fetchSuppliers()
  } catch (error) {
    console.error('Error deleting supplier:', error)
  }
}

// Fetch suppliers on initial load and whenever searchQuery or currentPage changes
onMounted(fetchSuppliers)
watch([searchQuery, currentPage], fetchSuppliers)
</script>

<template>
  <NavBar />
  <div class="bg-primary min-h-screen overflow-auto">
    <div class="bg-[#00000095] min-h-screen w-full overflow-auto pb-10">
      <div v-if="!showForm" class="mt-20 flex flex-col justify-center items-center mx-auto">
        <button
          @click="toggleForm()"
          class="text-green-500 text-md max-w-[180px] bg-[#ffffff] px-4 py-2 flex items-center rounded-md"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-6 mr-2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
            />
          </svg>

          Add Supplier
        </button>
      </div>

      <div v-if="showForm" class="flex flex-col h-screen justify-center items-center">
        <SupplierForm :supplier="selectedSupplier" />
        <button @click="toggleForm()" class="text-white mt-4 flex items-center underline">
          Close
        </button>
      </div>

      <div class="bg-white shadow-md rounded-lg p-6 mt-5 max-w-4xl mx-auto">
        <!-- Search Bar -->
        <div class="mb-4">
          <input
            v-model="searchQuery"
            @input="currentPage = 1"
            type="text"
            placeholder="Search by name or mobile number"
            class="w-full p-2 border border-gray-300 rounded-md"
          />
        </div>

        <!-- Supplier Cards -->
        <div v-if="loading" class="text-center">Loading suppliers...</div>
        <div v-else-if="suppliers.length === 0" class="text-center">No suppliers found.</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="supplier in suppliers"
            :key="supplier.id"
            class="bg-gray-100 p-4 rounded-lg shadow-lg"
          >
            <h3 class="text-xl font-bold">{{ supplier.supplier_name }}</h3>
            <p><strong>Contact Person:</strong> {{ supplier.contact_person }}</p>
            <p><strong>Phone:</strong> {{ supplier.mobile_numbers }}</p>

            <!-- Edit and Delete Buttons -->
            <div class="flex justify-between mt-4">
              <button @click="toggleForm(supplier)" class="text-blue-500 underline">Edit</button>
              <button @click="deleteSupplier(supplier.id)" class="text-red-500 underline">
                Delete
              </button>
            </div>
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
