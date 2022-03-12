<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Loan Details
            </h2>
        </template>

            <div>
                <apply
                    v-if="loan.progress==0"
                    :loan="loan"
                    :contractDuration="contractDuration"
                    :contractDurationEligibility="contractDurationEligibility"
                    :termsAndConditions="termsAndConditions"

                />
                <display
                    v-else
                    :loan="loan"
                    :contractDuration="contractDuration"
                    :employer="employer"
                />


            </div>


    </app-layout>

</template>

<script>
import Apply from './Partials/Apply'
import Display from './Partials/Display'
import AppLayout from '@/Layouts/AppLayout'

export default {
    name: "Show",
    props:[
        'loan',
        'contractDuration',
        'contractDurationEligibility',
        'termsAndConditions',
        'employer'

    ],
    components:{
        Apply,
        Display,
        AppLayout,
    }



}
</script>

<style scoped>

</style>
