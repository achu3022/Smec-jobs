<template>
  <div class="min-h-screen flex flex-col font-sans">
    <header v-if="!isDashboard" class="bg-white border-b border-slate-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <NuxtLink to="/" class="flex items-center">
            <img src="/logo-new.webp" alt="SMECJobs Logo" class="h-10 w-auto" />
          </NuxtLink>
        </div>
        <nav class="hidden md:flex items-center gap-8">
          <NuxtLink v-if="authStore.user?.role !== 'employer'" to="/" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Find a Job</NuxtLink>
          <NuxtLink to="/companies" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Companies</NuxtLink>
          <NuxtLink to="/courses" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Courses</NuxtLink>
          <NuxtLink to="/blog" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">Blog</NuxtLink>
        </nav>
        <div class="flex items-center gap-4">
          <template v-if="!authStore.isAuthenticated">
            <NuxtLink to="/login" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors hidden sm:block">Log in</NuxtLink>
            <NuxtLink to="/register" class="text-sm font-medium bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors shadow-sm shadow-primary-600/20">Sign up</NuxtLink>
          </template>
          <template v-else>
            <NuxtLink :to="dashboardLink" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">Dashboard</NuxtLink>
            <div class="text-sm font-medium text-slate-600 hidden sm:block">Hi, {{ authStore.user?.name }}</div>
            <button @click="logout" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Log out</button>
          </template>
        </div>
      </div>
    </header>

    <main class="flex-1">
      <slot />
    </main>

    <footer v-if="!isDashboard" class="bg-white text-slate-600 py-6 border-t border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <NuxtLink to="/" class="flex items-center">
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
