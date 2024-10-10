<template>
  <div>
    <h1 class="text-3xl font-bold mb-5 text-center text-white">Add Supplier Details</h1>
    <form
      @submit.prevent="editMode ? updateSupplier() : addSupplier()"
      class="bg-[#FFFFFF] py-5 px-8 rounded-lg text-black"
    >
      <div class="flex flex-col mb-2">
        <label class="text-sm">Supplier Name:</label>
        <input
          type="text"
          v-model="supplier.supplier_name"
          required
          class="text-black px-2 py-2 mt-1 rounded-md border-[1px] border-gray-500"
        />
      </div>
      <div class="flex flex-col mb-2">
        <label class="text-sm">Contact Person:</label>
        <input
          type="text"
          v-model="supplier.contact_person"
          required
          class="text-black px-2 py-2 mt-1 rounded-md border-[1px] border-gray-500"
        />
      </div>

      <div class="flex flex-col mb-2">
        <label class="text-sm">Mobile Number:</label>
        <input
          type="text"
          v-model="supplier.mobile_numbers"
          required
          class="text-black px-2 py-2 mt-1 rounded-md border-[1px] border-gray-500"
        />
      </div>

      <!-- Products Section -->
      <div class="flex flex-col mt-5 overflow-auto max-h-[400px]">
        <h3 class="text-md">Products</h3>
        <div
          v-for="(product, index) in supplier.products"
          :key="index"
          class="flex flex-col bg-[#e6e6e6] py-2 px-4 rounded-md mb-5"
        >
          <label class="text-sm mt-1">Product Name:</label>
          <input
            type="text"
            v-model="product.product_name"
            required
            class="text-black px-2 py-2 mb-1 rounded-md border-[1px] border-gray-500"
          />

          <label class="text-sm mt-1">Product Price:</label>
          <input
            type="number"
            v-model="product.product_price"
            required
            class="text-black px-2 py-2 mb-2 rounded-md border-[1px] border-gray-500"
          />

          <label class="text-sm mt-1">Product Image:</label>
          <input
            type="file"
            @change="onFileChange($event, index)"
            class="text-black px-2 py-2 mb-1 rounded-md border-[1px] border-gray-500"
          />

          <button
            type="button"
            @click="removeProduct(index)"
            class="text-sm text-red-500 flex items-center justify-end"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="size-4 mr-1"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
              />
            </svg>
            Remove
          </button>
        </div>
      </div>

      <button
        type="button"
        @click="addProduct"
        class="text-sm flex bg-green-300 py-1 px-2 rounded-md -mt-2"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="size-5 mr-1"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
          />
        </svg>
        Add Product
      </button>
      <button type="submit" class="mt-5 w-full bg-gray-700 text-white py-2 rounded-md">
        {{ editMode ? 'Update' : 'Submit' }}
      </button>
    </form>

    <div class="mt-10">
      <h2 class="text-2xl font-bold text-white">Supplier List</h2>
      <ul>
        <li
          v-for="(supplier, index) in suppliers"
          :key="index"
          class="flex justify-between bg-gray-200 p-2 rounded-md mb-2"
        >
          <span>{{ supplier.supplier_name }}</span>
          <div>
            <button
              @click="editSupplier(supplier)"
              class="bg-blue-500 text-white px-2 py-1 rounded-md mr-2"
            >
              Edit
            </button>
            <button
              @click="deleteSupplier(supplier.id)"
              class="bg-red-500 text-white px-2 py-1 rounded-md"
            >
              Delete
            </button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      suppliers: [], // Add suppliers array
      supplier: {
        supplier_name: '',
        contact_person: '',
        mobile_numbers: '',
        products: [{ product_name: '', product_price: '', image: '' }]
      },
      editMode: false // Track edit mode
    }
  },
  methods: {
    addProduct() {
      this.supplier.products.push({ product_name: '', product_price: '', image: '' })
    },
    removeProduct(index) {
      this.supplier.products.splice(index, 1)
    },
    onFileChange(event, index) {
      const file = event.target.files[0]
      this.supplier.products[index].image = file
    },
    async addSupplier() {
      const formData = new FormData()
      formData.append('supplier_name', this.supplier.supplier_name)
      formData.append('contact_person', this.supplier.contact_person)
      formData.append('mobile_numbers', this.supplier.mobile_numbers)

      this.supplier.products.forEach((product, index) => {
        formData.append(`products[${index}][product_name]`, product.product_name)
        formData.append(`products[${index}][product_price]`, product.product_price)
        if (product.image) {
          formData.append(`products[${index}][image]`, product.image)
        }
      })

      await axios.post('http://localhost:8000/api/store', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })

      this.clearForm()
      this.fetchSuppliers() // Refresh the supplier list
    },
    async updateSupplier() {
      const formData = new FormData()
      formData.append('supplier_name', this.supplier.supplier_name)
      formData.append('contact_person', this.supplier.contact_person)
      formData.append('mobile_numbers', this.supplier.mobile_numbers)

      this.supplier.products.forEach((product, index) => {
        formData.append(`products[${index}][product_name]`, product.product_name)
        formData.append(`products[${index}][product_price]`, product.product_price)
        if (product.image) {
          formData.append(`products[${index}][image]`, product.image)
        }
      })

      await axios.put(`http://localhost:8000/api/update/${this.supplier.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })

      this.clearForm()
      this.editMode = false // Reset edit mode
      this.fetchSuppliers() // Refresh the supplier list
    },
    async deleteSupplier(id) {
      await axios.delete(`http://localhost:8000/api/delete/${id}`)
      this.fetchSuppliers() // Refresh the supplier list after deletion
    },
    async fetchSuppliers() {
      const response = await axios.get('http://localhost:8000/api/suppliers')
      this.suppliers = response.data // Set the suppliers data
    },
    clearForm() {
      this.supplier = {
        supplier_name: '',
        contact_person: '',
        mobile_numbers: '',
        products: [{ product_name: '', product_price: '', image: '' }]
      }
    },
    editSupplier(supplier) {
      this.supplier = { ...supplier } // Copy the supplier data into the form
      this.editMode = true // Set edit mode
    }
  },
  created() {
    this.fetchSuppliers() // Fetch suppliers when the component is created
  }
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
