<?php include 'includes/head.php'?>
<?php include 'includes/header.php'?>


<!-- Main Start -->
<main>
    <section class="breadcrumb-wrapper py-4 border-top">    
        <div class="container-xxl">
            <ul class="breadcrumbs">
                <li><a href="javascript:void();">Home</a></li>
                <li>Account</li>
            </ul>
        </div>
    </section>
    <section class="furniture_myaccount_wrap pt-4">
        <div class="container flow-rootX3">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="fw-normal mt-0 font45 c--blackc">Account</h1>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="my_account_wrap">
                        <?php include 'includes/profile_sidebar.php'?>
                        <div class="right_content">
                            <div class="profile_details overflow-hidden border flow-rootX3 h-100">
                                <div class="heading border-bottom pb-4">
                                    <h2 class="font25 fw-medium m-0 c--blackc">Reward</h2>
                                </div>
                                <div class="info flow-rootX2">
                                    <div class="reward_box">
                                        <div class="left_side">
                                            <div class="icon"><span class="material-symbols-outlined c--whitec font45">account_balance_wallet</span></div>
                                            <div class="txt">
                                                <h4 class="font25 fw-normal c--blackc mb-1">$5412.00</h4>
                                                <p class="mb-0 c--gry font18">Updated few mins ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="transaction_history">
                                        <h4 class="font20 fw-normal mb-4 c--blackc">Transaction History</h4>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Amount ($)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>March 24, 2025</td>
                                                    <td>Cashback</td>
                                                    <td><span class="c--success">+60.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td>March 24, 2025</td>
                                                    <td>Refund</td>
                                                    <td><span class="c--success">+1002.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td>March 24, 2025</td>
                                                    <td>Order #12198</td>
                                                    <td><span class="c--error">-1002.00</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="Reward_offer">
                                        <h4 class="font20 fw-normal mb-4 c--blackc">Reward Offers</h4>
                                        <ul class="Reward_offer_list">
                                            <li>Earn extra when you use your Reward!</li>
                                            <li>Get 5% cashback when you pay via Reward.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/disclaimer.php'?>
<?php include 'includes/footer.php'?>
<?php include 'includes/foot.php'?>