<template>
        <div class="modal-mask" v-if="show" >
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-headers">
                        <slot name="header">
                            <button class="btn modal-cancel-button" @click="closeModal">Х</button>
                        </slot>
                    </div>
                    <div class="modal-body">
                        <span>
                            {{this.advert_id}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
export default {
    name: "ShowComponent",
    data:()=>{
        return{
            data:[],
            show:false,
        }
    },
    props:['advert_id'],
    created: function() {
        document.addEventListener("keydown", (event)=> {
            if (event.key === 'Escape') {
                this.closeModal();
            }
        });
    },
    methods:{
        getData()
        {
            axios.get(`/api/V1/advert/${this.advert_id}`)
                .then(res => {
                    this.data = res.data
                    console.log(this.data);
                })
                .catch(error => {
                    console.log(error);
                })
        },
        closeModal: function () {
            this.show = false;
            console.log(this.data);
        }
    },
}
</script>

<style >
.modal-mask{
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.45);
    z-index: 999;
}
.modal-wrapper {
    max-width: 35%;
    width: 35%;
    max-height:70%;
    background-color: #fff;
    padding: 20px;
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: auto;
}
.modal-headers {
    text-align: right;
    font-size: 22px;
    letter-spacing: 0;
    line-height: 18px;
    font-weight: bold;
    margin-bottom:10px;
}
</style>
