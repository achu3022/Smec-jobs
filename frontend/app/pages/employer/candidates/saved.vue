<template>
  <NuxtLayout name="employer">
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Talent Pool</h1>
          <p class="text-slate-500 mt-1">Candidates you've saved for future opportunities.</p>
        </div>
      </div>

      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      </div>
      
      <div v-else-if="!candidates || candidates.length === 0" class="bg-white rounded-3xl shadow-sm border border-slate-200 p-16 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Your Talent Pool is empty</h2>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">When reviewing applications, click the heart icon to save promising candidates here.</p>
        <NuxtLink to="/employer/applicants" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-md shadow-indigo-200">
          Review Applications
        </NuxtLink>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="candidate in candidates" :key="candidate.id" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col transition-all hover:border-indigo-300 hover:shadow-md relative group">
          
          <button @click="removeCandidate(candidate.user_id)" class="absolute top-4 right-4 text-rose-500 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-opacity" title="Remove from pool">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
          </button>

          <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 rounded-2xl flex items-center justify-center font-bold text-2xl shadow-sm">
              {{ candidate.user?.name?.charAt(0) || 'U' }}
            </div>
            <div>
              <h3 class="text-lg font-bold text-slate-900">{{ candidate.user?.name }}</h3>
              <p class="text-sm text-slate-500 font-medium">{{ candidate.user?.applicantProfile?.job_title || 'Candidate' }}</p>
            </div>
          </div>
          
          <div class="space-y-3 mb-6 flex-1">
            <div class="flex items-center gap-2 text-sm text-slate-600" v-if="candidate.user?.applicantProfile?.location">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
              {{ candidate.user?.applicantProfile?.location }}
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-600" v-if="candidate.user?.applicantProfile?.experience_years">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
              {{ candidate.user?.applicantProfile?.experience_years }} Years Experience
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-600">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              Saved on {{ new Date(candidate.created_at).toLocaleDateString() }}
            </div>
          </div>
          
          <button @click="openApplicantModal(candidate)" class="w-full bg-slate-50 hover:bg-slate-100 text-indigo-600 font-bold py-2.5 rounded-xl transition-colors border border-slate-200">
            View Full Profile
          </button>
        </div>
      </div>
      
      <!-- Applicant Profile Modal -->
      <EmployerApplicantProfileModal 
        :is-open="isModalOpen"
        :applicant="selectedApplicant"
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
  title: 'Talent Pool | Employer Dashboard'
})

const authStore = useAuthStore()

const { data: candidates, pending, refresh } = await useFetch<any[]>('/api/employer/candidates/saved', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const isModalOpen = ref(false)
const selectedApplicant = ref<any>(null)

const openApplicantModal = (candidate: any) => {
  if (!candidate) return
  // Map candidate to applicant format expected by modal
  selectedApplicant.value = candidate
  isModalOpen.value = true
}

const removeCandidate = async (userId: number) => {
  if (!confirm('Remove this candidate from your Talent Pool?')) return
  try {
    await $fetch('/api/employer/candidates/saved', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { user_id: userId }
    })
    refresh()
  } catch (e) {
    alert('Failed to remove candidate.')
  }
}
</script>
