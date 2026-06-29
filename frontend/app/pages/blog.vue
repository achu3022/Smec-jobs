<template>
  <div>
    <!-- Hero Section -->
    <div class="bg-slate-900 pt-32 pb-20 px-4 relative overflow-hidden">
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/40 to-slate-900/80 backdrop-blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Tech <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Insights & News</span></h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10">Stay updated with the latest trends in industrial automation, IT, embedded systems, and more directly from the SMECLabs blog.</p>
      </div>
    </div>

    <!-- Blog Grid -->
    <div class="max-w-7xl mx-auto px-4 py-16">
      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
      </div>
      
      <div v-else-if="!posts || posts.length === 0" class="text-center py-20">
        <div class="mb-6 flex justify-center text-primary-500">
          <Icon name="lucide:newspaper" class="w-20 h-20" />
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">No articles found</h3>
        <p class="text-slate-500">Check back later for the latest tech news.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <a 
          v-for="post in posts" 
          :key="post.id" 
          :href="post.link" 
          target="_blank"
          class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden group"
        >
          <!-- Thumbnail -->
          <div class="h-56 bg-slate-100 relative overflow-hidden">
            <img 
              v-if="getThumbnail(post)" 
              :src="getThumbnail(post)" 
              :alt="post.title.rendered" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            >
            <div v-else class="absolute inset-0 flex items-center justify-center text-slate-300">
              <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            
            <div class="absolute top-4 left-4">
              <span class="bg-white/90 backdrop-blur-sm text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                {{ formatDate(post.date) }}
              </span>
            </div>
          </div>
          
          <!-- Content -->
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-bold text-slate-900 mb-3 leading-snug group-hover:text-emerald-600 transition-colors line-clamp-2" v-html="post.title.rendered"></h3>
            
            <div class="text-slate-600 text-sm mb-6 line-clamp-3 flex-1" v-html="post.excerpt.rendered"></div>
            
            <div class="flex items-center text-emerald-600 font-bold text-sm mt-auto">
              Read Full Article 
              <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useHead } from '#imports'

useHead({
  title: 'Blog & News - SMECLabs'
})

// Fetching latest 9 posts with embedded data (for images)
const { data: posts, pending } = await useFetch('https://blog.smeclabs.com/wp-json/wp/v2/posts?_embed=1&per_page=9')

const formatDate = (dateStr: string) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const getThumbnail = (post: any) => {
  if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
    const media = post._embedded['wp:featuredmedia'][0]
    // Try to get medium size, fallback to source url
    if (media.media_details?.sizes?.medium?.source_url) {
      return media.media_details.sizes.medium.source_url
    }
    return media.source_url
  }
  return null
}
</script>

<style>
/* Remove WordPress default margins in excerpts */
.line-clamp-3 p {
  margin: 0;
  display: inline;
}
</style>
