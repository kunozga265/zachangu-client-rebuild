<template>
        <div>
            <inertia-link
                class="cursor-pointer"
                :href="route('loan.admin.show',{code:loan.code})"
            >
                <div class="p-1 md:p-4">
                    <div class=" flex items-center justify-start">
<!--                        <div class="w-28 h-28 rounded-full bg-gray-800 bg-center bg-no-repeat bg-cover" :class="'bg-[url('+url(loan.photo)+')]'"></div>-->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full bg-gray-800 bg-center bg-no-repeat bg-cover shadow-md" :style="'background-image:url('+url(loan.photo)+')'"></div>
                        <div class="ml-4">
                            <div class="text-2xl sm:text-3xl md:text-4xl text-gray-800 font-bold "><span class="text-base sm:text-lg md:text-xl font-black">MK</span>{{ numberWithCommas(loan.amount) }}</div>
                            <span class="my-2 rounded-full py-1 px-2 bg-gray-200 text-gray-600 text-base font-bold"> {{loan.firstName}} {{loan.lastName}}</span>
                            <div class=" flex justify-start items-center">
                                <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                <span class="ml-2 text-gray-600 text-xs sm:text-sm md:text-base">{{getStatus(loan.progress)}}</span>
                            </div>
                        </div>
                    </div>


<!--                    <span class="text-gray-400">Created on: {{(loan.created_at).substr(0,10)}}</span>-->
                </div>
            </inertia-link>
            <jet-section-border/>
        </div>
</template>

<script>
    import AlertCircle from 'vue-material-design-icons/AlertCircle.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        props:[
            'loan'
        ],
        components: {
            AlertCircle,
            JetSectionBorder
        },

        methods:{
            numberWithCommas(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            url(path){
                return this.$page.props.publicPath+path
            },
            getStatus(progress){
                switch (progress){
                    case '0':
                        return 'Finish the applying process';
                        break;
                    case '1':
                        return 'Waiting for guarantor to approve';
                        break;
                    case '2':
                        return 'Waiting for employer to approve';
                        break;
                    case '3':
                        return 'Active';
                        break;
                    case '4':
                        return 'Closed';
                        break;
                    case '5':
                        return 'Defaulted';
                        break;
                    case '6':
                        return 'Over Due';
                        break;
                    case '7':
                        return 'Rejected';
                        break;
                    default:
                        return '-';
                        break;
                }
            },
            getStatusColor(progress){
                switch (progress){
                    case '0':
                        return '#FBBF24';
                        break;
                    case '1':
                        return '#FBBF24';
                        break;
                    case '2':
                        return '#FBBF24';
                        break;
                    case '3':
                        return '#4ADE80';
                        break;
                    default:
                        return '#EF4444';
                        break;
                }
            },
        }
    }
</script>
