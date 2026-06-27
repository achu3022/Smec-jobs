<template>
  <NuxtLayout name="employer">
    <div class="max-w-4xl space-y-6">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Account Settings</h1>
        <p class="text-slate-500 mt-1">Manage your security preferences and notifications.</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-8">
          <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">Security & Password</h2>
          
          <form @submit.prevent="updatePassword" class="space-y-5 max-w-md">
            <div v-if="success" class="bg-emerald-50 text-emerald-700 p-3 rounded-lg text-sm border border-emerald-100 font-medium">
              Password updated successfully!
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Current Password</label>
              <input type="password" v-model="form.current_password" required class="block w-full rounded-xl border-slate-300 bg-slate-50 py-2.5 px-4 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
              <input type="password" v-model="form.new_password" required class="block w-full rounded-xl border-slate-300 bg-slate-50 py-2.5 px-4 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
              <input type="password" v-model="form.new_password_confirmation" required class="block w-full rounded-xl border-slate-300 bg-slate-50 py-2.5 px-4 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>

            <button type="submit" :disabled="loading" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-bold shadow-sm transition-colors disabled:opacity-70">
              {{ loading ? 'Updating...' : 'Update Password' }}
            </button>
          </form>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-6">
        <div class="p-8">
          <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">Notifications</h2>
          
          <div class="space-y-4">
            <label class="flex items-start gap-3 cursor-pointer group">
              <div class="relative flex items-center justify-center">
                <input type="checkbox" checked class="peer sr-only">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
              </div>
              <div>
                <span class="block text-sm font-bold text-slate-900">Email Alerts for New Applications</span>
                <span class="block text-sm text-slate-500">Receive an email instantly when someone applies to your job.</span>
              </div>
            </label>

            <label class="flex items-start gap-3 cursor-pointer group mt-4">
              <div class="relative flex items-center justify-center">
                <input type="checkbox" checked class="peer sr-only">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
              </div>
              <div>
                <span class="block text-sm font-bold text-slate-900">Weekly Summary Reports</span>
                <span class="block text-sm text-slate-500">Get a weekly email summarizing your job posting performance.</span>
              </div>
            </label>
          </div>
        </div>
      </div>

    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'

useSeoMeta({
  title: 'Settings | Employer Dashboard'
})

const loading = ref(false)
const success = ref(false)

const form = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const updatePassword = async () => {
  loading.value = true
  success.value = false
  // In a real app, this hits the backend /api/user/password endpoint
  setTimeout(() => {
    loading.value = false
    success.value = true
    form.current_password = ''
    form.new_password = ''
    form.new_password_confirmation = ''
  }, 800)
}
</script>
