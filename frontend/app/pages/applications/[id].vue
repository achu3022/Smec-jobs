<template>
  <div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Loading State -->
      <div v-if="pending" class="text-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
        <p class="mt-4 text-slate-600 font-medium">Loading candidate profile...</p>
      </div>
      
      <!-- Error State -->
      <div v-else-if="error || !application" class="bg-red-50 text-red-800 p-6 rounded-2xl border border-red-200 shadow-sm text-center">
        <h3 class="text-xl font-bold mb-2">Failed to load profile</h3>
        <p>{{ error || 'Application not found or you do not have permission to view it.' }}</p>
        <NuxtLink to="/dashboard" class="mt-6 inline-block bg-white text-slate-700 font-bold py-2 px-6 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors shadow-sm">
          Return to Dashboard
        </NuxtLink>
      </div>

      <!-- Profile View -->
      <div v-else class="space-y-6">
        
        <!-- Top Navigation -->
        <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
          <NuxtLink to="/dashboard" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Dashboard
          </NuxtLink>
          <div class="text-sm text-slate-500 font-medium">
             Applied for <span class="font-bold text-slate-700">{{ application.job?.title }}</span>
          </div>
        </div>

        <!-- Header -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="flex items-center gap-5">
             <div class="w-20 h-20 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600 text-3xl overflow-hidden shadow-sm shrink-0">
                <img v-if="application.user?.applicant_profile?.photo" :src="getLogoUrl(application.user.applicant_profile.photo)" class="w-full h-full object-cover"/>
                <span v-else v-text="application.user?.name ? application.user.name.charAt(0).toUpperCase() : 'U'"></span>
             </div>
             <div>
               <h1 class="text-3xl font-bold text-slate-900">{{ application.user?.name || 'Candidate Profile' }}</h1>
               <p class="text-slate-500 mt-1 font-medium flex items-center gap-2">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                 {{ application.user?.email }}
               </p>
             </div>
          </div>
          
          <div class="flex gap-3">
             <button v-if="application.status?.toLowerCase() !== 'accepted'" @click="updateStatus('Accepted')" :disabled="isUpdatingStatus" class="bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-sm">Accept Candidate</button>
             <button v-if="application.status?.toLowerCase() !== 'rejected'" @click="updateStatus('Rejected')" :disabled="isUpdatingStatus" class="bg-red-50 hover:bg-red-100 text-red-700 disabled:opacity-50 font-bold py-3 px-6 rounded-xl transition-colors">Reject</button>
          </div>
        </div>

        <!-- Basic Application Info -->
        <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
           <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
             <div>
               <h4 class="font-bold text-slate-900 text-lg mb-1">Application Status: 
                 <span :class="{'text-yellow-600': application.status?.toLowerCase() === 'pending', 'text-green-600': application.status?.toLowerCase() === 'accepted', 'text-red-600': application.status?.toLowerCase() === 'rejected'}">{{ application.status }}</span>
               </h4>
               <p class="text-slate-500 text-sm font-medium">Applied on {{ new Date(application.created_at).toLocaleDateString() }}</p>
             </div>
             <a v-if="application.resume?.file_path" :href="getResumeUrl(application.resume.file_path)" target="_blank" class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 font-bold px-6 py-3 rounded-xl border border-primary-200 hover:bg-primary-100 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                View Resume
             </a>
           </div>
        </section>

        <!-- Detailed Profile -->
        <div v-if="!application.user?.applicant_profile" class="text-center p-16 bg-white rounded-3xl border border-slate-200 shadow-sm">
           <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z"></path></svg>
           </div>
           <p class="text-slate-800 font-bold text-xl mb-2">Detailed Profile Not Provided</p>
           <p class="text-slate-500 font-medium">This candidate has only provided their basic details and resume.</p>
        </div>
        <div v-else class="space-y-6">
          
          <!-- Personal Info -->
          <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h4 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Personal Information</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-8 gap-x-6">
              <div v-if="application.user.applicant_profile.dob"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Date of Birth</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.dob }}</p></div>
              <div v-if="application.user.applicant_profile.gender"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Gender</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.gender }}</p></div>
              <div v-if="application.user.applicant_profile.marital_status"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Marital Status</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.marital_status }}</p></div>
              <div v-if="application.user.applicant_profile.nationality"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Nationality</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.nationality }}</p></div>
              <div v-if="application.user.applicant_profile.blood_group"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Blood Group</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.blood_group }}</p></div>
            </div>
          </section>

          <!-- Contact Info -->
          <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h4 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Contact Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-6">
              <div v-if="application.user.applicant_profile.mobile"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Mobile Number</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.mobile }}</p></div>
              <div v-if="application.user.applicant_profile.alt_mobile"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Alternate Mobile</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.alt_mobile }}</p></div>
              <div v-if="application.user.applicant_profile.address_current" class="md:col-span-2"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Current Address</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.address_current }}</p></div>
              <div v-if="application.user.applicant_profile.district || application.user.applicant_profile.state"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Location</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.district }}, {{ application.user.applicant_profile.state }}, {{ application.user.applicant_profile.country }} - {{ application.user.applicant_profile.pin_code }}</p></div>
            </div>
          </section>

          <!-- Career Info -->
          <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h4 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Career Summary</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-8 gap-x-6">
              <div v-if="application.user.applicant_profile.total_experience"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Total Experience</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.total_experience }}</p></div>
              <div v-if="application.user.applicant_profile.relevant_experience"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Relevant Experience</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.relevant_experience }}</p></div>
              <div v-if="application.user.applicant_profile.current_salary"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Current Salary</p><p class="font-medium text-slate-900 text-lg">₹{{ application.user.applicant_profile.current_salary }}</p></div>
              <div v-if="application.user.applicant_profile.expected_salary"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Expected Salary</p><p class="font-medium text-slate-900 text-lg">₹{{ application.user.applicant_profile.expected_salary }}</p></div>
              <div v-if="application.user.applicant_profile.notice_period"><p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Notice Period</p><p class="font-medium text-slate-900 text-lg">{{ application.user.applicant_profile.notice_period }}</p></div>
            </div>
            <div class="mt-8 flex flex-wrap gap-4">
              <span v-if="application.user.applicant_profile.immediate_joiner" class="bg-green-100 text-green-800 text-sm font-bold px-4 py-2 rounded-full flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Immediate Joiner
              </span>
              <span v-if="application.user.applicant_profile.willing_to_relocate" class="bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-full flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Willing to Relocate
              </span>
              <span v-if="application.user.applicant_profile.willing_to_travel" class="bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-full flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Willing to Travel
              </span>
            </div>
          </section>

          <!-- Experience History -->
          <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h4 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Experience History</h4>
            <div v-if="application.user.applicant_experiences?.length" class="space-y-4">
              <div v-for="exp in application.user.applicant_experiences" :key="exp.id" class="p-6 border border-slate-100 rounded-2xl bg-slate-50">
                <h5 class="font-bold text-slate-900 text-xl">{{ exp.job_role }} <span v-if="exp.company" class="font-medium text-slate-500">at {{ exp.company }}</span></h5>
                <p class="text-slate-600 mt-3 flex items-center gap-3">
                  <span class="font-medium bg-white px-3 py-1.5 rounded-lg shadow-sm">{{ exp.year }}</span>
                  <span v-if="exp.is_current" class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1.5 rounded-lg">Currently Working Here</span>
                </p>
              </div>
            </div>
            <div v-else class="text-slate-500 text-lg italic text-center py-8">No experience history provided.</div>
          </section>

          <!-- Education Info -->
          <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h4 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Education History</h4>
            <div v-if="application.user.applicant_educations?.length" class="space-y-4">
              <div v-for="edu in application.user.applicant_educations" :key="edu.id" class="p-6 border border-slate-100 rounded-2xl bg-slate-50">
                <h5 class="font-bold text-slate-900 text-xl">{{ edu.qualification }} <span v-if="edu.course_name" class="text-slate-500 font-medium">- {{ edu.course_name }}</span></h5>
                <p class="text-slate-600 mt-2 text-lg"><span class="font-medium">{{ edu.university }}</span> <span v-if="edu.passing_year">({{ edu.passing_year }})</span></p>
                <div class="mt-4 flex gap-4 text-sm text-slate-600 font-medium">
                   <span v-if="edu.branch" class="bg-white px-3 py-1.5 rounded-lg shadow-sm border border-slate-100">Branch: {{ edu.branch }}</span>
                   <span v-if="edu.board" class="bg-white px-3 py-1.5 rounded-lg shadow-sm border border-slate-100">Board: {{ edu.board }}</span>
                   <span v-if="edu.percentage" class="bg-white px-3 py-1.5 rounded-lg shadow-sm border border-slate-100">Score: {{ edu.percentage }}</span>
                </div>
              </div>
            </div>
            <div v-else class="text-slate-500 text-lg italic text-center py-8">No education history provided.</div>
          </section>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '~/stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
  }
})

const applicationId = route.params.id as string

const { data, pending, error, refresh } = await useFetch<any>(`/api/employer/applications/${applicationId}`, {
  headers: { Authorization: `Bearer ${authStore.token}` },
  server: false
})

const application = computed(() => data.value?.application)

const getLogoUrl = (path: string) => `/storage/${path}`
const getResumeUrl = (path: string) => `/storage/${path}`

const isUpdatingStatus = ref(false)
const updateStatus = async (status: string) => {
  if (!application.value) return
  isUpdatingStatus.value = true
  try {
    await $fetch(`/api/employer/applications/${application.value.id}/status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { status }
    })
    await refresh()
  } catch (e) {
    console.error('Failed to update status', e)
    alert('Failed to update application status.')
  } finally {
    isUpdatingStatus.value = false
  }
}
</script>

<style></style>
