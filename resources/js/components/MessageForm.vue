<template>
    <div class="message-input">
        <div class="wrap">
            <form @submit.prevent="sendMessage">
                <textarea
                    @keydown.enter.exact.prevent
                    @keydown.enter.shift.exact="newline"
                    @keydown.enter.exact="sendMessage"
                    rows="2"
                    max-rows="6"
                    class="form-control"
                    v-model="text"
                    placeholder="Enter for sending, Shift+Enter for line break...">
            </textarea>
                <button type="submit" class="btn-primary" :disabled="!contentExists">{{ buttonCaption }}</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['chatId'],
        data() {
            return {
                text: '',
                buttonCaption: 'Send message'
            }
        },
        computed: {
            contentExists() {
                return this.text.trim().length > 0;
            }
        },
        methods: {
            newline() {
                this.value = `${this.value}\n`;
            },
            sendMessage(e) {
                this.buttonCaption = 'Sending...';
                axios.post('/api/v1/chats/' + this.chatId + '/messages', {text: this.text})
                    .then(({data}) => {
                        this.text = '';
                        this.buttonCaption = 'Send message';
                    })
                    .catch(error => alert(error.response.data.message));
            }
        }
    };
</script>

<style scoped>
    .message-input {
        position: absolute;
        bottom: 0;
        width: 100%;
        z-index: 99;
    }

    .message-input .wrap {
        position: relative;
    }

    .message-input .wrap textarea {
        float: left;
        border: none;
        width: 100%;
        padding: 8px;
        font-size: 1.1em;
        color: #32465a;
        resize: none;
        word-break: break-word;
    }

    textarea::-webkit-scrollbar {
        width: 6px;
        background: transparent;
    }

    textarea::-webkit-scrollbar-thumb {
        background-color: #a1a3a3;
    }

    @media screen and (max-width: 735px) {
        .message-input .wrap textarea {
            padding: 15px 32px 16px 8px;
        }
    }

    .message-input .wrap textarea:focus {
        outline: none;
    }
</style>
