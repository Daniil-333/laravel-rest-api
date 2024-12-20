<template>
    <div class="container mt-5">
        <h1 class="mb-4">Оборудование</h1>
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <div class="table-responsive">

            <div class="col-md-6 mb-4">
                <input type="text"
                       class="form-control"
                       v-model="input.search"
                       @input="fetchItems"
                       placeholder="Поиск по серийному номеру/наименованию"
                >
            </div>

            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Наименование</th>
                    <th scope="col" style="width: 20%;">Маска серийного номера</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in items" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.title }}</td>
                    <td>{{ item.mask }}</td>
                </tr>
                </tbody>
            </table>

            <Bootstrap5Pagination
                :data="paginateLinks"
                @pagination-change-page="fetchItems"
            />
        </div>
    </div>

</template>

<script>
import {onMounted, reactive, ref} from 'vue';
import { useRouter } from 'vue-router';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    components: [Bootstrap5Pagination],

    setup() {
        const input = reactive({ search: '' });
        const items = ref([]);
        const router = useRouter();

        const successMessage = ref('');
        const paginateLinks = ref([]);

        const fetchItems = async (page = 1) => {
            let uri = `https://ktelekom.loc/api/equipment-type?page=${page}`;

            if(input.search) uri += `&search=${input.search}`

            try {
                const response = await axios.get(uri);
                items.value = response.data.data;
                paginateLinks.value = response.data.meta;
            } catch (error) {
                console.error('Ошибка получения записей:', error);
            }
        };

        // const openDeleteConfirmation = (id) => {
        //     itemIdToDelete.value = id;
        //     const modalInstance = new bootstrap.Modal(deleteModal.value);
        //     modalInstance.show();
        // };
        //
        // const closeModal = () => {
        //     const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
        //     if (modalInstance) {
        //         modalInstance.hide();
        //     }
        // };
        //
        // const confirmDelete = async () => {
        //     if (itemIdToDelete.value !== null) {
        //         await deleteItem(itemIdToDelete.value);
        //         closeModal();
        //     }
        // };
        //
        // const deleteItem = async (id) => {
        //     try {
        //         const uri = `https://ktelekom.loc/api/equipment/${id}`;
        //         await axios.delete(uri);
        //         items.value = items.value.filter((item) => item.id !== id);
        //         successMessage.value = 'Оборудование успешно удалено!';
        //         setTimeout(() => {
        //             successMessage.value = '';
        //             router.push({ name: 'Index' });
        //         }, 1000);
        //     } catch (error) {
        //         console.error('Error deleting item:', error);
        //     }
        // };

        onMounted(fetchItems);

        return {
            items,
            successMessage,
            input,
            paginateLinks,
            fetchItems,
        };
    }
}
</script>

<style></style>
