<template>
   <app-layout-admin>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 p-6 sm:px-20 bg-white border-b border-gray-200 flex justify-end">

                        <span class="rounded p-2 bg-red-200 text-red-600 font-bold text-sm">{{due.length}} Due</span>
                        <span class="rounded p-2 bg-yellow-200 text-yellow-600 font-bold text-sm ml-2">{{pending.length}} Pending</span>
                        <span class="rounded p-2 bg-green-200 text-green-600 font-bold text-sm ml-2">{{active.length}} Active</span>
                    </div>

                    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 border-b border-gray-200" >
                        <div class="px-6 p-12 sm:px-20">
<!--                            <div
                                v-for="loan in due"
                                :key="loan.id"
                            >
                                <inertia-link
                                    class="cursor-pointer p-4"
                                    :href="route('loan.show',{code:loan.code})"
                                >
                                    <div class="text-4xl text-gray-800 font-bold ">MK{{ loan.amount }}</div>
                                    <div class=" flex justify-start">
                                        <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                        <span class="ml-2 text-gray-600">{{getStatus(loan.progress)}}</span>
                                    </div>
                                    <span class="text-gray-400">Created on: {{(loan.created_at).substr(0,10)}}</span>
                                </inertia-link>
                            </div>


                            <div
                                v-for="loan in pending"
                                :key="loan.id"
                            >
                                <inertia-link
                                    class="cursor-pointer "
                                    :href="route('loan.show',{code:loan.code})"
                                >
                                    <div class="md:p-4">
                                        <div class="text-2xl md:text-4xl text-gray-800 font-bold ">MK{{ loan.amount }}</div>
                                        <div class=" flex justify-start">
                                            <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                            <span class="ml-2 text-gray-600 text-sm md:text-base">{{getStatus(loan.progress)}}</span>
                                        </div>
                                    </div>

&lt;!&ndash;                                    <span class="text-gray-400">Created on: {{(loan.created_at).substr(0,10)}}</span>&ndash;&gt;
                                </inertia-link>
                                <jet-section-border/>
                            </div>

                            <div
                                v-for="loan in active"
                                :key="loan.id"
                            >
                                <inertia-link
                                    class="cursor-pointer p-4"
                                    :href="route('loan.show',{code:loan.code})"
                                >
                                    <div class="text-4xl text-gray-800 font-bold ">MK{{ loan.amount }}</div>
                                    <div class=" flex justify-start">
                                        <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                        <span class="ml-2 text-gray-600">{{getStatus(loan.progress)}}</span>
                                    </div>
                                    <span class="text-gray-400">Created on: {{(loan.created_at).substr(0,10)}}</span>
                                </inertia-link>
                            </div>-->

                            <loan
                                v-for="loan in due"
                                :key="loan.id"
                                :loan="loan"
                            />
                            <loan
                                v-for="loan in pending"
                                :key="loan.id"
                                :loan="loan"
                            />
                            <loan
                                v-for="loan in active"
                                :key="loan.id"
                                :loan="loan"
                            />

                            <div   v-if="(active).length==0 && (due).length==0  && (pending).length==0 " class="m-2 p-6">
                                <div>No Loans found.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full h-20"></div>
        <div class="bottom-0 fixed p-6 bg-gray-800 w-full flex items-center justify-center">
            <a :href="route('loan.admin.export')">
                <jet-button>
                    Export Datasheet
                </jet-button>
            </a>
<!--            <jet-dialog-modal :show="dialog" @close="closeModal">
                <template #title>
                    Export Datasheet
                </template>

                <template #content class="p-6 md:p-12">
                    <div class="text-gray-600">Select the date you want to start exporting from</div>
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

                </template>

                <template #footer>
                    <a :href="route('loan.admin.export',{'datestamp':datestamp})" target="_blank">
                        <jet-button class="">
                            Export Datasheet
                        </jet-button>
                    </a>

                    <jet-button-secondary @click.native="closeModal">
                        Close
                    </jet-button-secondary>
                </template>
            </jet-dialog-modal>-->
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
            'due',
            'pending',
            'active',
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
           /* exportDatasheet(){
                let date= (new Date(this.date).getTime())/1000
                this.$inertia.get(route('loan.export',{datestamp:date}))
            },*/
            closeModal() {
                this.dialog = false
            },
        }
    }
</script>
