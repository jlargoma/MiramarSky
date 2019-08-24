(function (Vue, axios, moment) {
    var configAxios = {
        url: '/api-forfait',
        method: 'get',
        responseType: 'json',
        data: {},
        headers: {
            'Content-Type': 'application/json',
        },
        withCredentials: false
    };
    
    
      // Initialize as global component
  Vue.component('date-picker', VueBootstrapDatetimePicker);
  
    Vue.component('button-counter', {
      data: function () {
        return {
          date: new Date(),
        options: {
          format: 'DD/MM/YYYY',
          useCurrent: false,
        },   
          count: 0
        }
      },
      template: '<div class="container"><div class="row"> <div class="col-md-12"><date-picker v-model="date" :config="options"></date-picker></div></div></div>'
    });
    
    new Vue({
        el: '#app',
        data: {
          date_start:null,
          date_end:null,
          options: {
            format: 'DD/MM/YYYY',
            useCurrent: false,
          },   
          users:[]
        },
        filters: {
            formatDate: function (date, outputFormat) {
                return moment(date).format(outputFormat);
            }
        },
        mounted: function () {
        },
        methods: {
            
            
        }
    });

    Vue.filter('timer', function (d) {
        d = Number(d);
        var h = Math.floor(d / 3600);
        var m = Math.floor(d % 3600 / 60);
        var s = Math.floor(d % 3600 % 60);

        var hDisplay = h > 0 ? h + ":" : "00:";
        var mDisplay = m > 0 ? (m > 9 ? m : "0"+m) + ":" : "00:";
        var sDisplay = s;
        return hDisplay + mDisplay + sDisplay; 
        }
    );


    

})(window.Vue, window.axios, window.moment);