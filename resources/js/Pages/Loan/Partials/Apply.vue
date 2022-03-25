<template>

    <div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 sm:px-20 bg-white border-b border-gray-200">

                         <jet-validation-errors class="mb-4" />

                        <div>
                            <div class="flex items-center justify-start">
                                <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">1</div>
                                <span class="text-xl ml-4">Score</span>
                            </div>
                            <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                <div class="text-2xl text-gray-800 font-semibold">Loan Score</div>
                                <div class="mt-4 text-gray-600">How best the loan has matched with details given by the employer. You may update the details to get a better score.</div>
                                <div class="p-12 w-full flex justify-center">
                                    <div>
                                        <div class="text-7xl text-center font-extrabold mt-6">{{ loan.score }}%</div>
                                        <inertia-link :href="route('loan.edit',{'code':loan.code})">
                                            <jet-button-secondary class="mt-4">Update Loan Details</jet-button-secondary>
                                        </inertia-link>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex items-center justify-start">
                                <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">2</div>
                                <span class="text-xl ml-4">Status</span>
                            </div>
                            <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                <div class="text-2xl text-gray-800 font-semibold">Contract</div>
                                <!--Valid greater than 3 months-->
                                <div v-if="contractDurationEligibility" class="mt-4 text-gray-600"> Your current contract expires in {{computeHumanReadable(contractDuration)}}.</div>
                                <!--Less than 3 months-->
                                <div v-else class="mt-4 text-gray-600"> Your current contract is invalid. It has or will expire in less than 6 months.</div>

<!--                                <div class="mt-6 text-2xl text-gray-800 font-semibold">Subscription</div>-->
<!--                                <div class="mt-4 text-gray-600">  You are subscribed until  {{computeHumanReadable($page.props.user.subscription)}} from now. You are able to apply for loans without filling the entire form.</div>-->


                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex items-center justify-start">
                                <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">3</div>
                                <span class="text-xl ml-4">Employee Loan Agreement</span>
                            </div>
                            <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                <input class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" type="checkbox" v-model="termsAndConditionsCheck"/>
                                <span>I accept the terms in the <span class="cursor-pointer underline text-gray-600 hover:text-gray-900" @click="termsAndConditionsDialog=true">Loan Agreement</span></span>

                                <jet-dialog-modal :show="termsAndConditionsDialog" @close="closeModal">
                                    <template #title>
                                        Zachangu Microfinance Agency
                                    </template>

                                    <template #content>
                                        <div v-html="termsAndConditions"></div>
                                    </template>

                                    <template #footer>
                                        <jet-button-secondary @click.native="closeModal">
                                            Close
                                        </jet-button-secondary>
                                    </template>
                                </jet-dialog-modal>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="w-full h-20"></div>
        <div class="fixed bottom-0 h-20 p-6 bg-white shadow w-full flex items-center">
            <div v-if="validation" class="w-full flex items-center justify-center">

                <jet-button class="ml-4" @click.native="apply">
                    Apply for Loan
                </jet-button>

            </div>
            <div v-else>
                <div class="text-red-500">{{errorMessage}}</div>
                <div class="text-sm text-gray-400">{{errorSection}}</div>
            </div>

        </div>

    </div>

</template>

<script>
import JetButtonSecondary from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'


export default {
    name: "Apply",
    props:[
        'loan',
        'contractDuration',
        'contractDurationEligibility',
        'termsAndConditions',
    ],
    components:{
        JetButton,
        JetButtonSecondary,
        JetInput,
        JetDialogModal,
        JetValidationErrors
    },
    data() {
        return {
            termsAndConditionsCheck:false,
            termsAndConditionsDialog:false,
            errorMessage:'',
            errorSection:'',
        }
    },
    computed:{
        score:function () {
            return this.loan.score>0;
        },
        validation:function () {
            if(!this.score){
                this.errorMessage='You need a better score';
                this.errorSection='Score';
                return false;
            }else if(!this.contractDurationEligibility){
                this.errorMessage='Your contract duration is not valid';
                this.errorSection='Subscription';
                return false;
            }/*else if(this.consentCheck!==true){
                    this.errorMessage='Upload a signed consent form';
                    this.errorSection='Consent';
                    return false;
                }*/
            else if(!this.termsAndConditionsCheck){
                this.errorMessage='Accept the terms and conditions';
                this.errorSection='Terms and conditions';
                return false;
//                }else if(this.termsCheck!==true){
//                    this.errorMessage='Upload a signed terms and conditions document';
//                    this.errorSection='Terms and conditions';
//                    return false;
            }else
                return true
        },
    },
    methods:{
        apply(){
            this.$inertia.post(route('loan.apply',{'code':this.loan.code}))
        },
        computeHumanReadable:function(duration){
            let today=(new Date().getTime())/1000;
            let text='';
            duration=duration-today;

//                duration=duration/1000; //seconds
            duration=duration/60; //minutes
            duration=duration/60; //hours
            let days=duration/24; //days
            let months=days/30; //months

            if(months>=1){
                text=Math.floor(months)===1?'month':'months';
                return  Math.floor(months)+' '+text;
            }
            else{
                text=Math.floor(days)===1?'day':'days';
                return  Math.floor(days)+' '+text;
            }
        },
        closeModal() {
            this.termsAndConditionsDialog = false
        },
    }
}
</script>

<style scoped>

</style>
