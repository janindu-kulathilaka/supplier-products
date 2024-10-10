<template>
  <header class="bg-black p-4 fixed w-full z-10">
    <div class="container mx-auto flex justify-between items-center">
      <router-link to="/"
        ><h1 class="text-white text-2xl font-bold">
          Supply <span class="text-[#FFAA33]">Manager</span>
        </h1></router-link
      >

      <nav class="space-x-4">
        <router-link to="/" class="text-gray-300 hover:text-[#FFAA33]">Home</router-link>
        <router-link to="/supplier" class="text-gray-300 hover:text-[#FFAA33]"
          >Supplier</router-link
        >
        <router-link to="/product" class="text-gray-300 hover:text-[#FFAA33]">Product</router-link>
      </nav>
    </div>
  </header>
</template>
<script>
export default {
  name: 'NavBar'
}
</script>
