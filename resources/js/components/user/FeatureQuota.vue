<template>
    <div v-if="!isUnlimited" class="fw-bold btn btn-rounded btn-secondary">{{ limit - quota <=0 ? 'No Quotas Left':`Quota : ${limit-quota}` }}</div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {

    props: {
        type: {
            type: String,
            default: '',
        }
    },

    data() {
        return {
            limit: 0,
            quota: 0,
            isUnlimited: false,
        }
    },



    computed: {

        ...mapGetters({
            user: 'user',
        }),


    },

    mounted() {
        const limit = this.user.company.subscription.permission
            .filter((p) => p.permission == this.type + '_limit');
        if (limit.length > 0) {
            this.limit = limit[0].value;
        }
        if (this.limit != '') {
            this.quota = this.user.company.usages[this.type] ?? 0;
        } else {
            this.isUnlimited = true;
        }

    },


}
</script>