<template>
  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome back</h2>
        <p class="text-sm text-slate-500 mt-2">Log in to your SMECJobs account</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div v-if="successMsg" class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 mb-6 flex items-center gap-3">
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          {{ successMsg }}
        </div>

        <div v-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100 flex flex-col gap-2">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ error }}
          </div>
          <button v-if="isUnverified" @click.prevent="resendVerification" type="button" class="text-sm font-bold text-red-700 hover:text-red-800 underline self-start mt-1">
            Resend Verification Email
          </button>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
          <div class="mt-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <input id="email" type="email" v-model="form.email" required class="pl-10 block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="you@example.com">
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
          <div class="mt-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <input id="password" type="password" v-model="form.password" required class="pl-10 block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="••••••••">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-slate-300 rounded text-slate-900">
            <label for="remember-me" class="ml-2 block text-sm text-slate-600">Remember me</label>
          </div>
          <div class="text-sm">
            <a href="#" class="font-medium text-primary-600 hover:text-primary-500">Forgot password?</a>
          </div>
        </div>

        <button type="submit" :disabled="loading" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-600/30 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
          {{ loading ? 'Signing in...' : 'Sign in' }}
        </button>
      </form>
      
      <p class="mt-8 text-center text-sm text-slate-600">
        Don't have an account?
        <NuxtLink to="/register" class="font-medium text-primary-600 hover:text-primary-500">Sign up here</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from '#app'
import { useAuthStore } from '~/stores/auth'
import { useRoute } from '#app'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const loading = ref(false)
const error = ref('')
const successMsg = ref('')
const isUnverified = ref(false)
const form = reactive({
  email: '',
  password: ''
})

useSeoMeta({
  title: 'Log in - SMECJobs',
})

if (route.query.verified === '1') {
  successMsg.value = 'Your email has been successfully verified! You can now sign in.'
} else if (route.query.registered === '1') {
  successMsg.value = 'Account created successfully! Please check your email inbox to verify your account before signing in.'
}

const resendVerification = async () => {
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/email/resend', {
      method: 'POST',
      body: { email: form.email }
    })
    successMsg.value = res.message || 'Verification link sent! Please check your inbox.'
    error.value = ''
    isUnverified.value = false
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to resend. Please try again.'
  }
}

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response: any = await $fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      body: form
    })
    
    authStore.setAuth(response.user, response.access_token)
    
    if (response.user.role === 'admin') {
      router.push('/admin/dashboard')
    } else if (response.user.role === 'employer') {
      router.push('/employer/dashboard')
    } else {
      router.push('/dashboard')
    }
  } catch (err: any) {
    isUnverified.value = false
    
    if (err.status === 403 && err.data?.message?.includes('verified')) {
      error.value = 'Your email address has not been verified yet.'
      isUnverified.value = true
    } else if (err.data?.message) {
      error.value = err.data.message
    } else if (err.data?.errors) {
      error.value = Object.values(err.data.errors)[0][0]
    } else {
      error.value = 'An unexpected error occurred. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>
