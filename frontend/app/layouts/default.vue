<template>
  <div class="min-h-screen flex flex-col font-sans">
    <header v-if="!isDashboard" class="bg-white border-b border-slate-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <NuxtLink to="/" class="flex items-center" aria-label="Home">
            <img src="/logo-new.webp" alt="SMECJobs Logo" class="h-10 w-auto" />
          </NuxtLink>
        </div>
        
        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-8">
          <NuxtLink v-if="authStore.user?.role !== 'employer'" to="/" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Find a Job</NuxtLink>
          <NuxtLink to="/companies" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Companies</NuxtLink>
          <NuxtLink to="/courses" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Courses</NuxtLink>
          <NuxtLink to="/blog" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Blog</NuxtLink>
        </nav>

        <div class="flex items-center gap-4">
          <div class="hidden md:flex items-center gap-4">
            <template v-if="!authStore.isAuthenticated">
              <NuxtLink to="/login" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Log in</NuxtLink>
              <NuxtLink to="/register" class="text-sm font-medium bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors shadow-sm shadow-primary-600/20">Sign up</NuxtLink>
            </template>
            <template v-else>
              <NuxtLink :to="dashboardLink" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">Dashboard</NuxtLink>
              <div class="text-sm font-medium text-slate-600">Hi, {{ authStore.user?.name }}</div>
              <button @click="logout" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Log out</button>
            </template>
          </div>
          
          <!-- Mobile menu button -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none">
            <span class="sr-only">Open menu</span>
            <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu Panel -->
      <div v-if="mobileMenuOpen" class="md:hidden border-t border-slate-200 bg-white">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <NuxtLink v-if="authStore.user?.role !== 'employer'" to="/" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Find a Job</NuxtLink>
          <NuxtLink to="/companies" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Companies</NuxtLink>
          <NuxtLink to="/courses" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Courses</NuxtLink>
          <NuxtLink to="/blog" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Blog</NuxtLink>
        </div>
        <div class="pt-4 pb-3 border-t border-slate-200">
          <div v-if="!authStore.isAuthenticated" class="px-5 space-y-3 flex flex-col">
            <NuxtLink to="/login" class="block w-full text-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-base font-medium text-slate-700 bg-white hover:bg-slate-50">Log in</NuxtLink>
            <NuxtLink to="/register" class="block w-full text-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-primary-600 hover:bg-primary-700">Sign up</NuxtLink>
          </div>
          <div v-else class="px-5">
            <div class="text-base font-medium text-slate-800 mb-2">Hi, {{ authStore.user?.name }}</div>
            <div class="mt-3 space-y-1">
              <NuxtLink :to="dashboardLink" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Dashboard</NuxtLink>
              <button @click="logout" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Log out</button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="flex-1">
      <slot />
    </main>

    <footer v-if="!isDashboard" class="bg-white text-slate-600 py-6 border-t border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <NuxtLink to="/" class="flex items-center" aria-label="Home">
          <img src="/logo-new.webp" alt="SMECJobs Logo" class="h-6 w-auto grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all" />
        </NuxtLink>
        <div class="flex flex-wrap justify-center gap-6 text-sm font-medium">
          <NuxtLink to="/about" class="hover:text-primary-600 transition-colors">About</NuxtLink>
          <NuxtLink to="/contact" class="hover:text-primary-600 transition-colors">Contact</NuxtLink>
          <NuxtLink to="/privacy" class="hover:text-primary-600 transition-colors">Privacy</NuxtLink>
          <NuxtLink to="/terms" class="hover:text-primary-600 transition-colors">Terms</NuxtLink>
        </div>
        <p class="text-xs text-slate-500">&copy; {{ new Date().getFullYear() }} SMECJobs. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter, useRoute } from '#app'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const mobileMenuOpen = ref(false)

// Close mobile menu on route change
watch(() => route.fullPath, () => {
  mobileMenuOpen.value = false
})

const isDashboard = computed(() => route.path.startsWith('/admin') || route.path.startsWith('/employer'))

const dashboardLink = computed(() => {
  if (authStore.user?.role === 'admin') return '/admin/dashboard'
  if (authStore.user?.role === 'employer') return '/employer/dashboard'
  return '/dashboard'
})

onMounted(() => {
  authStore.initAuth()
})

const logout = async () => {
  await authStore.logout()
  router.push('/')
}
</script>
