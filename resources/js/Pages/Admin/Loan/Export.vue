<template>
   <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Export Datasheet
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 p-12 sm:px-20 bg-white border-b border-gray-200">
<!--                        <div class="text-gray-600">Select the date you want to start exporting from</div>
                        <div class="p-6">
                            <vue-date-time-picker
                                v-model="date"
                                onlyDate
                                inline
                                minDate="today"
                                format="YYYY-MM-DD"
                                class="w-full"
                            />
                        </div>
                        <div class="flex justify-end">
                            <a :href="route('loan.admin.export.datasheet',{'datestamp':datestamp})" target="_blank">
                                <jet-button class="">
                                    Export Datasheet
                                </jet-button>
                            </a>
                        </div>-->

                    </div>
                </div>
            </div>
        </div>
    </app-layout-admin>
</template>

<script>
   import AppLayoutAdmin from "@/Layouts/AppLayoutAdmin";
    import Welcome from '@/Jetstream/Welcome'
    import JetButton from '@/Jetstream/Button'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetButtonSecondary from '@/Jetstream/SecondaryButton'
    import Loan from '@/Pages/Admin/Components/Loan'


    export default {
        props:[

        ],
        components: {
           AppLayoutAdmin,
            Welcome,
            JetButton,
            JetDialogModal,
            JetButtonSecondary,
            Loan,

        },
          data() {
            return {
                dialog:false,
                date:new Date().toISOString().substr(0,10),
            }
        },
        computed:{
          datestamp(){
              return (new Date(this.date).getTime())/1000
          }
        },
        methods:{
          /*  exportDatasheet(){
                let date= (new Date(this.date).getTime())/1000
                this.$inertia.get(route('loan.admin.export.datasheet',{datestamp:date}))
            },*/
        }
    }
</script>
