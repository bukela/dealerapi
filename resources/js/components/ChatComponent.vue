<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header text-center"><h3>Chat Test</h3></div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" v-for="message in messages" :key="message.id">{{ message }}</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type message...">
                            <div class="input-group-append">
                            <button class="btn btn-outline-secondary" @click="sendMessage">SEND</button>
                            </div>
                        </div>
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
                messages: [],
                message: '',
                newMessage: ''
            }
        },
        created() {
             axios.get('/api/messages').then(response => {
                this.messages = response.data
                },

            axios.get('/api/user').then(response => {
                    console.log(response);
                })
             );

            //  window.Echo.channel('analyst-channel')
            // //  window.Echo.channel('chat-'+localStorage.getItem('user'))
            //         .listen('MessageSent', e => {
            //         this.messages.push(e.message.body);
            //         // console.log(e.message);
            //     });
        },
        methods: {
            sendMessage() {

                if(this.newMessage.length < 1) {

                    alert('Message field is empty');

                } else {

                    axios.post('api/message', { body: this.newMessage });

                    this.messages.push(this.newMessage);

                    this.newMessage = '';

                }
                
            }
        }
    }
</script>
