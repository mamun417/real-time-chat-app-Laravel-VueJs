<template>
    <div class="row">
        <div class="col-8">
            <div class="card card-default">
                <div class="card-header">Messages</div>
                <div class="card-body p-0">
                    <ul class="list-unstyled" style="height:300px; overflow-y:scroll" v-chat-scroll>
                        <li class="p-2" v-for="(message, index) in messages" :key="index">
                            <strong>{{ message.user.name }}</strong>
                            {{ message.message }}
                        </li>
                    </ul>
                </div>

                <input
                    @keydown="sendTypingEvent"
                    @keyup.enter="sendMessage"
                    v-model="newMessage"
                    type="text"
                    name="message"
                    placeholder="Enter your message..."
                    class="form-control">
            </div>
            <span class="text-muted" v-if="activeUser">{{ activeUser.name }} is typing...</span>
        </div>

        <div class="col-4">
            <div class="card card-default">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(user, index) in users" :key="index">
                            {{ user.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Chat',
    data() {
        return {
            messages: [],
            users: [],
            newMessage: '',
            activeUser: false,
            typingTimer: '',
        }
    },

    props: {
        user: '',
    },

    created() {
        this.fetchMessages()

        Echo.join('chat')
            .here((users) => {
                this.users = users;
                console.log(users);
            })
            .joining((user) => {
                console.log(user.name);
                this.users.push(user);
            })
            .leaving((user) => {
                console.log(user.name);
                this.users = this.users.filter(u => u.id !== user.id);
            })
            .listen('MessageSentEvent', (e) => {
                this.messages.push(e.message)
            })
            .listenForWhisper('typing', user => {
                clearTimeout(this.typingTimer)

                this.activeUser = user

                this.typingTimer = setTimeout(() => {
                    this.activeUser = false
                }, 3000)
            })
            .error((error) => {
                console.error(error);
            });
    },

    methods: {
        fetchMessages() {
            axios.get('messages')
                .then(res => {
                    this.messages = res.data
                })
                .catch(err => {
                    console.log(err)
                })
        },

        sendMessage() {
            axios.post('messages', {
                message: this.newMessage
            })
                .then(res => {
                    this.messages.push({
                        user: this.user,
                        message: this.newMessage
                    });

                    this.newMessage = ''
                })
                .catch(err => {
                    console.log(err)
                })
        },

        sendTypingEvent() {
            Echo.join('chat')
                .whisper('typing', this.user);
        }
    }
}
</script>
