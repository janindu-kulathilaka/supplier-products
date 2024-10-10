<template>
  <div>
    <h1 class="text-3xl font-bold mb-5 text-center text-white">
      {{ editMode ? 'Edit Supplier Details' : 'Add Supplier Details' }}
    </h1>
    <form @submit.prevent="submitForm" class="bg-[#FFFFFF] py-5 px-8 rounded-lg text-black">
      <div class="flex flex-col mb-2">
        <label class="text-sm">Supplier Name:</label>
        <input
          type="text"
          v-model="formSupplier.supplier_name"
          required
          class="text-black px-2 py-2 mt-1 rounded-md border-[1px] border-gray-500"
        />
      </div>
      <div class="flex flex-col mb-2">
        <label class="text-sm">Contact Person:</label>
        <input
          type="text"
          v-model="formSupplier.contact_person"
          required
          class="text-black px-2 py-2 mt-1 rounded-md border-[1px] border-gray-500"
        />
      </div>
      <div class="flex flex-col mb-2">
        <label class="text-sm">Mobile Number:</label>
        <input
          type="text"
          v-model="formSupplier.mobile_numbers"
          required
          class="text-black px-2 py-2 mt-1 rounded-md border-[1px] border-gray-500"
        />
      </div>

      <div class="flex flex-col mt-5 overflow-auto max-h-[400px]">
        <h3 class="text-md">Products</h3>
        <div
          v-for="(product, index) in formSupplier.products"
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
            class="text-black px-2 py-2 rounded-md border-[1px] border-gray-500"
          />
          <button
            @click="removeProduct(index)"
            v-if="formSupplier.products.length > 1"
            class="text-red-500 underline mt-2"
          >
            Remove Product
          </button>
        </div>
        <button @click="addProduct" class="text-green-500 underline">Add Another Product</button>
      </div>

      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md mt-4">
        {{ editMode ? 'Update Supplier' : 'Add Supplier' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  props: {
    supplier: {
      type: Object,
      default: () => ({
        supplier_name: '',
        contact_person: '',
        mobile_numbers: '',
        products: [{ product_name: '', product_price: '', image: '' }]
      })
    }
  },
  data() {
    return {
      formSupplier: {
        supplier_name: '',
        contact_person: '',
        mobile_numbers: '',
        products: [{ product_name: '', product_price: '', image: '' }]
      },
      editMode: false
    }
  },
  watch: {
    supplier: {
      handler(newSupplier) {
        if (newSupplier) {
          this.formSupplier = { ...newSupplier }
          this.editMode = !!newSupplier.id
        }
      },
      immediate: true
    }
  },
  methods: {
    addProduct() {
      this.formSupplier.products.push({ product_name: '', product_price: '', image: '' })
    },
    removeProduct(index) {
      this.formSupplier.products.splice(index, 1)
    },
    onFileChange(event, index) {
      const file = event.target.files[0]
      this.formSupplier.products[index].image = file
    },
    async submitForm() {
      if (this.editMode) {
        await this.updateSupplier()
      } else {
        await this.addSupplier()
      }
    },
    async addSupplier() {
      try {
        const formData = new FormData()
        formData.append('supplier_name', this.formSupplier.supplier_name)
        formData.append('contact_person', this.formSupplier.contact_person)
        formData.append('mobile_numbers', this.formSupplier.mobile_numbers)

        this.formSupplier.products.forEach((product, index) => {
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

        this.$emit('formSubmitted') // You can emit this event to inform the parent component to refresh the supplier list
        this.clearForm() // Reset the form after successful submission
      } catch (error) {
        console.error('Error adding supplier:', error)
      }
    },
    async updateSupplier() {
      try {
        const formData = new FormData()
        formData.append('supplier_name', this.formSupplier.supplier_name)
        formData.append('contact_person', this.formSupplier.contact_person)
        formData.append('mobile_numbers', this.formSupplier.mobile_numbers)

        this.formSupplier.products.forEach((product, index) => {
          formData.append(`products[${index}][product_name]`, product.product_name)
          formData.append(`products[${index}][product_price]`, product.product_price)
          if (product.image) {
            formData.append(`products[${index}][image]`, product.image)
          }
        })

        await axios.put(`http://localhost:8000/api/update/${this.formSupplier.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        this.$emit('formSubmitted') // Emit the event to inform the parent component
        this.clearForm() // Reset the form
      } catch (error) {
        console.error('Error updating supplier:', error)
      }
    },
    clearForm() {
      this.formSupplier = {
        supplier_name: '',
        contact_person: '',
        mobile_numbers: '',
        products: [{ product_name: '', product_price: '', image: '' }]
      }
      this.editMode = false // Reset edit mode
    }
  }
}
</script>
