<template>
  <NuxtLayout name="employer">
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Interviews</h1>
          <p class="text-slate-500 mt-1">Manage and track all scheduled interviews with candidates.</p>
        </div>
      </div>

      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      </div>
      
      <div v-else-if="!interviews || interviews.length === 0" class="bg-white rounded-3xl shadow-sm border border-slate-200 p-16 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">No interviews scheduled</h2>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">When reviewing applications, you can schedule interviews and they will appear here.</p>
        <NuxtLink to="/employer/applicants" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-md shadow-indigo-200">
          Review Applications
        </NuxtLink>
      </div>

      <div v-else class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <ul class="divide-y divide-slate-100">
          <li v-for="interview in interviews" :key="interview.id" class="p-6 hover:bg-slate-50 transition-colors group flex flex-col md:flex-row md:items-center gap-6">
            
            <!-- Date/Time Box -->
            <div class="flex-shrink-0 w-24 h-24 bg-indigo-50 rounded-2xl flex flex-col items-center justify-center border border-indigo-100">
              <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">{{ new Date(interview.scheduled_at).toLocaleString('default', { month: 'short' }) }}</span>
              <span class="text-2xl font-black text-indigo-900">{{ new Date(interview.scheduled_at).getDate() }}</span>
              <span class="text-[10px] font-semibold text-slate-500 mt-1">{{ new Date(interview.scheduled_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}</span>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-3 mb-2">
                <h3 class="text-lg font-bold text-slate-900 truncate">{{ interview.application?.user?.name }}</h3>
                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ interview.status }}</span>
              </div>
              <p class="text-sm text-slate-600 mb-4 truncate font-medium">Applying for: <span class="text-slate-900">{{ interview.application?.job?.title }}</span></p>
              
              <div class="flex flex-wrap gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1.5" v-if="interview.interviewer_name">
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                  Interviewer: {{ interview.interviewer_name }}
                </span>
                <span class="flex items-center gap-1.5" v-if="interview.location_url">
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                  <a :href="interview.location_url" target="_blank" class="text-indigo-600 hover:underline">Meeting Link</a>
                </span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 shrink-0">
              <button @click="openApplicantModal(interview.application, true)" class="bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-bold py-2 px-4 rounded-xl transition-colors text-sm">
                Reschedule
              </button>
              <button @click="openApplicantModal(interview.application)" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2 px-4 rounded-xl transition-colors text-sm">
                View Profile
              </button>
            </div>
            
          </li>
        </ul>
      </div>
      
      <!-- Applicant Profile Modal -->
      <EmployerApplicantProfileModal 
        :is-open="isModalOpen"
        :applicant="selectedApplicant"
        :auto-open-interview="autoOpenInterview"
        @close="isModalOpen = false"
      />
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'
import EmployerApplicantProfileModal from '~/components/employer/ApplicantProfileModal.vue'

useSeoMeta({
  title: 'Interviews | Employer Dashboard'
})

const authStore = useAuthStore()

const { data: interviews, pending } = await useFetch<any[]>('http://127.0.0.1:8000/api/employer/interviews', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const isModalOpen = ref(false)
const selectedApplicant = ref<any>(null)
const autoOpenInterview = ref(false)

const openApplicantModal = (app: any, openInterview = false) => {
  if (!app) return
  selectedApplicant.value = app
  autoOpenInterview.value = openInterview
  isModalOpen.value = true
}
</script>
