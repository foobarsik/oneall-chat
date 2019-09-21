<template>
    <div id="frame">
        <div id="sidepanel">
            <div id="search">
                <input type="text" v-model="search" placeholder="Search..."/>
            </div>
            <contacts :chats="filteredChats" v-on:active-chat-update="onActiveChatUpdate" :activeChat="activeChat">
            </contacts>
        </div>
        <div class="content">
            <div class="contact-profile card-header">
                <span>
                    {{ activeChat.name }} (via @{{activeChat.bot}})
                </span>
            </div>
            <messages :messages="messages" :chat="activeChat"></messages>
            <message-form :chat-id="activeChat.id"></message-form>
        </div>
    </div>
</template>

<script>
    import MessageForm from './MessageForm.vue';
    import Messages from './Messages.vue';
    import Contacts from './Contacts.vue';

    export default {
        components: {
            MessageForm,
            Messages,
            Contacts
        },
        data() {
            return {
                search: '',
                chats: [],
                activeChat: {},
                messages: []
            }
        },
        computed: {
            filteredChats() {
                return this.chats.filter(chat => {
                    return chat.name.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        },
        methods: {
            onActiveChatUpdate(chat) {
                this.messages = [];
                this.activeChat = chat;
            },
            scrollToChatBottom() {
                let element = document.getElementById('messages');
                element.scrollTop = element.scrollHeight - element.clientHeight;
            }
        },
        mounted() {
            var channel = Echo.channel('chat');
            channel.listen('.message-sent', (data) => {
                if (data.chat.isNew) {
                    this.chats.unshift(data.chat);
                } else {
                    let msg = data.message;
                    let targetChat;
                    this.chats.forEach(function (chat) {
                        if (chat.id == msg.chatId) {
                            targetChat = chat;
                        }
                    });
                    targetChat.lastMessage = msg;
                    if (targetChat.id === this.activeChat.id) {
                        this.messages.push(msg);
                    }
                }
                this.$nextTick(() => {
                    this.scrollToChatBottom();
                });
            });
        },
        created() {
            axios.get('/api/v1/chats').then(({data}) => {
                this.chats = data.data;
                this.chats[0].page = 1;
                this.activeChat = this.chats[0];
                this.$nextTick(() => {
                    this.scrollToChatBottom();
                });
            }).catch(error => alert(error.response.data.message));
        }
    }
</script>

<style>
    #frame {
        display: flex;
        width: 100%;
        max-width: 705px;
        height: 92vh;
        min-height: 300px;
        max-height: 720px;
        background: #f2f4f4;
    }

    @media screen and (max-width: 360px) {
        #frame {
            min-width: 100%;
            width: 100%;
            height: 100vh;
        }
    }

    #frame #sidepanel {
        float: left;
        min-width: 280px;
        max-width: 340px;
        width: 40%;
        height: 100%;
        background: #2c3e50;
        color: #f5f5f5;
        overflow: hidden;
        position: relative;
    }

    @media screen and (max-width: 735px) {
        #frame #sidepanel {
            width: 58px;
            min-width: 58px;
        }
    }

    #frame #sidepanel #search {
        border-top: 1px solid #32465a;
        border-bottom: 1px solid #32465a;
        font-weight: 300;
    }

    @media screen and (max-width: 735px) {
        #frame #sidepanel #search {
            display: none;
        }
    }

    #frame #sidepanel #search input {
        padding: 10px 0 10px 64px;
        width: 100%;
        border: none;
        background: #32465a;
        color: #f5f5f5;
        font-weight: 600;
    }

    #frame #sidepanel #search input:focus {
        outline: none;
        background: #435f7a;
    }

    #frame #sidepanel #search input::-webkit-input-placeholder {
        color: #f5f5f5;
    }

    #frame #sidepanel #search input::-moz-placeholder {
        color: #f5f5f5;
    }

    #frame #sidepanel #search input:-ms-input-placeholder {
        color: #f5f5f5;
    }

    #frame #sidepanel #search input:-moz-placeholder {
        color: #f5f5f5;
    }

    #frame #sidepanel #contacts {
        height: calc(100% - 177px);
        overflow-y: scroll;
        overflow-x: hidden;
    }

    #frame #sidepanel #bottom-bar button:focus {
        outline: none;
    }

    #frame .content {
        float: right;
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
</style>
