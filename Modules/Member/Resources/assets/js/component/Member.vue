<template>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <Card 
                :title="stats.money.title" 
                :value="stats.money.value" 
                :percentage="stats.money.percentage" 
                :icon-class="stats.money.iconClass" 
                :icon-background="stats.iconBackground" 
                direction-reverse>
            </Card>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <card
                :title="stats.users.title"
                :value="stats.users.value"
                :percentage="stats.users.percentage"
                :icon-class="stats.users.iconClass"
                :icon-background="stats.iconBackground"
                direction-reverse>
            </card>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <card
                :title="stats.clients.title"
                :value="stats.clients.value"
                :percentage="stats.clients.percentage"
                :icon-class="stats.clients.iconClass"
                :icon-background="stats.iconBackground"
                :percentage-color="stats.clients.percentageColor"
                direction-reverse>
            </card>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0">
            <card
                :title="stats.sales.title"
                :value="stats.sales.value"
                :percentage="stats.sales.percentage"
                :icon-class="stats.sales.iconClass"
                :icon-background="stats.iconBackground"
                direction-reverse>
            </card>
        </div>
    </div>

    <UploadImportFileMember></UploadImportFileMember>
    <ListMember
        :members="members" 
        :params="params" 
        :loading="loadingChild"
        :pagination="pagination"
        @sort-data="sortData"
        @goto-page="gotoPage">
    </ListMember>
</template>

<script>
export default {
    name: "Member",
    data() {
        return {
            stats: {
                iconBackground: "bg-gradient-success",
                money: {
                    title: "Today's Money",
                    value: "$53,000",
                    percentage: "+55%",
                    iconClass: "ni ni-money-coins",
                },
                users: {
                    title: "Today's Users",
                    value: "2,300",
                    percentage: "+3%",
                    iconClass: "ni ni-world",
                },
                clients: {
                    title: "New Clients",
                    value: "+3,462",
                    percentage: "-2%",
                    iconClass: "ni ni-paper-diploma",
                    percentageColor: "text-danger",
                },
                sales: {
                    title: "Sales",
                    value: "$103,430",
                    percentage: "+5%",
                    iconClass: "ni ni-cart",
                },
            },
            members: [],
            params: {
                page: 1,
                sortField: 'member_name',
                sortType: 'asc',
                searchField: ''
            },
            searchFieldName: '',
            loadingChild: false,
            pagination: {}
        }
    },
    mounted() {
        this.getMember();
    },
    methods: {
        async getMember() {
            try {
                const member = await axios.get('api/member', {params: this.params});
                this.pagination = member.data;
                setTimeout(() => {
                    this.members = member.data.data;
                    this.loadingChild = true;
                }, 50);
            } catch (error) {
                console.log(error);
            }
        },
        sortData(sortField) {
            if (!this.members.length) {
                return false;
            }

            if (this.params.sortField !== sortField) {
                this.params.sortField = sortField;
                this.params.sortType = 'asc';
            } else {
                this.params.sortType = this.params.sortType === 'asc' ? 'desc' : 'asc';
            }
            this.$forceUpdate();
            this.getMember();
        },
        gotoPage(page) {
            this.params.page = page;
            this.pagination.current_page = page;
            this.$forceUpdate();
            this.getMember();
        },
        searchName() {
            this.params.searchField = this.searchFieldName;
            this.getMember();
        }
    }
}
</script>