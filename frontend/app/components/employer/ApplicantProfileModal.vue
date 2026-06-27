<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
      <!-- Background overlay -->
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="closeModal" aria-hidden="true"></div>

      <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
        <!-- Slide-over panel -->
        <div class="pointer-events-auto w-screen max-w-2xl transform transition-all">
          <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl">
            <!-- Header -->
            <div class="px-6 py-6 sm:px-8 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-sm z-10">
              <h2 class="text-xl font-bold text-slate-900" id="slide-over-title">Applicant Profile</h2>
              <button @click="closeModal" type="button" class="rounded-md bg-white text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="sr-only">Close panel</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Content -->
            <div class="flex-1 px-6 py-6 sm:px-8" v-if="applicant">
              
              <!-- Candidate Identity -->
              <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-bold text-3xl shadow-sm border border-indigo-100">
                  {{ applicant.user?.name?.charAt(0) || 'U' }}
                </div>
                <div>
                  <h1 class="text-2xl font-bold text-slate-900">{{ applicant.user?.name }}</h1>
                  <p class="text-slate-500 flex items-center gap-2 mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ applicant.user?.email }}
                  </p>
                  <p class="text-slate-500 flex items-center gap-2 mt-1" v-if="applicant.user?.applicantProfile?.phone">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    {{ applicant.user?.applicantProfile?.phone }}
                  </p>
                </div>
              </div>

              <!-- Application Info -->
              <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Application Details</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Applied Job</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-900">{{ applicant.job?.title }}</dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Status</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-900 capitalize">{{ (applicant.status || 'new').replace('_', ' ') }}</dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Application Date</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-900">{{ new Date(applicant.created_at).toLocaleDateString() }}</dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500">Location</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-900">{{ applicant.user?.applicantProfile?.location || 'Not provided' }}</dd>
                  </div>
                </dl>
              </div>

              <!-- Professional Profile -->
              <div class="space-y-6 mb-8">
                <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2">Professional Profile</h3>
                
                <div>
                  <h4 class="text-sm font-medium text-slate-500 mb-2">Experience</h4>
                  <p class="text-sm text-slate-900 font-medium bg-slate-50 p-3 rounded-lg border border-slate-100">
                    {{ applicant.user?.applicantProfile?.experience_years ? `${applicant.user.applicantProfile.experience_years} Years` : 'Not provided' }}
                  </p>
                </div>

                <div>
                  <h4 class="text-sm font-medium text-slate-500 mb-2">About Candidate</h4>
                  <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 min-h-[100px]">
                    {{ applicant.user?.applicantProfile?.about || 'No description provided by the candidate.' }}
                  </p>
                </div>
              </div>

              <!-- Interview Scheduling Form -->
              <div v-if="showInterviewForm" class="bg-indigo-50/50 rounded-2xl p-6 mb-8 border border-indigo-100">
                <h3 class="font-bold text-indigo-900 mb-4 border-b border-indigo-200 pb-2 flex justify-between items-center">
                  Schedule Interview
                  <button @click="showInterviewForm = false" class="text-xs text-indigo-600 hover:text-indigo-800">Cancel</button>
                </h3>
                <form @submit.prevent="scheduleInterview" class="space-y-4">
                  <div v-if="interviewError" class="bg-red-50 text-red-600 p-3 rounded-lg text-xs border border-red-100">
                    {{ interviewError }}
                  </div>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Date & Time *</label>
                      <input type="datetime-local" v-model="interviewForm.scheduled_at" required class="block w-full rounded-xl border-slate-300 bg-white border py-2 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-900">
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Interview Type *</label>
                      <select v-model="interviewForm.type" required class="block w-full rounded-xl border-slate-300 bg-white border py-2 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-900">
                        <option value="virtual">Virtual</option>
                        <option value="in_person">In-Person</option>
                        <option value="phone">Phone</option>
                      </select>
                    </div>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                      {{ interviewForm.type === 'virtual' ? 'Meeting Link (Zoom, Google Meet)' : interviewForm.type === 'in_person' ? 'Location Address (Google Maps)' : 'Phone Number' }}
                    </label>
                    <input type="text" v-model="interviewForm.location" class="block w-full rounded-xl border-slate-300 bg-white border py-2 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-900" 
                           :placeholder="interviewForm.type === 'virtual' ? 'https://zoom.us/j/...' : interviewForm.type === 'in_person' ? '123 Main St, City, State' : '+1 234 567 8900'">
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Notes to Candidate</label>
                    <textarea v-model="interviewForm.notes" rows="2" class="block w-full rounded-xl border-slate-300 bg-white border py-2 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-900" placeholder="E.g. Be prepared for a technical round..."></textarea>
                  </div>
                  <div class="flex justify-end pt-2">
                    <button type="submit" :disabled="scheduling" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-sm shadow-indigo-600/30 transition-colors disabled:opacity-70">
                      {{ scheduling ? 'Scheduling...' : 'Confirm Schedule' }}
                    </button>
                  </div>
                </form>
              </div>

            </div>

            <!-- Sticky Bottom Actions -->
            <div class="border-t border-slate-200 px-6 py-4 sm:px-8 bg-slate-50 flex items-center justify-between sticky bottom-0 z-10">
              <button @click="closeModal" class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                Close
              </button>
              
              <div class="flex gap-3" v-if="!showInterviewForm">
                <button @click="emit('saveCandidate', applicant?.user?.id)" class="px-4 py-2 bg-white border border-slate-300 hover:border-amber-400 text-slate-700 hover:text-amber-500 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                  Save Candidate
                </button>
                <button @click="showInterviewForm = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-sm shadow-indigo-600/30 transition-colors">
                  Schedule Interview
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { useAuthStore } from '~/stores/auth'

const props = defineProps({
  isOpen: Boolean,
  applicant: Object,
  autoOpenInterview: { type: Boolean, default: false },
  targetInterviewStatus: { type: String, default: 'hr_interview' }
})

const emit = defineEmits(['close', 'saveCandidate', 'refresh'])
const authStore = useAuthStore()

const showInterviewForm = ref(false)
const scheduling = ref(false)
const interviewError = ref('')

const interviewForm = reactive({
  scheduled_at: '',
  type: 'virtual',
  location: '',
  notes: ''
})

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    showInterviewForm.value = props.autoOpenInterview
    interviewError.value = ''
    interviewForm.scheduled_at = ''
    interviewForm.location = ''
    interviewForm.notes = ''
  }
})

const closeModal = () => {
  emit('close')
}

const scheduleInterview = async () => {
  if (!props.applicant?.id) return
  
  scheduling.value = true
  interviewError.value = ''
  
  try {
    await $fetch(`http://127.0.0.1:8000/api/employer/applications/${props.applicant.id}/interviews`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: {
        scheduled_at: interviewForm.scheduled_at,
        location_url: interviewForm.location,
        notes: interviewForm.notes
      }
    })
    
    // Automatically change status to targeted interview step
    await $fetch(`http://127.0.0.1:8000/api/employer/applications/${props.applicant.id}/status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { status: props.targetInterviewStatus }
    })

    alert('Interview scheduled successfully!')
    showInterviewForm.value = false
    emit('refresh')
  } catch (err: any) {
    interviewError.value = err.data?.message || 'Failed to schedule interview.'
  } finally {
    scheduling.value = false
  }
}
</script>
