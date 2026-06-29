<template>
  <div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-700"></div>
      </div>
      
      <div v-else-if="!data?.job" class="text-center py-20">
        <h2 class="text-2xl font-bold text-slate-800">Job not found</h2>
        <NuxtLink to="/" class="mt-4 inline-block text-primary-600 hover:underline">Back to Job Search</NuxtLink>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Job Details & Apply -->
        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
            <NuxtLink to="/" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary-600 mb-6">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
              Back to search
            </NuxtLink>

            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">{{ data.job.title }}</h1>
            <div class="text-xl text-slate-700 mb-2">{{ data.job.company.name }}</div>
            <div class="text-slate-600 mb-6 flex items-center">
              <svg class="w-5 h-5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
              {{ data.job.location }}
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-3 mb-8">
              <span v-if="data.job.salary_min" class="bg-green-50 text-green-800 text-sm font-bold px-3 py-1.5 rounded flex items-center">
                <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                ₹{{ (data.job.salary_min).toLocaleString() }} - ₹{{ (data.job.salary_max).toLocaleString() }} a month
              </span>
              <span class="bg-slate-100 text-slate-800 text-sm font-bold px-3 py-1.5 rounded flex items-center">
                <svg class="w-4 h-4 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                {{ data.job.job_type }}
              </span>
              <span v-if="data.job.is_remote" class="bg-indigo-50 text-indigo-800 text-sm font-bold px-3 py-1.5 rounded">Remote</span>
            </div>

            <!-- Apply Buttons -->
            <div class="mb-8 p-5 sm:p-6 bg-slate-50 rounded-xl border border-slate-200">
              <div class="flex flex-col sm:flex-row gap-3">
                <button v-if="data.job.has_applied" disabled class="bg-green-100 text-green-800 font-bold py-3 px-8 rounded-lg cursor-not-allowed w-full sm:w-auto">
                  Already Applied
                </button>
                <button v-else @click="applyForJob" :disabled="isApplying" class="bg-primary-700 hover:bg-primary-800 disabled:opacity-50 text-white font-bold py-3 px-8 rounded-lg transition-colors w-full sm:w-auto flex-1">
                  {{ isApplying ? 'Submitting...' : 'Apply for this Job' }}
                </button>
                
                <button @click="toggleSaveJob" :disabled="isSavingJob" class="border-2 hover:border-primary-600 bg-white hover:bg-primary-50 font-bold py-3 px-6 rounded-lg transition-colors w-full sm:w-auto flex items-center justify-center gap-2"
                  :class="data.job.is_saved ? 'border-primary-400 text-primary-700 bg-primary-50' : 'border-slate-200 text-slate-700 hover:text-primary-700'">
                  <svg v-if="data.job.is_saved" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                  <svg v-else class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                  {{ data.job.is_saved ? 'Saved' : 'Save Job' }}
                </button>
              </div>
              
              <div v-if="applyMessage" :class="['mt-3 text-sm font-bold', applyError ? 'text-red-600' : 'text-green-600']">
                {{ applyMessage }}
              </div>
            </div>

            <hr class="border-slate-200 mb-8">

            <h3 class="text-xl font-bold text-slate-900 mb-4">Full job description</h3>

            <!-- Mobile: collapsible description -->
            <div class="lg:hidden">
              <div
                class="prose prose-slate max-w-none text-slate-800 whitespace-pre-wrap overflow-hidden transition-all duration-500"
                :style="descriptionExpanded ? '' : 'max-height: 220px; -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%); mask-image: linear-gradient(to bottom, black 60%, transparent 100%);'"
              >
                {{ data.job.description }}
              </div>
              <button
                @click="descriptionExpanded = !descriptionExpanded"
                class="mt-4 w-full flex items-center justify-center gap-2 bg-primary-50 hover:bg-primary-100 text-primary-700 font-bold py-3 px-6 rounded-xl border border-primary-200 transition-all"
              >
                <svg class="w-5 h-5 transition-transform" :class="descriptionExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                {{ descriptionExpanded ? 'Show Less' : 'View More Description' }}
              </button>
            </div>

            <!-- Desktop: full description -->
            <div class="hidden lg:block prose prose-slate max-w-none text-slate-800 whitespace-pre-wrap">
              {{ data.job.description }}
            </div>

            <!-- Mobile: Related Courses section (shown BELOW description on mobile) -->
            <div class="lg:hidden mt-10 pt-8 border-t border-slate-200">
              <h3 class="text-xl font-bold text-slate-900 mb-2 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Related Courses
              </h3>
              <p class="text-sm text-slate-600 mb-5">Boost your profile with these recommended courses.</p>
              <div v-if="data.related_courses?.length === 0" class="text-sm text-slate-500 italic p-4 bg-slate-100 rounded-lg">
                No related courses found at this time.
              </div>
              <div class="space-y-4">
                <div v-for="course in data.related_courses" :key="'m-' + course.id" class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:border-primary-300 transition-colors">
                  <h4 class="font-bold text-slate-900 mb-2 text-sm">{{ course.title }}</h4>
                  <div class="flex flex-wrap gap-2 mb-3">
                    <span v-if="course.duration" class="bg-slate-100 text-slate-600 text-xs px-2 py-1 rounded font-medium">{{ course.duration }}</span>
                    <span v-if="course.placement_assistance" class="bg-green-50 text-green-700 text-xs px-2 py-1 rounded font-medium flex items-center">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      Placement
                    </span>
                  </div>
                  <div v-if="course.qualifications" class="text-xs text-slate-500 mb-3 line-clamp-2">
                    <strong>Requires:</strong> {{ course.qualifications }}
                  </div>
                  <button v-if="!authStore.isAuthenticated || authStore.user?.role === 'applicant'" @click="openEnquiry(course)" :disabled="isSubmittingEnquiry === course.id" class="w-full mt-2 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold py-2.5 px-4 rounded-lg transition-colors text-sm flex items-center justify-center gap-2">
                    <svg v-if="isSubmittingEnquiry === course.id" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    {{ isSubmittingEnquiry === course.id ? 'Sending...' : 'Enquire Now' }}
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Right Column: Related Courses (desktop only) -->
        <div class="hidden lg:block lg:col-span-1">
          <div class="sticky top-24">
            <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
              <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
              Related Courses
            </h3>
            
            <p class="text-sm text-slate-600 mb-6">Enhance your resume and increase your chances of getting hired with these recommended courses.</p>

            <div v-if="data.related_courses?.length === 0" class="text-sm text-slate-500 italic p-4 bg-slate-100 rounded-lg">
              No related courses found at this time.
            </div>
            <div class="space-y-4">
              <div v-for="course in data.related_courses" :key="course.id" class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:border-primary-300 transition-colors group">
                <h4 class="font-bold text-slate-900 group-hover:text-primary-700 text-sm mb-2">{{ course.title }}</h4>
                
                <div class="flex flex-wrap gap-2 mb-3">
                  <span v-if="course.duration" class="bg-slate-100 text-slate-600 text-xs px-2 py-1 rounded font-medium">{{ course.duration }}</span>
                  <span v-if="course.placement_assistance" class="bg-green-50 text-green-700 text-xs px-2 py-1 rounded font-medium flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Placement Assistance
                  </span>
                </div>
                
                <div v-if="course.qualifications" class="text-xs text-slate-500 mb-3 line-clamp-2">
                  <strong>Requires:</strong> {{ course.qualifications }}
                </div>
                
                <button v-if="!authStore.isAuthenticated || authStore.user?.role === 'applicant'" @click="openEnquiry(course)" :disabled="isSubmittingEnquiry === course.id" class="w-full mt-2 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold py-2.5 px-4 rounded-lg transition-colors text-sm flex items-center justify-center gap-2">
                  <svg v-if="isSubmittingEnquiry === course.id" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                  {{ isSubmittingEnquiry === course.id ? 'Sending...' : 'Enquire Now' }}
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ===== ENQUIRE NOW MODAL ===== -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showEnquiryModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="closeEnquiry">
          <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 p-6 text-white relative">
              <button @click="closeEnquiry" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                  <p class="text-xs font-semibold text-primary-200 uppercase tracking-wide mb-0.5">Course Enquiry</p>
                  <h3 class="font-bold text-lg leading-tight">{{ selectedCourse?.title }}</h3>
                </div>
              </div>
            </div>

            <!-- Enquiry Success -->
            <div v-if="enquirySuccess" class="p-8 text-center">
              <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              </div>
              <h4 class="text-xl font-bold text-slate-900 mb-2">Enquiry Submitted!</h4>
              <p class="text-slate-500 text-sm">Our team will contact you shortly about <strong>{{ selectedCourse?.title }}</strong>.</p>
              <button @click="closeEnquiry" class="mt-6 bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-8 rounded-xl transition-colors">Close</button>
            </div>

            <!-- Enquiry Form -->
            <form v-else @submit.prevent="submitEnquiry" class="p-6 space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name *</label>
                  <input v-model="enquiryForm.name" type="text" required placeholder="Your full name" class="w-full rounded-xl border border-slate-300 py-2.5 px-4 text-slate-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
                </div>
                <div class="col-span-2">
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Email *</label>
                  <input v-model="enquiryForm.email" type="email" required placeholder="you@example.com" class="w-full rounded-xl border border-slate-300 py-2.5 px-4 text-slate-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Mobile *</label>
                  <input v-model="enquiryForm.phone" type="tel" required placeholder="+91 00000 00000" class="w-full rounded-xl border border-slate-300 py-2.5 px-4 text-slate-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">District *</label>
                  <input v-model="enquiryForm.district" type="text" required placeholder="District" class="w-full rounded-xl border border-slate-300 py-2.5 px-4 text-slate-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
                </div>
                <div class="col-span-2">
                  <label class="block text-sm font-semibold text-slate-700 mb-1">State *</label>
                  <input v-model="enquiryForm.state" type="text" required placeholder="State" class="w-full rounded-xl border border-slate-300 py-2.5 px-4 text-slate-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
                </div>
              </div>

              <div v-if="enquiryError" class="text-red-600 text-sm font-medium bg-red-50 px-4 py-3 rounded-lg">{{ enquiryError }}</div>

              <button type="submit" :disabled="isSubmittingEnquiry === selectedCourse?.id" class="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                <svg v-if="isSubmittingEnquiry === selectedCourse?.id" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                {{ isSubmittingEnquiry === selectedCourse?.id ? 'Submitting...' : 'Submit Enquiry' }}
              </button>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '~/stores/auth'

