<template>
  <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center transition-transform hover:-translate-y-1 hover:shadow-md relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-primary-100 to-transparent rounded-full opacity-0 group-hover:opacity-50 transition-opacity blur-xl"></div>
    <div class="p-4 rounded-2xl bg-primary-50 text-primary-600 mr-5 text-2xl flex-shrink-0">
      {{ icon }}
    </div>
    <div class="z-10">
      <p class="text-sm font-bold text-slate-500 mb-1">{{ title }}</p>
      <h3 class="text-3xl font-extrabold text-slate-900">{{ value }}</h3>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  value: string | number
  icon: string
}>()
</script>
