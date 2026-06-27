<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-primary-600/20 text-primary-300 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-6 border border-primary-500/30">Legal</span>
        <h1 class="text-4xl md:text-5xl font-black mb-4">Privacy <span class="text-primary-400">Policy</span></h1>
        <p class="text-slate-400 text-sm">Last updated: June 2026</p>
      </div>
    </section>

    <section class="py-16 bg-white">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-slate max-w-none space-y-10">

          <div v-for="section in sections" :key="section.title" class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
            <h2 class="text-xl font-black text-slate-900 mb-3 flex items-center gap-2">
              <span class="text-primary-600">{{ section.icon }}</span> {{ section.title }}
            </h2>
            <div class="text-slate-600 text-sm leading-relaxed space-y-3">
              <p v-for="(para, i) in section.content" :key="i">{{ para }}</p>
            </div>
          </div>

          <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 text-center">
            <p class="text-slate-700 text-sm">Questions about this policy? <NuxtLink to="/contact" class="text-primary-600 font-bold hover:underline">Contact us</NuxtLink></p>
          </div>

        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useHead({ title: 'Privacy Policy — SMECJobs' })

const sections = [
  {
    icon: '📋',
    title: 'Information We Collect',
    content: [
      'We collect information you provide directly, such as your name, email address, mobile number, resume, work experience, and education details when you register and build your profile.',
      'We also collect information about your activity on the platform, including jobs you view, save, or apply to, and courses you enquire about.',
      'Employers who register provide company details, job listings, and contact information.',
    ]
  },
  {
    icon: '🔍',
    title: 'How We Use Your Information',
    content: [
      'To match job seekers with relevant job listings and employers with qualified candidates.',
      'To send notifications about new job postings, application status updates, and interview schedules.',
      'To improve our platform features, fix bugs, and personalise your experience.',
      'To respond to customer support requests and enquiries.',
      'We do not sell your personal information to third parties.',
    ]
  },
  {
    icon: '🤝',
    title: 'Sharing of Information',
    content: [
      'Your profile and resume are visible to employers when you apply for a job or when an employer searches for candidates.',
      'We may share anonymised, aggregated data for analytics and reporting purposes.',
      'We may disclose information when required by law or to protect the rights and safety of our users.',
    ]
  },
  {
    icon: '🍪',
    title: 'Cookies & Tracking',
    content: [
      'We use cookies to keep you logged in, remember your preferences, and understand how you interact with the platform.',
      'You can disable cookies in your browser settings, though some features may not work correctly without them.',
    ]
  },
  {
    icon: '🔒',
    title: 'Data Security',
    content: [
      'We implement industry-standard security measures including encrypted connections (HTTPS), hashed passwords, and access controls to protect your data.',
      'While we take all reasonable precautions, no online platform can guarantee absolute security. Please use a strong, unique password for your account.',
    ]
  },
  {
    icon: '👤',
    title: 'Your Rights',
    content: [
      'You can access, update, or delete your profile information at any time from your dashboard.',
      'You may request deletion of your account and all associated data by contacting our support team.',
      'You can opt out of non-essential email notifications from your account settings.',
    ]
  },
  {
    icon: '✏️',
    title: 'Changes to This Policy',
    content: [
      'We may update this Privacy Policy from time to time. When we do, we will update the "Last updated" date at the top of this page.',
      'Continued use of SMECJobs after changes are posted constitutes your acceptance of the updated policy.',
    ]
  },
]
</script>
