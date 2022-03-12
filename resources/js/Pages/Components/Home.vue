<template>
    <div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 sm:px-20 bg-white border-b border-gray-200">

                        <div>
                            <inertia-link  :href="route('loan.new')">
                                <jet-button :disabled="loan!=null">
                                    Apply for loan
                                </jet-button>
                            </inertia-link>

                            <inertia-link class="ml-2" :href="route('guarantor')">
                                <jet-secondary-button>
                                    Guarantee Loan
                                </jet-secondary-button>
                            </inertia-link>

                            <jet-section-border />
                        </div>

                        <div v-if="loan">
                            <div class="mb-4 text-3xl text-gray-800 font-bold">
                                Current Loans
                            </div>
                            <inertia-link
                                :href="route('loan.show',{'code':loan.code})"
                            >
                                <div class="cursor-pointer m-2 p-6 rounded bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition">
                                    <div class="text-4xl text-gray-100 font-bold ">MK{{ loan.amount }}</div>
                                    <div class=" flex justify-start">
                                        <alert-circle :fill-color="getStatusColor(loan.progress)"/>
                                        <span class="ml-2 text-gray-400">{{getStatus(loan.progress)}}</span>
                                    </div>
                                </div>
                            </inertia-link>
                            <jet-section-border />
                        </div>

<!--                            <inertia-link-->
<!--                                v-else-->
<!--                                :href="route('loan.new')"-->
<!--                            >-->
<!--                                <div class="cursor-pointer p-6 rounded bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition">-->
<!--                                    <div class="text-4xl text-gray-100 font-bold ">No Active Loan</div>-->
<!--                                    <div class=" flex justify-start">-->
<!--                                        <span class="ml-2 text-gray-400">Click to apply</span>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                            </inertia-link>-->




                        <div class="mt-4 text-3xl text-gray-800 font-bold">
                            Get quick loans with us
                        </div>

                        <div class="mt-2 text-gray-600">
                            You can borrow MK5,000 to MK100,000 and pay back an interest of {{(contents.interest)*100}}% until your payday. Zachangu will charge MK{{contents.fee}} as bank transaction fee for all transactions made.
                        </div>


                        <div class="mt-6 text-gray-600">
                            For Example
                        </div>

                        <div class="mt-2">
                            <!--                <jet-label for="amount" value="Enter Amount" />-->
                            <jet-input id="amount" type="email" class="mt-1 block w-full" v-model="amount" placeholder="Enter Amount" />
                            <span class="text-xs text-gray-400">Between MK5,000 and MK100,000</span>
                        </div>

                        <div class="ml-3">
                            <div class="mt-4">
                                <div>MK{{ contents.fee }}</div>
                                <div class="text-sm text-gray-600">Bank Sending Fee</div>
                            </div>
                            <div class="mt-4">
                                <div>MK{{validation?amountReceived:'0'}}</div>
                                <div class="text-sm text-gray-600">Amount Received</div>
                            </div>
                            <jet-section-border />
                            <div class="mt-4">
                                <div>MK{{ contents.fee }}</div>
                                <div class="text-sm text-gray-600">Bank Withdrawal Fee</div>
                            </div>
                            <div class="mt-4">
                                <div>MK{{validation?interest:'0'}}</div>
                                <div class="text-sm text-gray-600">Interest charged</div>
                            </div>
                            <div class="mt-4">
                                <div>MK{{validation?amountToPay:'0'}}</div>
                                <div class="text-sm text-gray-600">Amount to pay back</div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

<!--        <div class="w-full h-20"></div>-->
<!--        <div v-show="loan==null" class=" p-6 bg-gray-800 w-full flex items-center justify-center">
            <inertia-link :href="route('loan.new')">
                <jet-button class="">
                    Apply for loan
                </jet-button>
            </inertia-link>
        </div>-->

    </div>
</template>

<script>

    import JetLabel from '@/Jetstream/Label.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import JetInput from '@/Jetstream/Input'
    import AlertCircle from 'vue-material-design-icons/AlertCircle.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        props:['loan'],
        components: {
            JetLabel,
            JetButton,
            JetSecondaryButton,
            JetValidationErrors,
            JetInput,
            JetSectionBorder,
            AlertCircle
        },

        data() {
            return {
                amount:'',
                contents:{
                    amountLimit:{
                        lower:this.$page.props.contents.lowerLimit,
                        upper:this.$page.props.contents.upperLimit,
                    },
                    fee:this.$page.props.contents.bankCharge,
                    interest:this.$page.props.contents.interest,
                }
            }
        },
        computed:{
            validation:function () {
                return (this.amount>=this.contents.amountLimit.lower && this.amount<=this.contents.amountLimit.upper);
            },
            interest:function () {
                return Math.round(this.amount*this.contents.interest);
            },
            amountReceived:function () {
                return Math.round(this.amount-this.contents.fee);
            },
            amountToPay:function () {
                return Math.round(this.amount *(this.contents.interest + 1) + this.contents.fee);
            },
        },
        methods: {
            getStatus(progress){
                switch (progress){
                    case '0':
                        return 'Finish the applying process';
                        break;
                    case '1':
                        return 'Waiting for your guarantor to approve';
                        break;
                    case '2':
                        return 'Waiting for your employer to approve';
                        break;
                    case '3':
                        return 'Active';
                        break;
                    case '4':
                        return 'Closed';
                        break;
                    default:
                        return 'Nothing';
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
                    case '4':
                        return '#EF4444';
                        break;
                    default:
                        break;
                }
            },

        }
    }
</script>
