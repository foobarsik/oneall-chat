<template>
    <div v-if="chat.id" class="messages" id="messages">
        <infinite-loading spinner="waveDots" direction="top" :identifier="chat.id" @infinite="infiniteHandler">
            <div slot="no-more"><span></span></div>
            <div slot="no-results"><span></span></div>
        </infinite-loading>
        <ul>
            <li v-for="(message, index) in messages"
                :class="{'sent': isMessageIncoming(message), 'replies': !isMessageIncoming(message)}">
            <span v-if="!isMessageChainWrittenSameDay(index)" class="message_date">
                <span>{{ formatDate(message.time)}}</span>
            </span>
                <p>
                    <span class="message_content" v-html="formatMessage(message.text)"></span>
                    <span class="message_time"> {{ formatTime(message.time) }} </span>
                </p>
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from 'moment';
    import InfiniteLoading from 'vue-infinite-loading';

    export default {
        components: {
            InfiniteLoading
        },
        props: ['messages', 'chat'],
        methods: {
            infiniteHandler($state) {
                axios.get('/api/v1/chats/' + this.chat.id + '/messages', {params: {page: this.chat.page}})
                    .then(({data}) => {
                        if (data.data.length) {
                            this.chat.page += 1;
                            this.messages.unshift(...data.data.reverse());
                            $state.loaded();
                        } else {
                            $state.complete();
                        }
                    })
                    .catch(error => alert(error.response.data.message));
            },
            isMessageIncoming(message) {
                return message.externalId;
            },
            isMessageChainWrittenSameDay(index) {
                if (index === 0) {
                    return false;
                }
                let prevMessage = this.messages[index - 1];
                let currentMessage = this.messages[index];
                return this.isSameDay(prevMessage.time, currentMessage.time);
            },
            isSameDay(time1, time2) {
                return moment(time1).day() === moment(time2).day();
            },
            formatDate(time) {
                return moment(String(time)).format('DD.MM.YYYY');
            },
            formatTime(time) {
                return moment(String(time)).format('HH:mm');
            },
            formatMessage(message) {
                return message.replace(/(?:\r\n|\r|\n)/g, '<br>');
            }
        }
    };
</script>

<style scoped>
    p {
        margin: 0;
    }

    .messages {
        height: calc(100% - 160px);
        overflow-y: scroll;
        overflow-x: hidden;
    }

    @media screen and (max-width: 735px) {
        .messages {
            max-height: calc(100% - 180px);
        }
    }

    .messages::-webkit-scrollbar {
        width: 8px;
        background: transparent;
    }

    .messages::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.3);
    }

    .messages ul li {
        display: inline-block;
        clear: both;
        float: left;
        margin: 0 0 5px 15px;
        width: 100%;
        font-size: 0.96em;
    }

    .messages ul li.sent p {
        background: #435f7a;
        color: #f5f5f5;
    }

    .messages ul li.replies p {
        background: #fff;
    }

    .messages ul li p {
        display: inline-block;
        padding: 7px 15px;
        border-radius: 15px;
        max-width: 205px;
        min-width: 70px;
        line-height: 130%;
    }

    @media screen and (min-width: 735px) {
        .messages ul li p {
            max-width: 349px;
        }
    }

    .message_date {
        text-align: center;
        display: block;
        margin-top: 5px;
        margin-bottom: 10px;
    }

    .message_date > span {
        background: #e6eaea;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .message_time {
        font-size: 12px;
        margin-top: 5px;
        display: block;
        float: right;
        margin-left: 10px;
    }

    .message_content {
        word-break: break-word;
    }
</style>
