<template>
    <div class="container">
        <div class="row">   
            <div class="col">
                <form action="POST">
                    <div class="d-flex">
                        <input type="text" class="form-control" v-model="query" placeholder="search">
                    <button class="btn btn-primary ml-3" @click.prevent="search">Go</button>
                    </div>
                </form>
                <div class="row">
                    <div class="col-3 d-flex px-4 flex-column my-2" v-for="item in items">
                        <div class="img-wrap">
                            <div class="item-image" v-bind:style="{ 'background-image': 'url(' + item.img_url + ')' }"></div>
                        </div>
                        <span>{{ item.name }}</span>
                        <span class="mt-auto mb-2 text-center">{{item.price}}</span>
                        <button class="btn btn-block btn-danger">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>        
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                query: '',
                items: []
            }
        },
         methods: {
            search() {
                let self = this;
                axios.post('/api/v1/search',{
                    query:this.query
                }).then(function(response){
                    console.log(response)
                    self.items = response.data;

                })
                console.log(this.query)
            }
        }
    }
</script>

<style>
    .img-wrap {
        width: 100%;
        height: 200px;
    }
    .item-image {
        background-repeat: no-repeat;
        width: 100%;
        height: 100%;
        background-position: center;
        background-size: contain;
    }
</style>