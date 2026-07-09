<?php include 'header.php'; ?>

<style>
/* মূল ড্যাশবোর্ড কার্ড স্টাইল */
.order-card {
    color: #fff;
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px !important;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    min-height: 120px;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
}

/* প্রতিটি কার্ডের জন্য আলাদা আকর্ষণীয় গ্রেডিয়েন্ট */
.bg-c-blue { background: linear-gradient(45deg, #4099ff, #73b4ff) !important; }
.bg-c-green { background: linear-gradient(45deg, #2ed8b6, #59e0c5) !important; }
.bg-c-yellow { background: linear-gradient(45deg, #FFB64D, #ffcb80) !important; }
.bg-c-pink { background: linear-gradient(45deg, #FF5370, #ff869a) !important; }
.bg-c-purple { background: linear-gradient(45deg, #62d1f3, #62a8ea) !important; }
.bg-c-dark { background: linear-gradient(45deg, #490273, #a203ff) !important; }

.card {
    border-radius: 15px;
    margin-bottom: 25px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.card .card-block {
    padding: 20px;
}

/* বাম পাশে টাইটেল এবং কাউন্টিং */
.card-left-content {
    float: left;
    text-align: left;
}

.m-b-10 {
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    opacity: 0.9;
}

.counter-num {
    font-size: 28px;
    font-weight: 700;
}

/* ডান পাশে আইকন স্টাইল */
.card-icon-right {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 45px;
    opacity: 0.3; /* হালকা করে দেখা যাবে, দেখতে প্রিমিয়াম লাগে */
    transition: all 0.3s ease;
}

.order-card:hover .card-icon-right {
    opacity: 0.7;
    font-size: 50px;
}

/* ক্লিয়ার ফিক্স */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

/* চার্ট এরিয়া */
#chart {
    background: #fff;
    padding: 15px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <a href="/admin/clients" style="text-decoration: none;">
                <div class="card bg-c-blue order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">USERS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"clients"]) ?></h2>
                        </div>
                        <i class="fa fa-users card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="/admin/orders" style="text-decoration: none;">
                <div class="card bg-c-green order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">TOTAL ORDERS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"orders"]) ?></h2>
                        </div>
                        <i class="fa fa-shopping-cart card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="/admin/refill" style="text-decoration: none;">
                <div class="card bg-c-pink order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">REFILL ORDERS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"refill_status"] ) ?></h2>
                        </div>
                        <i class="fa fa-refresh card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="/admin/orders/1/all?mode=apiorders" style="text-decoration: none;">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">API ORDERS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"orders","where"=>["order_where"=>'api'] ]) ?></h2>
                        </div>
                        <i class="fa fa-exchange card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-xl-3">
            <a href="/admin/orders/1/all?mode=manuel" style="text-decoration: none;">
                <div class="card bg-c-dark order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">MANUAL ORDERS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"orders","where"=>["api_orderid"=>0] ]) ?></h2>
                        </div>
                        <i class="fa fa-hand-paper-o card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="/admin/tickets?search=unread" style="text-decoration: none;">
                <div class="card bg-c-pink order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">UNREAD TICKETS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"tickets","where"=>["client_new"=>2] ]) ?></h2>
                        </div>
                        <i class="fa fa-envelope-open card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="/admin/payments" style="text-decoration: none;">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">FAILED ORDERS</p>
                            <h2 class="counter-num"><?php echo $failCount ?></h2>
                        </div>
                        <i class="fa fa-warning card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="/admin/manager" style="text-decoration: none;">
                <div class="card bg-c-blue order-card">
                    <div class="card-block clearfix">
                        <div class="card-left-content">
                            <p class="m-b-10">ADMINS</p>
                            <h2 class="counter-num"><?php echo countRow(["table"=>"admins"]) ?></h2>
                        </div>
                        <i class="fa fa-user-secret card-icon-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mg-t-30 mg-b-30">
            <div id="chart"></div>
        </div>
    </div>
</div>

<script>
    var options = {
        title: { text: 'MONTHLY ORDERS REPORT', align: 'left', style: { fontSize: '16px', color: '#444' } },
        fill: { colors: ['#490273'] },
        colors:['#490273'],
        series: [{
            name: 'Daily Orders',
            data: [<?php for ($day=1; $day <=31; $day++): echo dayOrders($day,date('m'),date("Y")).','; endfor; ?>]
        }],
        chart: { height: 350, type: 'area', toolbar: {show: false} },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            type: 'datetime',
            categories: [<?php for($d=1; $d<=31; $d++) { echo '"'.date('Y-m-').sprintf("%02d", $d).'",'; } ?>]
        },
        tooltip: { x: { format: 'dd MMM' } },
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

<?php include 'footer.php'; ?>
