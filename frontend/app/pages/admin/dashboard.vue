<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
      <h1 class="text-3xl font-bold mb-8 text-slate-800">Admin Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <AdminWidget title="Total Job Seekers" :value="stats.jobSeekers" icon="👥" />
        <AdminWidget title="Total Employers" :value="stats.employers" icon="🏢" />
        <AdminWidget title="Active Jobs" :value="stats.activeJobs" icon="💼" />
        <AdminWidget title="Applications Today" :value="stats.applicationsToday" icon="📄" />
        <AdminWidget title="New Registrations" :value="stats.newRegistrations" icon="🆕" />
        <AdminWidget title="Revenue" :value="stats.revenue" icon="💰" />
        <AdminWidget title="Course Enquiries" :value="stats.courseEnquiries" icon="🎓" />
        <AdminWidget title="Recent Activities" :value="stats.recentActivities" icon="🕒" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AdminSidebar from '~/components/AdminSidebar.vue'
import AdminWidget from '~/components/AdminWidget.vue'
import { useAuthStore } from '~/stores/auth'

const stats = ref({
  jobSeekers: 0,
  employers: 0,
  activeJobs: 0,
  applicationsToday: 0,
  newRegistrations: 0,
  revenue: '₹0',
  courseEnquiries: 0,
  recentActivities: 0,
})

const fetchStats = async () => {
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/dashboard', {
      headers: { Authorization: `Bearer ${useAuthStore().token}` },
      server: false,
    })
    stats.value = res
  } catch (e) {
    console.error('Failed to load admin stats', e)
  }
}

onMounted(() => {
  fetchStats()
})
</script>

<style scoped>
/* Admin‑specific styling */
</style>
