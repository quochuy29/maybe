<template>
    <div v-if="loading"  class="main__content">
        <div class="user__list">
            <div class="list-info__user">
                <div class="info__user__name user__list__info cursor-info"
                @click="sortData('member_name')"
                :class="{ selected: (members.length > 0) && 
                (params.sortField == 'member_name') }">
                    User name
                    <span class="icon icon__arw__sort"
                    :class="{ascending: (members.length > 0) &&
                    (params.sortType == 'asc') &&
                    (params.sortField == 'member_name'),
                    descending: (members.length > 0) &&
                    (params.sortField == 'member_name') &&
                    (params.sortType == 'desc')}">
                        <svg viewBox="0 0 8 12">
                            <g transform="translate(-391.609 -103)">
                                <path d="M4,0,8,5H0Z" transform="translate(391.609 103)" class="upper"></path>
                                <path d="M4,0,8,5H0Z" transform="translate(399.609 115) rotate(180)" class="lower"></path>
                            </g>
                        </svg>
                    </span>
                </div>
                <div class="info__user__mail user__list__info cursor-info"
                @click="sortData('member_email')"
                :class="{ selected: (members.length > 0) && 
                (params.sortField == 'member_email') }">
                    User email
                    <span class="icon icon__arw__sort"
                    :class="{ascending: (members.length > 0) &&
                    (params.sortType == 'asc') &&
                    (params.sortField == 'member_email'),
                    descending: (members.length > 0) &&
                    (params.sortField == 'member_email') &&
                    (params.sortType == 'desc')}">
                        <svg viewBox="0 0 8 12">
                            <g transform="translate(-391.609 -103)">
                                <path d="M4,0,8,5H0Z" transform="translate(391.609 103)" class="upper"></path>
                                <path d="M4,0,8,5H0Z" transform="translate(399.609 115) rotate(180)" class="lower"></path>
                            </g>
                        </svg>
                    </span>
                </div>
                <div class="info__user__phone user__list__info cursor-info"
                @click="sortData('member_phone_mobile')"
                :class="{ selected: (members.length > 0) && 
                (params.sortField == 'member_phone_mobile') }">
                    User phone
                    <span class="icon icon__arw__sort"
                    :class="{ascending: (members.length > 0) &&
                    (params.sortType == 'asc') &&
                    (params.sortField == 'member_phone_mobile'),
                    descending: (members.length > 0) &&
                    (params.sortField == 'member_phone_mobile') &&
                    (params.sortType == 'desc')}">
                        <svg viewBox="0 0 8 12">
                            <g transform="translate(-391.609 -103)">
                                <path d="M4,0,8,5H0Z" transform="translate(391.609 103)" class="upper"></path>
                                <path d="M4,0,8,5H0Z" transform="translate(399.609 115) rotate(180)" class="lower"></path>
                            </g>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="user__list-info">
                <router-link :to="{name:'index'}" class="user__info" v-for="(user, index) in members" :key="index">
                    <div class="user-name user__list__info">
                        {{ user.member_name }}
                    </div>
                    <div class="user-mail user__list__info">
                        {{ user.member_email }}
                    </div>
                    <div class="user-phone user__list__info">
                        {{ user.member_phone_number}}
                    </div>
                    <span class="icon icon__arw__right">
                        <svg viewBox="0 0 8.166 14">
                            <path d="M7,168.166a1.162,1.162,0,0,1-.825-.342L.347,161.991A1.166,1.166,0,0,1,2,160.342L7,165.352l5.009-5.009a1.166,1.166,0,0,1,1.65,1.65L7.83,167.825A1.163,1.163,0,0,1,7,168.166Z" transform="translate(-160 14.005) rotate(-90)"></path>
                        </svg>
                    </span>
                </router-link>
            </div>
        </div>
        <Pagination v-if="members && members.length > 0" :pagination="pagination" @goto-page="gotoPage"></Pagination>
    </div>
</template>

<script>
    export default {
        name: "ListMember",
        props: {
            members: {
                type: Array,
                default: []
            },
            params: {
                type: Object,
                default: {}
            },
            loading: {
                type: Boolean,
                default: false
            },
            pagination: {
                type: Object,
                default: {}
            }
        },
        methods: {
            sortData(sortField) {
                this.$emit('sort-data', sortField);
            },
            gotoPage(page) {
                this.$emit('goto-page', page);
            }
        },
    }
</script>