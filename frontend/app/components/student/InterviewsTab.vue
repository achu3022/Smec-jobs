<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
      <div>
        <h3 class="text-2xl font-bold text-slate-900">My Interviews</h3>
        <p class="text-slate-500 mt-1">Keep track of your upcoming and past interview schedules.</p>
      </div>
    </div>
    
    <div v-if="pending" class="py-12 flex justify-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
    </div>
    
    <div v-else-if="interviews.length === 0" class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
      <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-400">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
      </div>
      <h4 class="text-lg font-bold text-slate-900 mb-2">No Interviews Scheduled</h4>
      <p class="text-slate-500 mb-6 max-w-md mx-auto">You don't have any interviews scheduled yet. Keep applying to jobs and they will appear here once employers schedule them.</p>
    </div>
    
    <div v-else class="space-y-8">
      <div v-for="(group, index) in groupedInterviews" :key="index" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        
        <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center gap-4">
          <div class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
            <span class="text-slate-400 font-bold text-lg">{{ group.company.charAt(0) }}</span>
          </div>
          <div>
            <h4 class="font-bold text-slate-900 text-lg">{{ group.jobTitle }}</h4>
            <p class="text-sm text-slate-500 font-medium">{{ group.company }}</p>
          </div>
        </div>
        
        <div class="p-6 space-y-6">
          <div v-for="interview in group.interviews" :key="interview.id" class="flex flex-col md:flex-row gap-6 items-start relative">
            
            <!-- Connector Line (if multiple) -->
            <div v-if="group.interviews.length > 1 && interview !== group.interviews[group.interviews.length - 1]" class="hidden md:block absolute left-10 top-20 bottom-[-1.5rem] w-0.5 bg-slate-100 -z-10"></div>

            <!-- Date Badge -->
            <div class="shrink-0 flex flex-col items-center justify-center bg-white border-2 border-slate-100 rounded-xl w-20 h-20 shadow-sm z-10">
              <span class="text-xs font-bold text-slate-500 uppercase">{{ new Date(interview.scheduled_at).toLocaleDateString('en-US', { timeZone: 'Asia/Kolkata', month: 'short' }) }}</span>
              <span class="text-2xl font-black text-primary-600 leading-none">{{ new Date(interview.scheduled_at).toLocaleDateString('en-US', { timeZone: 'Asia/Kolkata', day: 'numeric' }) }}</span>
              <span class="text-[10px] font-bold text-slate-400">{{ new Date(interview.scheduled_at).toLocaleTimeString('en-US', { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit' }) }} IST</span>
            </div>
            
            <!-- Interview Info -->
            <div class="flex-1 min-w-0 border border-slate-100 rounded-2xl p-5 bg-slate-50 hover:border-primary-200 transition-colors w-full">
              <div class="flex justify-between items-start mb-3 gap-2">
                <div class="flex gap-2 items-center flex-wrap">
                  <span v-if="interview.type" class="bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full border border-purple-100 capitalize">{{ interview.type }}</span>
                  <span v-if="interview.status === 'scheduled'" class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-100 capitalize">Scheduled</span>
                  <span v-else-if="interview.status === 'completed'" class="bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full border border-green-100 capitalize">Completed</span>
                  <span v-else-if="interview.status === 'cancelled'" class="bg-red-50 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full border border-red-100 capitalize">Cancelled</span>
                  <span v-else class="bg-slate-200 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-full capitalize">{{ interview.status }}</span>
                </div>
              </div>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 text-sm text-slate-700">
                <div v-if="interview.interviewer_name" class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                  <span class="truncate"><strong>Interviewer:</strong> {{ interview.interviewer_name }}</span>
                </div>
                
                <div v-if="interview.location_url" class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                  <a :href="interview.location_url" target="_blank" class="text-primary-600 font-bold hover:underline truncate">Join Link / Location</a>
                </div>
              </div>
              
              <div v-if="interview.notes" class="mt-4 p-3 bg-yellow-50 rounded-lg text-sm text-yellow-800 border border-yellow-100">
                <strong>Notes:</strong> {{ interview.notes }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

const { data, pending } = await useFetch<any>('http://127.0.0.1:8000/api/applicant/interviews', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const interviews = computed(() => data.value?.interviews || [])

const groupedInterviews = computed(() => {
  const groups: Record<number, any> = {}
  interviews.value.forEach((interview: any) => {
    const appId = interview.application_id
    if (!groups[appId]) {
      groups[appId] = {
        jobTitle: interview.application?.job?.title || 'Unknown Job',
        company: interview.application?.job?.company?.name || 'Unknown Company',
        interviews: []
      }
    }
    groups[appId].interviews.push(interview)
  })
  return Object.values(groups)
})
</script>