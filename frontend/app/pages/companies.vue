<template>
  <div>
    <!-- Hero Section -->
    <div class="bg-slate-900 pt-32 pb-20 px-4 relative overflow-hidden">
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/40 to-slate-900/80 backdrop-blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Browse <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-blue-400">Companies</span></h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10">Discover top employers hiring on SMECJobs. Find the perfect company culture and career growth opportunities for you.</p>
        
        <div class="max-w-xl mx-auto relative">
          <input type="text" v-model="searchQuery" placeholder="Search companies by name or industry..." class="w-full bg-white/10 border border-white/20 text-white placeholder-slate-400 py-4 px-6 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500 backdrop-blur-md">
        </div>
      </div>
    </div>

    <!-- Companies Grid -->
    <div class="max-w-7xl mx-auto px-4 py-16">
      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
      
      <div v-else-if="filteredCompanies.length === 0" class="text-center py-20">
        <div class="mb-6 flex justify-center text-primary-500">
          <Icon name="lucide:building-2" class="w-20 h-20" />
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">No companies found</h3>
        <p class="text-slate-500">Try adjusting your search terms to find employers.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="company in filteredCompanies" :key="company.id" class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
          <div class="flex items-start gap-5 mb-6">
            <div class="w-24 h-16 bg-transparent flex items-center justify-start shrink-0">
              <img v-if="company.logo" :src="company.logo.startsWith('http') ? company.logo : `/storage/${company.logo}`" :alt="company.name" class="max-w-full max-h-full object-contain">
              <span v-else class="text-2xl font-bold text-slate-400">{{ company.name.charAt(0) }}</span>
            </div>
            <div>
              <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ company.name }}</h3>
              <p class="text-slate-500 text-sm mt-1 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ company.industry || 'Various Industries' }}
              </p>
            </div>
          </div>
          
          <p class="text-slate-600 text-sm mb-6 line-clamp-3">
            {{ company.description || 'Top employer actively hiring professionals on SMECJobs.' }}
          </p>
          
          <div class="flex items-center justify-between border-t border-slate-100 pt-5">
            <div class="flex items-center text-slate-500 text-sm gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              {{ company.location || 'Global' }}
            </div>
            <a v-if="company.website" :href="company.website" target="_blank" class="text-primary-600 hover:text-primary-700 font-semibold text-sm flex items-center gap-1">
              Visit Site
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useHead } from '#imports'

useHead({
  title: 'Browse Companies - SMECJobs'
})

const { data: companies, pending } = await useFetch('/api/companies')

const searchQuery = ref('')

const filteredCompanies = computed(() => {
  if (!companies.value) return []
  if (!searchQuery.value) return companies.value
  
  const query = searchQuery.value.toLowerCase()
  return (companies.value as any[]).filter(c => 
    c.name.toLowerCase().includes(query) || 
    (c.industry && c.industry.toLowerCase().includes(query))
  )
})
</script>
