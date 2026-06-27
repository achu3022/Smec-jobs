<template>
  <div class="space-y-4">

    <!-- Row 1: Profile Card + Compact Stat Cards -->
    <div class="flex flex-col lg:flex-row gap-4 items-stretch">

      <!-- User Profile Card -->
      <div class="lg:w-72 shrink-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl px-6 py-4 text-white shadow-xl relative overflow-hidden border border-slate-700/50 group">
        <div class="absolute -right-8 -top-8 w-28 h-28 bg-primary-500/20 rounded-full blur-2xl transition-transform duration-700 group-hover:scale-150"></div>
        <div class="absolute -left-8 -bottom-8 w-28 h-28 bg-blue-500/20 rounded-full blur-2xl transition-transform duration-700 group-hover:scale-150"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 mix-blend-overlay"></div>
        
        <div class="relative z-10 flex items-center gap-4">
          <!-- Avatar -->
          <div class="relative shrink-0">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-400 to-blue-500 rounded-full blur opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative w-14 h-14 bg-slate-800 rounded-full border-2 border-slate-600 flex items-center justify-center text-xl font-black shadow-inner overflow-hidden z-10">
              <img v-if="authStore.user?.photo" :src="authStore.user.photo" alt="Profile photo" class="w-full h-full object-cover" />
              <span v-else class="bg-gradient-to-br from-white to-slate-400 bg-clip-text text-transparent">{{ authStore.user?.name?.charAt(0).toUpperCase() }}</span>
            </div>
            <button @click="activeTab = 'profile'" class="absolute -bottom-0.5 -right-0.5 z-20 bg-primary-600 hover:bg-primary-500 text-white p-1 rounded-full shadow-lg border border-slate-700 transition-transform hover:scale-110">
              <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </button>
          </div>

          <!-- Name / Email / Badge -->
          <div class="min-w-0 flex-1">
            <h2 class="text-base font-extrabold truncate leading-tight">{{ authStore.user?.name }}</h2>
            <p class="text-slate-400 text-xs truncate mb-1.5">{{ authStore.user?.email }}</p>
            <div class="inline-flex items-center gap-1 bg-slate-800/80 border border-slate-600/50 px-2.5 py-0.5 rounded-full">
              <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
              <span class="text-xs font-bold tracking-widest uppercase text-slate-200">Job Seeker</span>
            </div>
          </div>
        </div>

        <!-- Profile Completion -->
        <div class="relative z-10 mt-3">
          <div class="flex justify-between items-center mb-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profile</span>
            <span class="text-xs font-black text-white bg-slate-800 px-1.5 py-0.5 rounded border border-slate-700">45%</span>
          </div>
          <div class="w-full bg-slate-800/80 rounded-full h-1.5 overflow-hidden border border-slate-700/50">
            <div class="bg-gradient-to-r from-green-500 to-emerald-400 h-full rounded-full shadow-[0_0_8px_rgba(52,211,153,0.5)] transition-all duration-1000" style="width: 45%"></div>
          </div>
        </div>
      </div>

      <!-- Compact Stat Cards — all 4 in one row -->
      <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-3">

        <!-- Widget 1: Resume Strength -->
        <div class="bg-white rounded-2xl px-4 py-3 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden flex items-center gap-3">
          <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-green-50 to-transparent rounded-bl-full -mr-4 -mt-4"></div>
          <div class="w-9 h-9 shrink-0 rounded-xl bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          </div>
          <div class="min-w-0">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Resume Strength</p>
            <div class="flex items-baseline gap-1">
              <span class="text-xl font-black text-slate-900 leading-none">92</span>
              <span class="text-xs text-slate-400 font-bold">%</span>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-1.5 py-0.5 rounded mt-1 inline-block">Excellent</span>
          </div>
        </div>

        <!-- Widget 2: Career Match -->
        <div class="bg-white rounded-2xl px-4 py-3 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden flex items-center gap-3">
          <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-4 -mt-4"></div>
          <div class="w-9 h-9 shrink-0 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          </div>
          <div class="min-w-0">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Career Match</p>
            <div class="flex items-baseline gap-1">
              <span class="text-xl font-black text-slate-900 leading-none">88</span>
              <span class="text-xs text-slate-400 font-bold">%</span>
            </div>
            <span class="text-[10px] text-primary-600 font-bold bg-primary-50 px-1.5 py-0.5 rounded mt-1 inline-block">Highly Aligned</span>
          </div>
        </div>

        <!-- Widget 3: Interviews -->
        <div class="bg-white rounded-2xl px-4 py-3 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden flex items-center gap-3">
          <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-purple-50 to-transparent rounded-bl-full -mr-4 -mt-4"></div>
          <div class="w-9 h-9 shrink-0 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
          </div>
          <div class="min-w-0">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Interviews</p>
            <span class="text-xl font-black text-slate-900 leading-none">0</span>
            <p class="text-[10px] text-slate-500 font-bold mt-1">Upcoming</p>
          </div>
        </div>

        <!-- Widget 4: Total Applied -->
        <div class="bg-white rounded-2xl px-4 py-3 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden flex items-center gap-3">
          <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -mr-4 -mt-4"></div>
          <div class="w-9 h-9 shrink-0 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          </div>
          <div class="min-w-0">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Total Applied</p>
            <span class="text-xl font-black text-slate-900 leading-none">0</span>
            <p class="text-[10px] text-slate-500 font-bold mt-1">Lifetime</p>
          </div>
        </div>

      </div>
    </div>

    <!-- Row 2: Full Tabs Navigation -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-3 py-2">
      <div class="flex flex-wrap gap-1">
        <button v-for="tab in dashboardTabs" :key="tab.id"
          @click="activeTab = tab.id"
          :class="['px-3 py-1.5 font-semibold text-xs rounded-lg transition-all duration-150 whitespace-nowrap',
            activeTab === tab.id
              ? 'bg-primary-600 text-white shadow-sm'
              : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100']"
        >
          {{ tab.label }}
        </button>
      </div>
    </div>

    <!-- Dynamic Tab Content -->
    <div class="min-h-[400px]">
      <StudentProfileTab v-if="activeTab === 'profile'" />
      <StudentResumeTab v-if="activeTab === 'resume'" />
      <StudentSearchJobsTab v-if="activeTab === 'search_jobs'" />
      <StudentRecommendedTab v-if="activeTab === 'recommended'" />
      <StudentSavedJobsTab v-if="activeTab === 'saved'" />
      <StudentAppliedJobsTab v-if="activeTab === 'applications'" />
      <StudentInterviewsTab v-if="activeTab === 'interviews'" />
      <StudentCoursesTab v-if="activeTab === 'courses'" />
      <StudentGuidanceTab v-if="activeTab === 'guidance'" />
      <StudentAlertsTab v-if="activeTab === 'alerts'" />
      <StudentMessagesTab v-if="activeTab === 'messages'" />
      <StudentNotificationsTab v-if="activeTab === 'notifications'" />
      <StudentDocumentsTab v-if="activeTab === 'documents'" />
      <StudentActivityTab v-if="activeTab === 'activity'" />
      <StudentSettingsTab v-if="activeTab === 'settings'" />
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'

