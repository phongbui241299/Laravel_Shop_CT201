<template>
        <div class="container-fluid banner__component">
            <div class="row">
                <div class="col-12 col sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="banner__component--show bg-info">
                        <div class="number_render">
                            <h1>+ {{totalBillToDay}}</h1>
                            <p>Hóa Đơn Hôm Nay</p>
                        </div>
                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-12 col sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="banner__component--show bg-success">
                        <div class="number_render">
                            <h1 v-if="totalRevenueToDay">{{(totalRevenueToDay/1000).toFixed(3)}}</h1>
                            <h1 v-else="">0</h1>
                            <p>Doanh Thu Hôm Nay</p>
                        </div>
                        <i class="fa fa-line-chart" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-12 col sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="banner__component--show bg-warning">
                        <div class="number_render">
                            <h1 v-if="member">{{member}}</h1>
                            <h1 v-else="">0</h1>
                            <p>Thành Viên Mới</p>
                        </div>
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-12 col sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="banner__component--show bg-danger">
                        <div class="number_render">
                            <h1>0</h1>
                            <p>Trả Hàng</p>
                        </div>
                        <i class="fa fa-undo" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <canvas ref="canvas" width="900" height="400"></canvas>
            </div>
        </div>
</template>

<script>
    import { Line } from 'vue-chartjs';
    import {RESOURCE} from '../api';
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    export default {
        extends: Line,
        name: "dashboard-component",
        data(){
            return{
                revenue_day:'',
                loading: false,
                datacollection: {
                    //Data to be represented on x-axis
                    labels: Array(31).fill(null),
                    datasets: [{
                        label: 'Doanh thu',
                        backgroundColor: 'rgba(0, 0, 0, 0.1)',
                        borderColor:'rgb(75, 192, 192)',
                        pointBackgroundColor: 'white',
                        borderWidth: 2,
                        pointBorderColor: '#249EBF',
                        //Data to be represented on y-axis
                        data: [100, 20, 30, 50, 90, 10, 20, 40, 50, 35, 90, 100,12,12,50,12,14,15,16,89,200,12,45,78,78,98,52,85,96,30,31]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                },
                bill_today:[],
                member:'',
                revenueMonth:[],
            }
        },
        mounted () {
            this.listDay();
            this.countBillToDay();
            this.countMemBerToDay();
            this.get_revenue_one_day();
            // this.countRevenueMonth();
        },
        computed:{
            totalBillToDay(){
                return this.bill_today.length;
            },
            totalRevenueToDay(){
               return this.bill_today.reduce((total, item) => total + item.order_total, 0)
            },
            totalRevenueMonth(){
                return this.revenueMonth.reduce((total, item) => total + item.order_total, 0)
            }
        },
        methods:{
            countBillToDay(){
                axios.get(RESOURCE + '/orders/to-day')
                    .then(res => {
                        this.bill_today = res.data;
                    })
            },
            countMemBerToDay(){
                axios.get(RESOURCE + '/users/to-day')
                    .then(res => {
                        this.member = res.data.length;
                    })
            },
            // countRevenueMonth(){
            //     axios.get(RESOURCE + '/orders/revenue/month')
            //         .then(res => {
            //             this.revenueMonth =res.data;
            //         })
            // } ,
            async listDay(){
                const res = await axios.get(RESOURCE + '/orders/list-day');
                // console.log(res.data);
                this.datacollection.labels = res.data;
                this.loading = true;
            },
            async get_revenue_one_day(){
                axios.get(RESOURCE + '/orders/revenue-order')
                    .then(res => {
                        this.revenue_day =res.data;
                    })
            },
            async post_revenue_one_day(){
                // var item = this.revenue_day;
                this.loading = false;
                axios.post(RESOURCE + '/orders/revenue-order',{items:this.revenue_day})
                    .then(res => {

                    })
                setTimeout(() => this.loading = true, 2000);
            }
        },
        watch: {
            loading: function() {
                this.renderChart(this.datacollection, this.options);
            }
        }
    }
</script>

<style scoped>

</style>
