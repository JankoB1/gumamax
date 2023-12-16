/**
 * Created by nikola on 30.9.2016.
 */
var apiHealth1 = new Vue({
    el: '#merchant-health-1',

    data: {
        health : {time_elapsed:'...', error:null}
    },

    mounted: function() {
        var self = this;
//        this.fetchEvents();
        setInterval(function(){self.fetchEvents()}, 5000);
    },

    methods: {

        fetchEvents: function() {
            var self=this;
            // GET /someUrl
            this.$http.get(merchantHealthUrl).then((response) => {
                self.health.error = response.body.error;
                self.health.time_elapsed=response.body.time_elapsed;
            }, (response) => {
                // error callback
            });
        }
    }
});


var productCount = new Vue({
    el:'#product-count',
    data:{count: '...'},
    mounted: function() {
        var self = this;
        this.fetchData();
        setInterval(function(){self.fetchData()}, 5000);
    },
    methods: {
        fetchData: function() {
            var self=this;
            this.$http.get(productCountUrl).then((response) => {
                self.count = response.body.count;
            }, (response) => {
                // error callback
            });
        }
    }
});

var callbackRequestCount = new Vue({
    el:'#callback-request-count',
    data:{count: '...'},
    mounted: function() {
        var self = this;
        this.fetchData();
        setInterval(function(){self.fetchData()}, 5000);
    },
    methods: {
        fetchData: function() {
            var self=this;
            this.$http.get(callbackRequestCountUrl).then((response) => {
                self.count = response.body.count;
            }, (response) => {
                // error callback
            });
        }
    }
});

var ordersCount = new Vue({
    el:'#orders-count',
    data:{count: '...'},
    mounted: function() {
        var self = this;
        this.fetchData();
        setInterval(function(){self.fetchData()}, 5000);
    },
    methods: {
        fetchData: function() {
            var self=this;
            this.$http.get(ordersCountUrl).then((response) => {
                self.count = response.body.count;
            }, (response) => {
                // error callback
            });
        }
    }
});

var contactFormMessages = new Vue({
    el:'#contact-form-message',
    data:{count: '...'},
    mounted: function() {
        var self = this;
        this.fetchData();
        setInterval(function(){self.fetchData()}, 5000);
    },
    methods: {
        fetchData: function() {
            var self=this;
            this.$http.get(contactFormMessagesCountUrl).then((response) => {
                self.count = response.body.count;
            }, (response) => {
                // error callback
            });
        }
    }
});


