<script setup>
// vue composition api
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';

import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

onMounted(() => {
    getMahasiswa()
})

//  ---------------------------------------------

//  status modal mahasiswa --> open or close
const modalMahasiswaOpen = ref(false)

// form untuk Create dan Update mahasiswa
const formMahasiswa = useForm({
    npm: '',
    nama: '',
    jenis_kelamin: '',
    fakultas_id: '',
    prodi_id: '',
    alamat: ''
})

//... reset form
const resetFormMahasiswa = () => {
    formMahasiswa.reset()
    formMahasiswa.clearErrors()
    mahasiswa_id.value = false
}

//  ----------------------------------------------

// (cRud) get Mahasiswa
const listMahasiswaLoading = ref(false)
const listMahasiswa = ref({})
const getMahasiswa = () => {
    listMahasiswa.value = {}
    listMahasiswaLoading.value = true
    axios.get('http://127.0.0.1:8000/api/mahasiswa').then(res => {
        const data = res.data
        listMahasiswa.value = data
        listMahasiswaLoading.value = false
    }).catch(err => {
        alert(`Gagal mengambil data mahasiswa!\n\nError: ${err}`)
    })
}
//... show modal detail Mahasiswa (All Field) ... 
const detailMahasiswa = ref(false)
const modalDetailMahasiswa = (data) => {
    mahasiswa_id.value = false
    detailMahasiswa.value = data
    modalMahasiswaOpen.value = true
    getFakultas();
}

// (cRud) get Fakultas
const listFakultas = ref(false)
const getFakultas = () => {
    listFakultas.value = false
    axios.get('http://127.0.0.1:8000/api/jurusan/fakultas').then(res => {
        const data = res.data
        listFakultas.value = data
    }).catch(err => {
        alert(`Gagal mengambil data fakultas\n\nError: ${err}`)
    })
}

// (cRud) get Prodi
const listProdi = ref(false)
const getProdi = () => {
    listProdi.value = false 
const fk_id = formMahasiswa.fakultas_id     // API Route
    axios.post(`http://127.0.0.1:8000/api/jurusan/prodi?fakultas_id=${fk_id}`).then(res => {
        const data = res.data
        listProdi.value = data
    }).catch(err => {
        alert(`Gagal mengambil data Prodi\n\nError: ${err}`)
    })
}
//... 
watch(() => formMahasiswa.fakultas_id, () => {
    getProdi()
})

// (CrUd) create and update Mahasiswa
const saveMahasiswa = () => {
    formMahasiswa.clearErrors()
    formMahasiswa.transform(data => ({
        ...data,
        mahasiswa_id: mahasiswa_id.value
    })).post('http://127.0.0.1:8000/api/mahasiswa/save', {
        onSuccess: () => {
            modalMahasiswaOpen.value = false
            resetFormMahasiswa()
            getMahasiswa()
            alert('Data Berhasil Disimpan!')
        }
    })
}
//... show modal Create Mahasiswa
const modalAddMahasiswa = () => {
    modalMahasiswaOpen.value = true
    detailMahasiswa.value = false
    resetFormMahasiswa()
    getFakultas();
}
//... get data (by id) and set value 
const mahasiswa_id = ref(false)
const updateMahasiswa = () => {
    const data = detailMahasiswa.value
    mahasiswa_id.value = data.id

    formMahasiswa.npm = data.npm
    formMahasiswa.nama = data.nama
    formMahasiswa.jenis_kelamin = data.jenis_kelamin
    formMahasiswa.fakultas_id = data.fakultas_id
    formMahasiswa.prodi_id = data.prodi_id
    formMahasiswa.alamat = data.alamat
}

