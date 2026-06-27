<template>
  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create an account</h2>
        <p class="text-sm text-slate-500 mt-2">Join SMECJobs to discover your next career move</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-5">
        <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">
          {{ error }}
        </div>

        <div>
          <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
          <div class="mt-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <input id="name" type="text" v-model="form.name" required class="pl-10 block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="John Doe">
          </div>
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

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
          <div class="mt-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <input id="password_confirmation" type="password" v-model="form.password_confirmation" required class="pl-10 block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="••••••••">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">I am a...</label>
          <div class="grid grid-cols-2 gap-4">
            <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 shadow-sm focus:outline-none"
                   :class="form.role === 'applicant' ? 'border-primary-600 ring-1 ring-primary-600' : 'border-slate-300'">
              <input type="radio" v-model="form.role" value="applicant" class="sr-only">
              <span class="flex flex-1">
                <span class="flex flex-col">
                  <span class="block text-sm font-medium text-slate-900">Job Seeker</span>
                  <span class="mt-1 flex items-center text-xs text-slate-500">I want to find a job</span>
                </span>
              </span>
              <svg v-if="form.role === 'applicant'" class="h-5 w-5 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
              </svg>
            </label>

            <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 shadow-sm focus:outline-none"
                   :class="form.role === 'employer' ? 'border-primary-600 ring-1 ring-primary-600' : 'border-slate-300'">
              <input type="radio" v-model="form.role" value="employer" class="sr-only">
              <span class="flex flex-1">
                <span class="flex flex-col">
                  <span class="block text-sm font-medium text-slate-900">Employer</span>
                  <span class="mt-1 flex items-center text-xs text-slate-500">I want to hire</span>
                </span>
              </span>
              <svg v-if="form.role === 'employer'" class="h-5 w-5 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
              </svg>
            </label>
          </div>
        </div>
        <button type="submit" :disabled="loading" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-600/30 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
          {{ loading ? 'Creating account...' : 'Create account' }}
        </button>
      </form>
      
      <p class="mt-8 text-center text-sm text-slate-600">
        Already have an account?
        <NuxtLink to="/login" class="font-medium text-primary-600 hover:text-primary-500">Sign in here</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from '#app'
import { useAuthStore } from '~/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const error = ref('')
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'applicant'
})

useSeoMeta({
  title: 'Sign Up - SMECJobs',
})

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response: any = await $fetch('http://127.0.0.1:8000/api/register', {
      method: 'POST',
      body: form
    })
    
    // Redirect to login with success message so they know to verify email
    router.push({ path: '/login', query: { registered: '1' } })
    
  } catch (err: any) {
    if (err.data?.errors) {
      error.value = Object.values(err.data.errors)[0][0]
    } else {
      error.value = 'An unexpected error occurred. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>