import StudentProfileTab from '~/components/student/ProfileTab.vue'
import StudentResumeTab from '~/components/student/ResumeTab.vue'
import StudentSearchJobsTab from '~/components/student/SearchJobsTab.vue'
import StudentRecommendedTab from '~/components/student/RecommendedTab.vue'
import StudentSavedJobsTab from '~/components/student/SavedJobsTab.vue'
import StudentAppliedJobsTab from '~/components/student/AppliedJobsTab.vue'
import StudentInterviewsTab from '~/components/student/InterviewsTab.vue'
import StudentCoursesTab from '~/components/student/CoursesTab.vue'
import StudentGuidanceTab from '~/components/student/GuidanceTab.vue'
import StudentAlertsTab from '~/components/student/AlertsTab.vue'
import StudentMessagesTab from '~/components/student/MessagesTab.vue'
import StudentNotificationsTab from '~/components/student/NotificationsTab.vue'
import StudentDocumentsTab from '~/components/student/DocumentsTab.vue'
import StudentActivityTab from '~/components/student/ActivityTab.vue'
import StudentSettingsTab from '~/components/student/SettingsTab.vue'

const authStore = useAuthStore()

const dashboardTabs = [
  { id: 'profile', label: 'My Profile' },
  { id: 'resume', label: 'Resume' },
  { id: 'search_jobs', label: 'Search Jobs' },
  { id: 'recommended', label: 'Recommended Jobs' },
  { id: 'saved', label: 'Saved Jobs' },
  { id: 'applications', label: 'Applied Jobs' },
  { id: 'interviews', label: 'Interviews' },
  { id: 'courses', label: 'SMECLABS Courses' },
  { id: 'guidance', label: 'Career Guidance' },
  { id: 'alerts', label: 'Job Alerts' },
  { id: 'messages', label: 'Messages' },
  { id: 'notifications', label: 'Notifications' },
  { id: 'documents', label: 'Documents' },
  { id: 'activity', label: 'Activity History' },
  { id: 'settings', label: 'Settings' }
]

const activeTab = ref('profile')

</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
