const fs = require('fs');
const tabs = ['ProfileTab', 'ResumeTab', 'SearchJobsTab', 'RecommendedTab', 'InterviewsTab', 'CoursesTab', 'GuidanceTab', 'AlertsTab', 'MessagesTab', 'NotificationsTab', 'DocumentsTab', 'ActivityTab', 'SettingsTab'];
tabs.forEach(tab => {
  const content = `<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <h3 class="text-2xl font-bold text-slate-900 mb-2">${tab.replace('Tab', '')}</h3>
    <p class="text-slate-500">This section is under construction.</p>
  </div>
</template>
<script setup lang="ts">
</script>`;
  fs.writeFileSync('frontend/app/components/student/' + tab + '.vue', content);
});
