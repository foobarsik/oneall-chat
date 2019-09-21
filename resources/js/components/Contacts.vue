<template>
    <ul id="contacts">
        <li v-for="(chat, index) in chats" @click="openChat(chat)" class="contact"
            :class="{ active : isChatActive(chat) }">
            <div class="wrap">
                <div class="avatar">
                    <div class="avatar_initials">{{ getInitials(chat) }}</div>
                </div>
                <div class="meta">
                    <div class="name">
                        {{chat.name}} ({{chat.messenger}})
                    </div>
                    <div class="preview">
                        {{ chat.lastMessage.text }}
                    </div>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['chats', 'activeChat'],
        methods: {
            openChat(chat) {
                if (this.isChatActive(chat)) {
                    return;
                }
                chat.page = 1;
                this.$emit('active-chat-update', chat);
            },
            getInitials(chat) {
                let name = chat.name.split(' ');
                let firstNameLetter = name[0][0];
                let lastNameLetter = name[1] ? name[1][0] : '';
                return firstNameLetter + lastNameLetter;
            },
            isChatActive(chat) {
                return this.activeChat.id === chat.id;
            }
        }
    };
</script>

<style scoped>
    #contacts {
        height: calc(100% - 177px);
        overflow-y: scroll;
        overflow-x: hidden;
    }

    @media screen and (max-width: 735px) {
        #contacts {
            height: calc(100% - 149px);
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #contacts::-webkit-scrollbar {
            display: none;
        }
    }

    #contacts::-webkit-scrollbar {
        width: 8px;
        background: #2c3e50;
    }

    #contacts::-webkit-scrollbar-thumb {
        background-color: #243140;
    }

    .contact {
        position: relative;
        padding: 10px 0;
        font-size: 0.9em;
        cursor: pointer;
    }

    @media screen and (max-width: 735px) {
        .contact {
            padding: 6px 0 46px 8px;
        }
    }

    .contact:hover {
        background: #32465a;
    }

    .contact.active {
        background: rgba(255, 255, 255, 0.2);
        border-right: 5px solid #435f7a;
    }

    .contact .wrap {
        width: 88%;
        margin: 0 auto;
        position: relative;
    }

    @media screen and (max-width: 735px) {
        .contact .wrap {
            width: 100%;
        }
    }

    .contact .wrap span {
        position: absolute;
        left: 0;
        margin: -2px 0 0 -2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 2px solid #2c3e50;
        background: #95a5a6;
        z-index: 999;
    }

    @media screen and (max-width: 735px) {
        .contact .wrap img {
            margin-right: 0;
        }
    }

    .contact .wrap .meta {
        padding: 5px 0 0 0;
    }

    @media screen and (max-width: 735px) {
        .contact .wrap .meta {
            display: none;
        }
    }

    .contact .wrap .meta .name {
        font-weight: 600;
    }

    .avatar {
        width: 40px;
        height: 40px;
        float: left;
        margin-right: 8px;
        margin-top: 2px;
        position: relative;
        overflow: hidden;
        border-radius: 25px;
        background: #fff;
        color: black;
    }

    .avatar_initials {
        font-size: 15px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: 600;
        text-transform: uppercase;
    }

    .contact .wrap .meta .preview {
        padding: 0 0 1px;
        font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        -moz-transition: 1s all ease;
        -o-transition: 1s all ease;
        -webkit-transition: 1s all ease;
        transition: 1s all ease;
    }
</style>
