<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Application Form
            </h2>
        </template>

        <form @submit.prevent="submit">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-12 sm:px-20 bg-white border-b border-gray-200">

                            <jet-validation-errors class="mb-4" />

                              <!-- Amount -->
                            <div >
                                <div class="flex items-center justify-start">
                                    <div class="mt-0 p-1 h-6 w-6 rounded-full text-white text-center text-xs bg-gray-800 ">1</div>
                                    <span style="padding-left: 14px" class="text-xl">Amount</span>
                                </div>
                                <div class="mt-4 mx-2 p-6  border-l-2 border-gray-200">

                                    <div >
                                        <jet-label for="amount" value="Enter Amount" />
                                        <jet-input id="amount" type="text" class="mt-1 block w-full" v-model="form.amount" />
                                        <span class="text-xs text-gray-400">Between MK{{contents.amountLimit.lower}} and MK{{contents.amountLimit.upper}}</span>
                                    </div>

                                    <div class="ml-3">
                                        <div class="mt-4">
                                            <div>MK{{amountValidation?processingFee:'0'}}</div>
                                            <div class="text-sm text-gray-600">Processing Fee</div>
                                        </div>
                                        <div class="mt-4">
                                            <div>MK{{amountValidation?amountReceived:'0'}}</div>
                                            <div class="text-sm text-gray-600">Amount Received</div>
                                        </div>
                                        <jet-section-border />
                                        <!--                            <div class="mt-4">
                                                                        <div>MK{{ contents.fee }}</div>
                                                                        <div class="text-sm text-gray-600">Bank Withdrawal Fee</div>
                                                                    </div>-->
                                        <div class="mt-4">
                                            <div>MK{{amountValidation?interest:'0'}}</div>
                                            <div class="text-sm text-gray-600">Interest charged</div>
                                        </div>
                                        <div class="mt-4">
                                            <div>MK{{amountValidation?amountToPay:'0'}}</div>
                                            <div class="text-sm text-gray-600">Amount to pay back</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full h-20"></div>
            <div class="fixed bottom-0 h-20 p-6 bg-white shadow w-full flex items-center">
                <div v-if="validation" class="w-full flex items-center justify-center">
                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                    >
                        {{form.progress.percentage}}
                    </progress>
                    <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Submit Details
                    </jet-button>
                </div>
                <div v-else>
                    <div class="text-red-500">{{errorMessage}}</div>
                    <div class="text-sm text-gray-400">{{errorSection}}</div>
                </div>

            </div>
        </form>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default {

        components: {
            AppLayout,
            JetLabel,
            JetButton,
            JetInput,
            JetSectionBorder,
            JetValidationErrors
        },

        data() {
            return {
                form: this.$inertia.form({

                    //Amount
                    amount:'',

                }),

                contents:{
                    amountLimit:{
                        lower:this.$page.props.contents.lowerLimit,
                        upper:this.$page.props.contents.upperLimit,
                    },
                    fee:this.$page.props.contents.bankCharge,
                    interest:this.$page.props.contents.interest,
                },
                errorMessage:'',
                errorSection:'',
            }
        },
        created(){

        },
        computed:{

            validation:function () {
                if(this.amount===null || !(this.amountValidation) ){
                    this.errorSection='Amount';
                    this.errorMessage='Enter an amount';
                    return false;
                }
                else
                    return true
            },
            amountValidation:function () {
                return (this.form.amount>=this.contents.amountLimit.lower && this.form.amount<=this.contents.amountLimit.upper);
            },
            interest:function () {
                return Math.round(this.form.amount*this.contents.interest);
            },
            amountReceived:function () {
                return Math.round(this.form.amount - this.processingFee);
            },
            amountToPay:function () {
                return Math.round(this.form.amount *(this.contents.interest + 1));
            },
            processingFee:function () {
                return Math.round(this.form.amount * this.contents.fee);
            },
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,

                    }))
                    .post(this.route('loan.subscribed'))
            },
        }


    }
</script>