const route = useRoute()
const authStore = useAuthStore()

const uuid = route.params.uuid as string

const { data, pending } = await useFetch<any>(`/api/jobs/${uuid}`, {
  headers: {
    Authorization: authStore.token ? `Bearer ${authStore.token}` : ''
  }
})

useSeoMeta({
  title: () => data.value?.job ? `${data.value.job.title} | SMECJobs` : 'Job Details',
})

// --- Job Actions ---
const isApplying = ref(false)
const applyMessage = ref('')
const applyError = ref(false)
const isSavingJob = ref(false)
const descriptionExpanded = ref(false)

const applyForJob = async () => {
  if (!authStore.isAuthenticated) {
    applyError.value = true
    applyMessage.value = 'Please log in to apply for this position.'
    return
  }
  if (authStore.user?.role !== 'applicant') {
    applyError.value = true
    applyMessage.value = 'Only registered candidates can submit applications.'
    return
  }
  isApplying.value = true
  applyMessage.value = ''
  applyError.value = false
  try {
    await $fetch(`/api/jobs/${data.value.job.id}/apply`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    applyMessage.value = 'Application submitted successfully! Track it in your Dashboard.'
    data.value.job.has_applied = true
  } catch (e: any) {
    applyError.value = true
    applyMessage.value = e.data?.message || 'Failed to submit application. Please try again.'
  } finally {
    isApplying.value = false
  }
}

const toggleSaveJob = async () => {
  if (!authStore.isAuthenticated) {
    applyError.value = true
    applyMessage.value = 'Please log in to save jobs.'
    return
  }
  isSavingJob.value = true
  try {
    const res: any = await $fetch(`/api/applicant/saved-jobs/${data.value.job.id}`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    data.value.job.is_saved = (res.status === 'saved')
  } catch (e: any) {
    console.error('Failed to save job', e)
  } finally {
    isSavingJob.value = false
  }
}

// --- Course Enquiry ---
const showEnquiryModal = ref(false)
const selectedCourse = ref<any>(null)
const enquirySuccess = ref(false)
const enquiryError = ref('')
const isSubmittingEnquiry = ref<number | null>(null)

const enquiryForm = reactive({
  name: '',
  email: '',
  phone: '',
  district: '',
  state: '',
})

const openEnquiry = async (course: any) => {
  if (authStore.isAuthenticated) {
    isSubmittingEnquiry.value = course.id
    try {
      await $fetch(`/api/courses/${course.id}/enquire`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      alert('Enquiry sent successfully!')
    } catch (e: any) {
      alert(e.data?.message || 'Failed to send enquiry. Please try again.')
    } finally {
      isSubmittingEnquiry.value = null
    }
  } else {
    selectedCourse.value = course
    enquirySuccess.value = false
    enquiryError.value = ''
    enquiryForm.name = ''
    enquiryForm.email = ''
    enquiryForm.phone = ''
    enquiryForm.district = ''
    enquiryForm.state = ''
    showEnquiryModal.value = true
  }
}

const closeEnquiry = () => {
  showEnquiryModal.value = false
  selectedCourse.value = null
  enquirySuccess.value = false
}

const submitEnquiry = async () => {
  if (!selectedCourse.value) return
  isSubmittingEnquiry.value = selectedCourse.value.id
  enquiryError.value = ''
  try {
    await $fetch(`/api/courses/${selectedCourse.value.id}/enquire`, {
      method: 'POST',
      body: {
        name: enquiryForm.name,
        email: enquiryForm.email,
        phone: enquiryForm.phone,
        district: enquiryForm.district,
        state: enquiryForm.state,
      }
    })
    enquirySuccess.value = true
  } catch (e: any) {
    enquiryError.value = e.data?.message || 'Failed to submit enquiry. Please try again.'
  } finally {
    isSubmittingEnquiry.value = null
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
