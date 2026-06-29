<template>
  <div class="min-h-screen bg-slate-100 py-8 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Actions Header (Hidden when printing) -->
      <div class="flex justify-between items-center mb-6 print:hidden">
        <NuxtLink to="/dashboard" class="flex items-center text-slate-600 hover:text-slate-900 font-medium bg-white px-4 py-2 rounded-lg shadow-sm">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          Back to Dashboard
        </NuxtLink>
        <button @click="printResume" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-colors flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
          Download PDF
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="flex justify-center items-center py-24 bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>

      <!-- Resume Document (A4 format roughly) -->
      <div v-else class="bg-white shadow-xl w-full mx-auto print:shadow-none print:m-0 print:w-full" style="min-height: 297mm; max-width: 210mm;">
        <div class="p-10 sm:p-14 text-slate-800">
          
          <!-- Header -->
          <div class="border-b-2 border-slate-800 pb-6 mb-8 flex items-end justify-between">
            <div>
              <h1 class="text-4xl font-bold tracking-tight text-slate-900 uppercase">{{ authStore.user?.name }}</h1>
              <h2 v-if="profile?.job_title" class="text-xl font-medium text-slate-600 mt-2">{{ profile.job_title }}</h2>
            </div>
            <div class="text-right text-sm space-y-1 text-slate-600 font-medium">
              <div class="flex items-center justify-end gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                {{ authStore.user?.email }}
              </div>
              <div v-if="profile?.mobile" class="flex items-center justify-end gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                {{ profile.mobile }}
              </div>
              <div v-if="profile?.address_current" class="flex items-center justify-end gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                {{ profile.address_current }}
              </div>
              <div v-if="profile?.social_links" class="flex items-center justify-end gap-2 mt-2">
                {{ profile.social_links }}
              </div>
            </div>
          </div>

          <!-- Professional Summary / Projects / Certs -->
          <div class="mb-8" v-if="profile?.projects || profile?.certifications">
            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-1 mb-4">Professional Overview</h3>
            <div v-if="profile?.projects" class="mb-4">
              <h4 class="font-bold text-slate-700">Key Projects</h4>
              <p class="text-slate-600 text-sm whitespace-pre-wrap mt-1 leading-relaxed">{{ profile.projects }}</p>
            </div>
            <div v-if="profile?.certifications">
              <h4 class="font-bold text-slate-700">Certifications</h4>
              <p class="text-slate-600 text-sm whitespace-pre-wrap mt-1 leading-relaxed">{{ profile.certifications }}</p>
            </div>
          </div>

          <!-- Work Experience -->
          <div class="mb-8" v-if="experiences && experiences.length > 0">
            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-1 mb-4">Work Experience</h3>
            <div class="space-y-6">
              <div v-for="exp in experiences" :key="exp.id" class="break-inside-avoid">
                <div class="flex justify-between items-baseline mb-1">
                  <h4 class="text-base font-bold text-slate-900">{{ exp.job_role }}</h4>
                  <span class="text-sm font-medium text-slate-600">{{ exp.year }}</span>
                </div>
                <div class="text-sm font-bold text-primary-700 mb-2">{{ exp.company }}</div>
              </div>
            </div>
          </div>

          <!-- Education -->
          <div class="mb-8" v-if="educations && educations.length > 0">
            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-1 mb-4">Education</h3>
            <div class="space-y-5">
              <div v-for="edu in educations" :key="edu.id" class="break-inside-avoid">
                <div class="flex justify-between items-baseline mb-1">
                  <h4 class="text-base font-bold text-slate-900">{{ edu.qualification }} <span v-if="edu.course_name">in {{ edu.course_name }}</span></h4>
                  <span class="text-sm font-medium text-slate-600">{{ edu.passing_year }}</span>
                </div>
                <div class="text-sm text-slate-700">{{ edu.university }} <span v-if="edu.board">- {{ edu.board }}</span></div>
                <div v-if="edu.percentage" class="text-sm text-slate-500 mt-1">Score: <span class="font-medium text-slate-700">{{ edu.percentage }}</span></div>
              </div>
            </div>
          </div>

          <!-- Skills & Languages -->
          <div class="grid grid-cols-2 gap-8 mb-8 break-inside-avoid">
            <div v-if="profile?.skills">
              <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-1 mb-4">Skills</h3>
              <div class="flex flex-wrap gap-2">
                <span v-for="skill in (profile.skills || '').split(',').map((s: string) => s.trim()).filter(Boolean)" :key="skill" class="bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold px-3 py-1 rounded-full print:border-slate-300">
                  {{ skill }}
                </span>
              </div>
            </div>
            
            <div v-if="profile?.languages">
              <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-1 mb-4">Languages</h3>
              <p class="text-slate-600 text-sm">{{ profile.languages }}</p>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from '#app'

const authStore = useAuthStore()
const router = useRouter()

const pending = ref(true)
const profile = ref<any>(null)
const educations = ref<any[]>([])
const experiences = ref<any[]>([])

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }
  
  try {
    const data: any = await $fetch('http://127.0.0.1:8000/api/applicant/profile', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    if (data.profile) {
      profile.value = data.profile
    }
    if (data.educations) {
      educations.value = data.educations
    }
    if (data.experiences) {
      experiences.value = data.experiences
    }
  } catch (e) {
    console.error('Failed to load profile for resume', e)
  } finally {
    pending.value = false
  }
})

const printResume = () => {
  window.print()
}

useSeoMeta({
  title: 'Resume Builder | SMECJobs',
})
</script>

<style>
@media print {
  body {
    background-color: white !important;
  }
  .print\:hidden {
    display: none !important;
  }
  .print\:shadow-none {
    box-shadow: none !important;
  }
  .print\:m-0 {
    margin: 0 !important;
  }
  .print\:w-full {
    width: 100% !important;
    max-width: none !important;
  }
  @page {
    margin: 0;
    size: A4 portrait;
  }
}
</style>
