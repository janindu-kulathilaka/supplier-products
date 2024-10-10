<script setup lang="ts">
import NavBar from '@/components/NavBar.vue'
</script>

<template>
  <NavBar />
  <div class="relative w-full h-screen">
    <div class="absolute bg-primary">
      <div
        class="absolute bg-[#00000095] flex flex-col items-center justify-center w-full h-screen z-100"
      >
        <h1 class="text-5xl font-bold text-white">
          Connecting <span class="text-[#FFAA33]">Resources</span>, Delivering
          <span class="text-[#FFAA33]">Excellence</span>
        </h1>
        <h3 class="mt-2 text-white text-2xl">Transforming Operations for Greater Productivity.</h3>
        <div class="flex suppliers mt-5 gap-5">
          <router-link to="/supplier" class="text-white bg-[#FFAA33] py-3 px-6 rounded-lg">
            Suppliers
          </router-link>
          <router-link to="/product" class="text-[#FFAA33] bg-white py-3 px-6 rounded-lg">
            Products
          </router-link>
        </div>
      </div>
    </div>

    <!-- Content -->
  </div>
</template>
