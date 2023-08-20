var vmUserOrdres = new Vue({
    el: '#user_orders_table',
    data: {
        orders_list: []
    },
    mounted: function () {
        this.fetchData();
    },
    methods: {
        fetchData: function () {
            var self = this;
            this.$http.get(apiUserOrdersUrl).then((response) => {
                self.orders_list = response.body;
            }, (response)=>{
                // error callback
            });
        }
    }
});

