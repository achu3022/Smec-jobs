<template>
  <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
      <h2 class="text-xl font-bold text-slate-800">{{ title }}</h2>
      <div class="flex gap-4">
        <input 
          v-if="searchable"
          type="text" 
          v-model="searchQuery" 
          @input="$emit('search', searchQuery)"
          placeholder="Search..." 
          class="rounded-xl border-slate-300 bg-white border py-2 px-4 text-sm focus:ring-primary-500 w-64"
        >
        <slot name="actions"></slot>
      </div>
    </div>
    
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 text-slate-500 text-sm font-bold uppercase tracking-wider">
            <th v-for="col in columns" :key="col.key" class="p-4 border-b border-slate-100">{{ col.label }}</th>
            <th v-if="hasActions" class="p-4 border-b border-slate-100 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="item in items" :key="item.id" class="hover:bg-slate-50 transition-colors">
            <td v-for="col in columns" :key="col.key" class="p-4 text-slate-700 text-sm">
              <slot :name="`cell-${col.key}`" :item="item">
                {{ item[col.key] }}
              </slot>
            </td>
            <td v-if="hasActions" class="p-4 text-right">
              <slot name="actions-cell" :item="item"></slot>
            </td>
          </tr>
          <tr v-if="items.length === 0">
            <td :colspan="columns.length + (hasActions ? 1 : 0)" class="p-8 text-center text-slate-500">
              No data found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  title: string
  columns: Array<{ key: string, label: string }>
  items: Array<any>
  searchable?: boolean
  hasActions?: boolean
}>()

defineEmits(['search'])

const searchQuery = ref('')
</script>
