<script setup>
  defineProps({
    event: Object
  })
</script>

<template>
  <div class="return-event">
    <div v-if="event.created_by">
      {{event.created_by.name}}
    </div>
    <div v-else>-</div>
    <div>{{event.created_at}}</div>
    <div>{{event.event_title}}</div>


    <div v-if="event.ref_type === 'status' && event.event_ref">
      {{event.event_ref.name}}
    </div>
    <div v-else>
      {{event.value}}
    </div>
  </div>
</template>

<style scoped lang="scss">

</style>