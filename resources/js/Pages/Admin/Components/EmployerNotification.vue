<template>
    <div>
        <div
            @click="dialog=true"
            class="cursor-pointer">

            <div class="my-2 flex items-center justify-start">
                <card-account-details :size="24" fill-color="#3B82F6"/>
                <div class="ml-4">
                    <div class="text-base md:text-lg"><span class="font-bold">{{ notifications.user.firstName }} {{ notifications.user.lastName }}</span> has registered a new employer: {{ employer.name }}</div>
                    <div class="text-xs md:text-sm text-gray-400">{{ notifications.date.formatted }}</div>
                </div>
            </div>
        </div>


        <jet-dialog-modal :show="dialog" @close="closeModal">
            <template #title>
                Employer Information
            </template>

            <template #content class="p-6 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="" >
                        <div>{{employer.name}}</div>
                        <div class="text-sm text-gray-400">Name</div>
                    </div>
                    <div class="mt-4 md:ml-4 md:mt-0">
                        <div>{{employer.email}}</div>
                        <div class="text-sm text-gray-400">Email</div>
                    </div>

                    <div class="mt-4 md:col-span-2">
                        <div>{{employer.physicalAddressName}} P. O. Box {{employer.physicalAddressBox}} {{employer.physicalAddressLocation}}</div>
                        <div class="text-sm text-gray-400">Address</div>
                    </div>

                </div>

                <div class="mt-6 text-lg text-gray-400 ">Employer Details</div>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="" >
                        <div>{{employer.proxyName}}</div>
                        <div class="text-sm text-gray-400">Full Name</div>
                    </div>
                    <div class="mt-4 md:ml-4 md:mt-0">
                        <div>{{employer.proxyEmail}}</div>
                        <div class="text-sm text-gray-400">Email</div>
                    </div>
                    <div class="mt-4">
                        <div>+265 {{employer.proxyPhoneNumber}}</div>
                        <div class="text-sm text-gray-400">Phone Number</div>
                    </div>

                </div>

            </template>

            <template #footer>
                <jet-button-secondary @click.native="closeModal">
                    Close
                </jet-button-secondary>
            </template>
        </jet-dialog-modal>


    </div>
</template>

<script>

import JetDialogModal from '@/Jetstream/DialogModal'
import JetButtonSecondary from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import CardAccountDetails from "vue-material-design-icons/CardAccountDetails.vue";


export default {
    name: "EmployerNotification",
    props:[
        'notifications'
    ],
    components:{
        JetDialogModal,
        JetButtonSecondary,
        JetButton,
        CardAccountDetails,

    },
    data() {
        return {
            dialog:false,
        }
    },
    computed:{
        employer(){
          return this.notifications.contents.employer
        },
    },
    methods: {
        closeModal() {
            this.dialog = false
        },

    }



}
</script>

<style scoped>

</style>