const deleteMahasiswa = () => {
    if (confirm('Yakin ingin dihapus?')) {
        const mahasiswa_id = detailMahasiswa.value.id
        axios.delete(`http://127.0.0.1:8000/api/mahasiswa/delete?mahasiswa_id=${mahasiswa_id}`).then(() => {
            alert(`Berhasil dihapus`)
            modalMahasiswaOpen.value = false
            resetFormMahasiswa()
            getMahasiswa()
        }).catch(err => {
            alert(`Gagal mengahapus data\n\nError: ${err}`)
        })
    }
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-end mb-1">
                            <PrimaryButton @click="modalAddMahasiswa()" type="PrimaryButton"
                                class="rounded-md px-2 py-1 text-white bg-indigo-600 hover:bg-indigo-700">
                                Tambah
                            </PrimaryButton>
                        </div>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-600 text-white">
                            <tr>
                                <td class="px-2 py-1 font-semibold w-10">No.</td>
                                <td class="px-2 py-1 font-semibold">NPM</td>
                                <td class="px-2 py-1 font-semibold">Nama</td>
                                <td class="px-2 py-1 font-semibold">Jenis Kelamin</td>
                                <td class="px-2 py-1 font-semibold">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in listMahasiswa.data" :key="index">
                                <td class="px-2 py-1">{{ index + 1 }}</td>
                                <td class="px-2 py-1">{{ data.npm }}</td>
                                <td class="px-2 py-1">{{ data.nama }}</td>
                                <td class="px-2 py-1">{{ data.jenis_kelamin }}</td>
                                <td class="px-2 py-1">
                                    <PrimaryButton @click="modalDetailMahasiswa(data)" type="PrimaryButton"
                                        class="px-2 py-1 rounded-md bg-gray-600 hover:bg-gray-700 text-white">
                                        Detail
                                    </PrimaryButton>
                                </td>
                            </tr>
                            <tr v-if="listMahasiswaLoading">
                                <td colspan="5" class="px-2 py-1 text-center font-bold">
                                    :: Loading ::
                                </td>
                            </tr>
                            <tr
                                v-else-if="listMahasiswa && (!listMahasiswa.data || (listMahasiswa.data && listMahasiswa.data.length <= 0))">
                                <td colspan="5" class="px-2 py-1 text-center font-bold">
                                    :: Tidak Ada Data ::
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <div v-if="listMahasiswa && listMahasiswa.links" class="flex p-2 bg-white">
                        <PrimaryButton>

                        </PrimaryButton>
                    </div> -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <DialogModal :show="modalMahasiswaOpen" @close="modalMahasiswaOpen = false">
        <template #title>
            Detail Mahasiswa
        </template>
        <template #content>
            <div class="text-gray-600 space-y-1">
                <div class="flex">
                    <div class="flex-initial w-36 font-semibold">
                        NPM
                    </div>
                    <div class="flex-1">
                        <template v-if="detailMahasiswa && !mahasiswa_id">
                            {{ detailMahasiswa.npm }}
                        </template>
                        <template v-else>
                            <input v-model="formMahasiswa.npm" type="text" class="px-2 py-1 rounded-md border-gray-300"
                                placeholder="Ketik Disini">
                            <div v-if="formMahasiswa.errors" class="text-xs text-red-600">{{ formMahasiswa.errors.npm }}
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-initial w-36 font-semibold">
                        Nama
                    </div>
                    <div class="flex-1">
                        <template v-if="detailMahasiswa && !mahasiswa_id">
                            {{ detailMahasiswa.nama }}
                        </template>
                        <template v-else>
                            <input v-model="formMahasiswa.nama" type="text" class="px-2 py-1 rounded-md border-gray-300"
                                placeholder="Ketik Disini">
                            <div v-if="formMahasiswa.errors" class="text-xs text-red-600">{{ formMahasiswa.errors.nama }}
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-initial w-36 font-semibold">
                        Jenis Kelamin
                    </div>
                    <div class="flex-1">
                        <template v-if="detailMahasiswa && !mahasiswa_id">
                            {{ detailMahasiswa.jenis_kelamin }}
                        </template>
                        <template v-else>
                            <select v-model="formMahasiswa.jenis_kelamin" class="pl-2 py-1 rounded-md border-gray-300">
                                <option value="" disabled selected>:: Pilih Satu ::</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div v-if="formMahasiswa.errors" class="text-xs text-red-600">{{
                                formMahasiswa.errors.jenis_kelamin }}</div>
                        </template>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-initial w-36 font-semibold">
                        Fakultas
                    </div>
                    <div class="flex-1">
                        <template v-if="detailMahasiswa && !mahasiswa_id">
                            {{ detailMahasiswa.fakultas ? detailMahasiswa.fakultas.nama : '-' }}
                        </template>
                        <template v-else>
                            <select v-model="formMahasiswa.fakultas_id" class="pl-2 py-1 rounded-md border-gray-300">
                                <option value="" v-if="!listFakultas" disabled selected> :: Loading :: </option>
                                <option value="" v-else disabled selected>:: Pilih Satu ::</option>
                                <option :value="data.id" v-for="(data, index) in listFakultas" :key="index"> {{ data.nama }}
                                </option>
                            </select>
                            <div v-if="formMahasiswa.errors" class="text-xs text-read-600">{{
                                formMahasiswa.errors.fakultas_id }}</div>
                        </template>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-initial w-36 font-semibold">
                        Program Studi
                    </div>
                    <div class="flex-1">
                        <template v-if="detailMahasiswa && !mahasiswa_id">
                            {{ detailMahasiswa.prodi ? detailMahasiswa.prodi.nama : '-' }}
                        </template>
                        <template v-else>
                            <select v-model="formMahasiswa.prodi_id"
                                :class="{ 'cursor-not-allowed bg-gray-200': !formMahasiswa.fakultas_id }"
                                :disabled="!formMahasiswa.fakultas_id" class="pl-2 py-1 rounded-md border-gray-300">
                                <option value="" v-if="!listProdi && formMahasiswa.fakultas_id" disabled selected> ::
                                    Loading :: </option>
                                <option value="" v-else disabled selected> :: Pilih Satu :: </option>
                                <option :value="data.id" v-for="(data, index) in listProdi" :key="index">{{ data.nama }}
                                </option>
                            </select>
                            <div v-if="formMahasiswa.errors" class="text-xs text-red-600">{{ formMahasiswa.errors.prodi_id
                            }}</div>
                        </template>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-initial w-36 font-semibold">
                        Alamat
                    </div>
                    <div class="flex-1">
                        <template v-if="detailMahasiswa && !mahasiswa_id">
                            {{ detailMahasiswa.alamat }}
                        </template>
                        <template v-else>
                            <textarea v-model="formMahasiswa.alamat" class="px-2 py-1 rounded-md border-gray-300"
                                placeholder="Ketik Disini"></textarea>
                            <div v-if="formMahasiswa.errors" class="text-xs text-red-600">{{ formMahasiswa.errors.alamat }}
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex justify-end space-x-2">
                <template v-if="detailMahasiswa && !mahasiswa_id">
                    <PrimaryButton @click="updateMahasiswa()" type="PrimaryButton"
                        class="rounded-md px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white">
                        Ubah
                    </PrimaryButton>
                    <PrimaryButton @click="deleteMahasiswa()" type="PrimaryButton"
                        class="rounded-md px-2 py-1 bg-red-600 hover:bg-red-700 text-white">
                        Hapus
                    </PrimaryButton>
                </template>
                <template v-else>
                    <PrimaryButton @click="saveMahasiswa()" type="PrimaryButton" :disabled="formMahasiswa.processing"
                        class="rounded-md px-2 py-1 bg-green-600 hover:bg-green-700 text-white">
                        {{ formMahasiswa.processing ? 'Loading' : 'Simpan' }}
                    </PrimaryButton>
                </template>
            </div>
        </template>
    </DialogModal>
</template>