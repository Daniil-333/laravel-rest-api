<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h1 class="card-title">Добавление нового оборуования</h1>
            </div>
            <div class="card-body">
                <div v-if="successMessage" class="alert alert-success">
                    {{ successMessage }}
                </div>
                <form @submit.prevent="addItem">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="equipmentType" class="form-label">Тип оборудования:</label>
                            <CFormSelect
                                aria-label="Default select example"
                                v-model="item.equipment_type_id"
                                :options="equipmentTypes">
                            </CFormSelect>
                            <div v-if="errorEquipTypeMessage" class="alert alert-warning">
                                {{ errorEquipTypeMessage }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="itemName" class="form-label">Серийный номер:</label>
                            <input type="text" class="form-control" id="itemName" v-model="item.serial_number"
                                   placeholder="Введите серийный номер" required>
                            <div v-if="errorSNMessage" class="alert alert-warning">
                                {{ errorSNMessage }}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="itemPrice" class="form-label">Комментарий:</label>
                            <textarea class="form-control" id="itemPrice" v-model="item.comment"
                                      placeholder="Комментарий"></textarea>
                        </div>

                        <div v-if="errors.length" class="alert alert-danger">
                            <p v-for="error in errors" :key="error.id">{{ error }}</p>
                        </div>

                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus-lg"></i> Добавить запись</button>
                    </div>
                </form>
                <div class="mt-4 d-flex justify-content-start">
                    <router-link :to="{ name: 'Index' }" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Вернуться к списку оборудования
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, reactive, ref} from 'vue';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const item = reactive({ equipment_type_id: '', serial_number: '', comment: '' });
        const equipmentTypes = ref([]);
        const router = useRouter();
        const successMessage = ref('');
        const errorEquipTypeMessage = ref('');
        const errorSNMessage = ref('');
        const errors = ref([]);

        const outputError = (errorType, msg) => {
            errorType.value = msg;
            setTimeout(() => errorType.value = '', 1000)
        }

        const addItem = async () => {
            const uri = 'https://ktelekom.loc/api/equipment';

            if(!item.equipment_type_id) {
                return outputError(errorEquipTypeMessage, 'Тип оборудования не заполнен!')
            }
            else if(!item.serial_number) {
                return outputError(errorSNMessage, 'Серийный номер не заполнен!')
            }

            try {
                const response = await axios.post(uri, [item]);

                if(response.data.errors) {
                    errors.value = response.data.errors;
                    setTimeout(() => errors.value = [],2000)
                    return;
                }

                // console.log(response);

                successMessage.value = 'Оборудование успешнор добавлено!';
                setTimeout(() => {
                    successMessage.value = '';
                    errorEquipTypeMessage.value = '';
                    errorSNMessage.value = '';
                    router.push({ name: 'Index' });
                }, 1000);
            } catch (error) {
                console.error('Ошибка добавления записи:', error);
            }
        };

        const fetchItems = async () => {
            try {
                const uri = 'https://ktelekom.loc/api/equipment-type';
                const response = await axios.get(uri);
                equipmentTypes.value = ['Open this select menu'].concat(response.data.data.map(eq => ({
                    value: eq.id,
                    label: eq.title,
                })));
            } catch (error) {
                console.error('Ошибка получения типов оборудования:', error);
            }
        };

        onMounted(fetchItems);

        return {
            item,
            equipmentTypes,
            addItem,
            successMessage,
            errorEquipTypeMessage,
            errorSNMessage,
            errors,
        };
    }
}
</script>


<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}
</style>
