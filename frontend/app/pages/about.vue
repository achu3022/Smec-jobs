<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-primary-600/20 text-primary-300 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-6 border border-primary-500/30">About SMECJobs</span>
        <h1 class="text-4xl md:text-5xl font-black mb-6 leading-tight">Connecting Talent with <span class="text-primary-400">Opportunity</span></h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">SMECJobs is a dedicated job portal built to bridge the gap between skilled job seekers and forward-thinking employers, backed by the expertise of SMECLABS.</p>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-16 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="bg-primary-50 rounded-3xl p-8 border border-primary-100">
          <div class="w-12 h-12 bg-primary-600 rounded-2xl flex items-center justify-center mb-5">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <h2 class="text-2xl font-black text-slate-900 mb-3">Our Mission</h2>
          <p class="text-slate-600 leading-relaxed">To empower job seekers with the right tools, training, and connections to land their dream careers, while helping employers discover pre-qualified, motivated candidates efficiently.</p>
        </div>
        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100">
          <div class="w-12 h-12 bg-slate-800 rounded-2xl flex items-center justify-center mb-5">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </div>
          <h2 class="text-2xl font-black text-slate-900 mb-3">Our Vision</h2>
          <p class="text-slate-600 leading-relaxed">To become the most trusted employment platform in India, where every skilled individual finds meaningful work and every company finds the talent it needs to grow.</p>
        </div>
      </div>
    </section>

    <!-- Why SMECJobs -->
    <section class="py-16 bg-slate-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-black text-slate-900 mb-3">Why Choose SMECJobs?</h2>
          <p class="text-slate-500 max-w-xl mx-auto">We go beyond job listings — we're a complete career ecosystem.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="item in features" :key="item.title" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="text-3xl mb-4">{{ item.icon }}</div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">{{ item.title }}</h3>
            <p class="text-slate-500 text-sm leading-relaxed">{{ item.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Backed by SMECLABS -->
    <section class="py-16 bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 mb-4">Backed by SMECLABS</h2>
        <p class="text-slate-600 leading-relaxed mb-6 max-w-2xl mx-auto">SMECLABS is a leading training and development institution with years of experience in upskilling professionals across India. SMECJobs is the natural extension of that mission — taking trained talent directly to the job market.</p>
        <NuxtLink to="/courses" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3 rounded-xl transition-colors shadow-sm">Explore Our Courses</NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useHead({ title: 'About Us — SMECJobs' })

const features = [
  { icon: '🎯', title: 'Targeted Job Matching', desc: 'Smart filters and category-based search help you find roles that actually fit your skills and goals.' },
  { icon: '🏢', title: 'Verified Employers', desc: 'Every company on the platform is reviewed to ensure candidates connect with legitimate, quality employers.' },
  { icon: '📄', title: 'Resume Builder', desc: 'Create and manage a professional resume directly within your applicant dashboard.' },
  { icon: '🎓', title: 'Integrated Training', desc: 'Access SMECLABS courses to upskill and increase your chances of getting hired faster.' },
  { icon: '📅', title: 'Interview Scheduling', desc: 'Employers can schedule interviews directly through the platform, keeping everything in one place.' },
  { icon: '🔔', title: 'Job Alerts', desc: 'Set up alerts for your preferred roles and get notified the moment a matching job is posted.' },
]
</script>
