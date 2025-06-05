<template>
    <dashboard-admin/>
</template>

<script>
import { mapGetters } from 'vuex'
import DashboardAdmin from './DashboardAdmin.vue';

export default {
    components: {
        DashboardAdmin,
    },
    computed: mapGetters({
        user: 'user',
        department: 'department'
    }),

    data() {
      return {}
    },
    mounted() {
    },
    methods: {
    }
};
</script>
