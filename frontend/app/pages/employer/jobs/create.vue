<template>
  <NuxtLayout name="employer">
    <div class="max-w-4xl mx-auto py-8">
      <div class="mb-8">
        <NuxtLink to="/employer/jobs" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mb-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          Back to Jobs
        </NuxtLink>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Post a New Job</h1>
        <p class="text-slate-500 mt-1">Create a new job listing to attract top talent.</p>
      </div>

      <form @submit.prevent="submitJob" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-8 space-y-8">
          
          <div v-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100">
            {{ error }}
          </div>

          <!-- Basic Info -->
          <div class="space-y-6">
            <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-2">Basic Information</h2>
            
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Job Title *</label>
              <input type="text" v-model="form.title" required class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="e.g. Senior Frontend Developer">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Job Category *</label>
                <select v-model="form.job_category_id" required class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900">
                  <option value="" disabled>Select a category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Job Type *</label>
                <select v-model="form.job_type" required class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900">
                  <option value="Full-time">Full-time</option>
                  <option value="Part-time">Part-time</option>
                  <option value="Contract">Contract</option>
                  <option value="Freelance">Freelance</option>
                  <option value="Internship">Internship</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Location *</label>
              <input type="text" v-model="form.location" required class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="e.g. Kochi, Kerala (or Remote)">
            </div>
          </div>

          <!-- Description -->
          <div class="space-y-6">
            <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-2">Job Description</h2>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Full Description *</label>
              <textarea v-model="form.description" required rows="8" class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="Describe the responsibilities, requirements, and benefits..."></textarea>
            </div>
          </div>

          <!-- Requirements & Comp -->
          <div class="space-y-6">
            <h2 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-2">Requirements & Compensation</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Experience Level</label>
                <select v-model="form.experience_level" class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900">
                  <option value="">Any</option>
                  <option value="Entry Level">Entry Level (0-2 years)</option>
                  <option value="Mid Level">Mid Level (3-5 years)</option>
                  <option value="Senior Level">Senior Level (5+ years)</option>
                  <option value="Executive">Executive</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Education Level</label>
                <select v-model="form.education_level" class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900">
                  <option value="">Any</option>
                  <option value="High School">High School</option>
                  <option value="Bachelor's Degree">Bachelor's Degree</option>
                  <option value="Master's Degree">Master's Degree</option>
                  <option value="PhD">PhD</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Minimum Salary (Optional)</label>
                <input type="number" v-model="form.salary_min" class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="e.g. 30000">
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Maximum Salary (Optional)</label>
                <input type="number" v-model="form.salary_max" class="block w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-colors text-slate-900" placeholder="e.g. 60000">
              </div>
            </div>
          </div>
        </div>

        <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 flex justify-end gap-4">
          <NuxtLink to="/employer/jobs" class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-200 transition-colors">
            Cancel
          </NuxtLink>
          <button type="submit" :disabled="loading" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-sm shadow-indigo-600/30 transition-colors disabled:opacity-70">
            {{ loading ? 'Posting...' : 'Post Job' }}
          </button>
        </div>
      </form>
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from '#app'
import { useAuthStore } from '~/stores/auth'

useSeoMeta({
  title: 'Post a New Job | Employer Dashboard'
})

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const error = ref('')

const form = reactive({
  title: '',
  description: '',
  location: '',
  job_type: 'Full-time',
  job_category_id: '',
  salary_min: '',
  salary_max: '',
  experience_level: '',
  education_level: ''
})

const { data: categories } = await useFetch<any[]>('/api/categories')

const submitJob = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await $fetch('/api/employer/jobs', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${authStore.token}`
      },
      body: form
    })
    
    router.push('/employer/jobs')
  } catch (err: any) {
    if (err.data?.errors) {
      error.value = Object.values(err.data.errors)[0][0]
    } else {
      error.value = err.data?.message || 'Failed to post job. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>
