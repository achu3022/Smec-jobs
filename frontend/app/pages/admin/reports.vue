<template>
  <div class="flex h-screen bg-gray-50 overflow-hidden">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Reports & Export</h1>
          <p class="text-slate-500 mt-1">Export raw data into Excel/CSV formats for offline analysis.</p>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Export Users -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
          <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Users Data</h3>
          <p class="text-slate-500 text-sm mb-8 flex-1">Export all registered Job Seekers, Employers, and Admins including their status and join dates.</p>
          <button @click="downloadCSV('users')" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export to CSV
          </button>
        </div>

        <!-- Export Jobs -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
          <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Jobs Directory</h3>
          <p class="text-slate-500 text-sm mb-8 flex-1">Download the entire directory of jobs including their respective companies, locations, and featured status.</p>
          <button @click="downloadCSV('jobs')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export to CSV
          </button>
        </div>

        <!-- Export Companies -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
          <div class="w-16 h-16 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Registered Companies</h3>
          <p class="text-slate-500 text-sm mb-8 flex-1">Generate a CSV of all employer companies containing industry types, base locations, and owner names.</p>
          <button @click="downloadCSV('companies')" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export to CSV
          </button>
        </div>
        
        <!-- Export Course Enquiries -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center transition-transform hover:-translate-y-1 md:col-span-3">
          <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Course Enquiries</h3>
          <p class="text-slate-500 text-sm mb-6 max-w-xl mx-auto">Export lead data from prospective students. Filter by status and date range.</p>
          
          <div class="w-full grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 text-left">
            <div>
              <label class="block text-xs font-bold text-slate-500 mb-1">Status</label>
              <select v-model="enquiryFilters.status" class="w-full rounded-lg border-slate-200 text-sm py-2">
                <option value="all">All Statuses</option>
                <option value="new">New</option>
                <option value="contacted">Contacted</option>
                <option value="closed">Closed</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 mb-1">Date Range</label>
              <select v-model="enquiryFilters.date_filter" class="w-full rounded-lg border-slate-200 text-sm py-2">
                <option value="">All Time</option>
                <option value="day">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
            <div v-if="enquiryFilters.date_filter === 'custom'">
              <label class="block text-xs font-bold text-slate-500 mb-1">Start Date</label>
              <input type="date" v-model="enquiryFilters.start_date" class="w-full rounded-lg border-slate-200 text-sm py-2">
            </div>
            <div v-if="enquiryFilters.date_filter === 'custom'">
              <label class="block text-xs font-bold text-slate-500 mb-1">End Date</label>
              <input type="date" v-model="enquiryFilters.end_date" class="w-full rounded-lg border-slate-200 text-sm py-2">
            </div>
          </div>

          <button @click="downloadEnquiries" class="w-full md:w-auto px-12 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-colors flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Enquiries to CSV
          </button>
        </div>
      </div>
      
      <div class="bg-primary-50 rounded-3xl p-8 border border-primary-100 flex items-start gap-4">
        <div class="text-primary-600 mt-1">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
          <h4 class="font-bold text-primary-900 mb-1">About Excel / CSV Exports</h4>
          <p class="text-primary-700 text-sm">
            All files are exported in standard UTF-8 CSV format, which guarantees maximum compatibility with Microsoft Excel, Google Sheets, and Apple Numbers. Simply open the downloaded file in your preferred spreadsheet software to view the raw data. The exported lists will include suspended and closed records automatically.
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import AdminSidebar from '~/components/AdminSidebar.vue'

const authStore = useAuthStore()

const enquiryFilters = reactive({
  status: 'all',
  date_filter: '',
  start_date: '',
  end_date: ''
})

const downloadCSV = async (type: string) => {
  try {
    // We use the browser's native fetch API to handle blob downloads
    const response = await fetch(`/api/admin/export/${type}`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    
    if (!response.ok) throw new Error('Export failed')
    
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    
    // Create temporary link to trigger download
    const a = document.createElement('a')
    a.href = url
    a.download = `smecjobs_export_${type}_${new Date().toISOString().split('T')[0]}.csv`
    document.body.appendChild(a)
    a.click()
    
    // Cleanup
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)
  } catch (e) {
    alert('Failed to generate export file. Please try again.')
    console.error(e)
  }
}

const downloadEnquiries = async () => {
  try {
    const params = new URLSearchParams()
    if (enquiryFilters.status) params.append('status', enquiryFilters.status)
    if (enquiryFilters.date_filter) params.append('date_filter', enquiryFilters.date_filter)
    if (enquiryFilters.start_date) params.append('start_date', enquiryFilters.start_date)
    if (enquiryFilters.end_date) params.append('end_date', enquiryFilters.end_date)
    
    const queryString = params.toString() ? `?${params.toString()}` : ''

    const response = await fetch(`/api/admin/export/enquiries${queryString}`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    
    if (!response.ok) throw new Error('Export failed')
    
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    
    const a = document.createElement('a')
    a.href = url
    a.download = `smecjobs_export_enquiries_${new Date().toISOString().split('T')[0]}.csv`
    document.body.appendChild(a)
    a.click()
    
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)
  } catch (e) {
    alert('Failed to generate export file. Please try again.')
    console.error(e)
  }
}
</script>
