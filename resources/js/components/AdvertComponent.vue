<template>
    <div class="container p-4">
        <div class="mx-auto p-4" style="width:50%;">
            <form @submit.prevent="addData">
                <div class="form-group">
                    <label>Title</label>
                    <input v-model="form.title"
                           class="form-control"
                           placeholder="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input v-model="form.description"
                           class="form-control" type="tel"
                           placeholder="description" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input  v-model="form.price"
                            class="form-control" type="number"
                            placeholder="price" required>
                </div>
                <div class="form-group">
                    <label>Изображение</label>
                    <div class="row" v-if="form.imgPaths.length < 3">
                        <div class="col-md">
                            <input type="text" v-model="urlImg"
                                    class="form-control form-control"
                                    placeholder="Image Link">
                        </div>
                        <div class="col-md">
                            <button @click="addUrl"
                                    type="button"
                                    class="btn btn-sm btn-outline-success">
                                Добавить
                            </button>
                        </div>
                    </div>
                    <div class="row pt-4" v-if="form.imgPaths.length !== 0">
                        <div class="col-md">
                            <ul v-for="(item,index) in form.imgPaths" :key="index" class="list-group list-group-flush">
                                <li class="list-group-item">
                                    {{item}}
                                    <button type="button" @click="deleteUrl(index)"
                                            class="btn btn-sm btn-outline-danger">
                                        Удалить
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary">Отправить</button>
            </form>
        </div>
        <div class="row">
            <div class="col-9">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Название</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Главная картинка</th>
                        <th>
                            <select name="" id="" v-model="statBy" @change="sortChanged" class="form-control">
                                <option value="0">Выберите тип сортировки</option>
                                <option value="1">Сортировка по дате &#8595;</option>
                                <option value="2">Сортировка по цене &#8595;</option>
                                <option value="3">Сортировка по дате &#8593;</option>
                                <option value="4">Сортировка по цене &#8593;</option>
                            </select>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="el in data.data" :key="el.id">
                        <td>{{el.title}}</td>
                        <td>{{el.price}}</td>
                        <td v-show="!el.first_image">
                            <span>
                                Не назначено
                            </span>
                        </td>
                        <td v-for="image in el.first_image">
                            <span>
                                {{image}}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" @click="showOpen(el.id)">
                                Показать
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-3">
                <ul class="list-group" v-for="item in show">
                    <li class="list-group-item">
                        <span class="text-primary">Название - </span>
                        {{item.title}}
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Цена - </span>
                        {{item.price}}
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Главное фото - </span>
                        <span v-if="!item.first_image">
                            Не назначено
                        </span>
                        <span v-for="image in item.first_image">
                            {{image}}
                        </span>
                    </li>
                    <li v-if="isFieldShow" class="list-group-item">
                        <span class="text-primary">Описание - </span>
                        {{item.description}}
                    </li>
                    <li v-if="isFieldShow" class="list-group-item">
                        <span class="text-primary">Список фотографий - </span>
                        <span v-if="!item.images.length">
                            Не назначено
                        </span>
                        <ul v-for="el in item.images" class="list-group">
                            <li class="list-group-item">
                                <span>
                                    {{el.path}}
                                </span>
                            </li>
                        </ul>
                    </li>
                    <button v-if="!isFieldShow" type="button" class="btn btn-outline-primary" @click="showOpenFields">
                        Показать доп поля
                    </button>
                    <button v-if="isFieldShow" @click="isFieldShow=false" type="button" class="btn btn-outline-danger">
                        Скрыть
                    </button>
                </ul>
            </div>
        </div>
        <pagination align="center" :data="data" @pagination-change-page="getData"></pagination>
    </div>
</template>

<script>
import LaravelVuePagination from 'laravel-vue-pagination';
import ShowComponent from "./ShowComponent";
export default {
    name: "AdvertComponent",
    data:()=>{
        return{
            data:[],
            urlImg:'',
            show:[],
            fields:[],
            advert_id:[],
            isFieldShow:false,
            form:{
                title: '',
                description: '',
                price: '',
                imgPaths: []
            },
            sort: {
                sortDesc:false,
                sortBy:''
            },
            statBy:0,
        }
    },
    computed: {
       fieldsQuery() {
            return this.fields.length
                ? this.fields.map((item) => `fields[]=${encodeURIComponent(item)}`).join("&")
                : ''
        }
    },
    components: {
        'Pagination': LaravelVuePagination,
        ShowComponent,
    },
    mounted() {
        this.getData()
    },
    methods: {
        addUrl() {
            this.urlImg.trim() !== '' ? this.form.imgPaths.push(this.urlImg)
                : this.$fire({
                title: "Ошибка",
                text: "Неверная ссылка",
                type: "warning",
                timer: 3000
            })
            this.urlImg = ''
        },
        deleteUrl(index) {
            this.form.imgPaths.splice(index, 1)
        },
        getData(page = 1) {
            let sort = this.sort.sortBy ? `&sort[${this.sort.sortBy}]=${this.sort.sortDesc ? 'desc' : 'asc'}` : '';
            axios.get(`/api/V1/advert?page=${page}` + sort)
                .then(res => {
                    this.data = res.data
                    console.log(res.data.data)
                })
                .catch(error => {
                    console.log(error);
                })
        },
        addData() {
            axios.post('/api/V1/advert', {
                title: this.form.title,
                description: this.form.description,
                price: this.form.price,
                imgPaths: this.form.imgPaths,
            })
                .then(res => {
                    this.form.title = ''
                    this.form.description = ''
                    this.form.price = ''
                    this.form.imgPaths = []
                    this.getData();
                    console.log(res);
                    this.$fire({
                        title: "Успешно",
                        text: "Успешный успех тестового:)",
                        type: "success",
                        timer: 3000
                    })
                })
                .catch(error => console.log(error))
        },
        showOpen(id){
            this.advert_id = id;
            axios.get(`/api/V1/advert/${id}?${this.fieldsQuery}`)
                .then(res => {
                    this.show = res.data
                    console.log(res.data)
                })
                .catch(error => {
                    console.log(error);
                })
        },
        showOpenFields()
        {
            this.isFieldShow = true;
            this.fields = ['description','image'];
            this.showOpen(this.advert_id);
        },
        sortChanged()
        {
            //Нужно изменить !
            if(this.statBy == 1){
                this.sort.sortBy = 'created_at'
                this.sort.sortDesc = true
                this.getData()
            }
            if(this.statBy == 2){
                this.sort.sortBy = 'price'
                this.sort.sortDesc = true
                this.getData()
            }
            if(this.statBy == 3){
                this.sort.sortBy = 'created_at'
                this.sort.sortDesc = false
                this.getData()
            }
            if(this.statBy == 4){
                this.sort.sortBy = 'price'
                this.sort.sortDesc = false
                this.getData()
            }
        }
    }

}
</script>

<style scoped lang="scss">

</style>
