<template>
  <NuxtLayout name="employer">
    <div class="h-[calc(100vh-80px)] flex flex-col">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Applicant Tracking</h1>
          <p class="text-slate-500 mt-1">Manage candidates through your recruitment pipeline.</p>
        </div>
        
        <div class="flex gap-4">
          <select v-model="selectedJob" class="rounded-xl border-slate-200 text-sm font-medium focus:ring-indigo-500">
            <option value="">All Jobs</option>
            <option v-for="job in jobs" :key="job.id" :value="job.id">{{ job.title }}</option>
          </select>
        </div>
      </div>

      <!-- Simple List View -->
      <div class="flex-1 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-4">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-sm font-semibold text-slate-600">
                <th class="p-4">Applicant</th>
                <th class="p-4">Job Title</th>
                <th class="p-4">Location</th>
                <th class="p-4">Experience</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="app in filteredApplicants" :key="app.id" class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                <td class="p-4">
                  <div class="flex items-center gap-3 cursor-pointer" @click="openApplicantModal(app)">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm">
                      {{ app.user?.name?.charAt(0) || 'U' }}
                    </div>
                    <div>
                      <h4 class="font-bold text-slate-900 text-sm">{{ app.user?.name }}</h4>
                      <p class="text-xs text-slate-500">{{ app.user?.email }}</p>
                    </div>
                  </div>
                </td>
                <td class="p-4 text-sm font-medium text-slate-700">
                  {{ app.job?.title }}
                </td>
                <td class="p-4 text-sm text-slate-600">
                  {{ app.user?.applicantProfile?.location?.split(',')[0] || 'N/A' }}
                </td>
                <td class="p-4 text-sm text-slate-600">
                  {{ app.user?.applicantProfile?.experience_years ? app.user.applicantProfile.experience_years + ' Yrs' : 'N/A' }}
                </td>
                <td class="p-4">
                  <select :value="app.status || 'new'" @change="(e) => moveApplicant(app, (e.target as HTMLSelectElement).value)" class="text-sm font-medium rounded-lg border-slate-200 focus:ring-indigo-500 py-1.5 pl-3 pr-8" :class="getStatusColor(app.status || 'new')">
                    <option v-for="c in boardColumns" :key="c.id" :value="c.id">{{ c.title }}</option>
                  </select>
                </td>
                <td class="p-4 text-right">
                  <button @click="toggleSaveCandidate(app.user.id)" class="text-slate-400 hover:text-amber-500 transition-colors p-2" title="Save Candidate">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                  </button>
                  <button @click="openApplicantModal(app)" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm px-3 py-1.5 bg-indigo-50 rounded-lg ml-2 transition-colors">
                    View
                  </button>
                </td>
              </tr>
              <tr v-if="!filteredApplicants || filteredApplicants.length === 0">
                <td colspan="6" class="p-8 text-center text-slate-500 font-medium">
                  No applicants found for this job.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Applicant Profile Modal -->
      <EmployerApplicantProfileModal 
        :is-open="isModalOpen"
        :applicant="selectedApplicant"
        :auto-open-interview="autoOpenInterview"
        :target-interview-status="targetInterviewStatus"
        @close="closeApplicantModal"
        @saveCandidate="toggleSaveCandidate"
        @refresh="refresh"
      />
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import EmployerApplicantProfileModal from '~/components/employer/ApplicantProfileModal.vue'

useSeoMeta({
  title: 'Applicant Tracking | Employer Dashboard'
})

const authStore = useAuthStore()

// Board Columns Configuration
const boardColumns = [
  { id: 'new', title: 'New', colorClass: 'bg-blue-500' },
  { id: 'resume_reviewed', title: 'Reviewed', colorClass: 'bg-indigo-500' },
  { id: 'shortlisted', title: 'Shortlisted', colorClass: 'bg-emerald-500' },
  { id: 'hr_interview', title: 'HR Interview', colorClass: 'bg-purple-500' },
  { id: 'tech_interview', title: 'Tech Interview', colorClass: 'bg-amber-500' },
  { id: 'final_interview', title: 'Final Interview', colorClass: 'bg-orange-500' },
  { id: 'offer_sent', title: 'Offer Sent', colorClass: 'bg-teal-500' },
  { id: 'hired', title: 'Hired', colorClass: 'bg-green-600' },
  { id: 'rejected', title: 'Rejected', colorClass: 'bg-rose-500' },
  { id: 'hold', title: 'On Hold', colorClass: 'bg-slate-400' }
]

const { data: jobs } = await useFetch<any[]>('http://127.0.0.1:8000/api/employer/jobs', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const selectedJob = ref('')

const queryParams = computed(() => {
  const p = new URLSearchParams()
  if (selectedJob.value) p.append('job_id', selectedJob.value)
  return p.toString() ? `?${p.toString()}` : ''
})

const { data: applicants, refresh } = await useFetch<any[]>(() => `http://127.0.0.1:8000/api/employer/applications${queryParams.value}`, {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const filteredApplicants = computed(() => applicants.value || [])

const getStatusColor = (status: string) => {
  const col = boardColumns.find(c => c.id === status)
  if (!col) return 'bg-slate-50 text-slate-700'
  return `${col.colorClass.replace('bg-', 'text-').replace('-500', '-700')} bg-${col.colorClass.replace('bg-', '')}/10`
}

const isModalOpen = ref(false)
const selectedApplicant = ref<any>(null)
const autoOpenInterview = ref(false)
const targetInterviewStatus = ref('hr_interview')

const openApplicantModal = (app: any, openInterview = false, targetStatus = 'hr_interview') => {
  selectedApplicant.value = app
  autoOpenInterview.value = openInterview
  targetInterviewStatus.value = targetStatus
  isModalOpen.value = true
}

const closeApplicantModal = () => {
  isModalOpen.value = false
  // Refresh ensures dropdown visually reverts if user canceled the interview form
  refresh()
}

const moveApplicant = async (app: any, newStatus: string) => {
  const interviewStatuses = ['hr_interview', 'tech_interview', 'final_interview']
  
  if (interviewStatuses.includes(newStatus)) {
    // Prevent immediate change in UI dropdown visually if they cancel the modal
    // Actually, Vue might have already updated it because of v-model, but since it's an API call, we can just open the modal.
    // However, the v-model for the select is not strictly enforced here. The select has `@change`.
    openApplicantModal(app, true, newStatus)
    return
  }

  try {
    await $fetch(`http://127.0.0.1:8000/api/employer/applications/${app.id}/status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { status: newStatus }
    })
    refresh()
  } catch (e) {
    alert('Failed to update status')
  }
}

const toggleSaveCandidate = async (userId: number) => {
  try {
    await $fetch('http://127.0.0.1:8000/api/employer/candidates/saved', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { user_id: userId }
    })
    alert('Candidate Saved to Talent Pool!')
  } catch (e) {
    alert('Candidate is already saved or error occurred.')
  }
}

// already defined above

</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
</style>
