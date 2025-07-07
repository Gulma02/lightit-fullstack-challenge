<template>
    <table class="w-full">
        <thead class="bg-gray-300 opacity-70 rounded-lg">
        <tr class="border border-gray-200 h-[36px] rounded-lg">
            <th>Name</th>
            <th>Email</th>
            <th>Phone number</th>
            <!--th>&nbsp</th-->
        </tr>
        </thead>
        <tbody>
        <tr v-for="(patient, key) of patients" class="items-center text-center h-[36px]" :class="{'bg-gray-100':key%2}">
            <td>{{patient.name}}</td>
            <td>{{patient.email}}</td>
            <td>{{patient.number}}</td>
            <!--td>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4" @click="viewPatientDoc(patient.documentFilePath)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </td-->
        </tr>
        </tbody>
    </table>

    <PatientImg :patientImgModalVisible="patientImgModalVisible" :patientDocImg="patientDocImg" />
</template>

<script setup lang="ts">
    import PatientImg from "@/Components/Patients/PatientImg.vue";
    import { ref } from "vue";

    defineProps<{
        patients: Patient[]
    }>()

    const patientImgModalVisible = ref<boolean>(false);
    const patientDocImg = ref<string>("")

    const viewPatientDoc = (docImg: string) => {
        patientImgModalVisible.value = true;
        patientDocImg.value = docImg;
    }
</script>
