<?php
$endList=$form_data['end_list'];
?>
<style type="text/css">
    sheet__heading{padding:24px 6px}.sheet--padding{padding-top:36px;padding-bottom:36px}.sheet--padding-extra-small{padding-top:12px;padding-bottom:12px}.sheet--padding-small{padding-top:24px;padding-bottom:24px}@media (min-width:768px){.sheet__heading{padding:60px 36px}.sheet--padding{padding-top:48px;padding-bottom:48px}.sheet--padding-extra-small{padding-top:12px;padding-bottom:12px}.sheet--padding-small{padding-top:24px;padding-bottom:24px}.sheet--hero{padding-top:24px!important}}@media (min-width:992px){.sheet__heading{padding:108px 90px}.sheet--padding{padding-top:72px;padding-bottom:72px}.sheet--padding-extra-small{padding-top:12px;padding-bottom:12px}.sheet--padding-small{padding-top:36px;padding-bottom:36px}}
</style>
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"> <a href="/" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a></div>
    </div>
    <!--End-breadcrumbs-->
    <!--Action boxes-->
    <div class="container-fluid" >
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <?php if(in_array(2,$this->user_menu_list)){?>
                <li class="bg_lb"> <a href="index.php?action=Order&mode=toOrderPage&n=2_1"> <i class="icon-off"></i> 订单</a> </li>
                <?php }?>
                <?php if(in_array(3,$this->user_menu_list)){?>
                <li class="bg_ly span3" > <a href="index.php?action=Customer&mode=getCustomerList"> <i class="icon-calendar"></i><span class="label label-success"></span> 客户</a> </li>
                <?php }?>
                <?php if(in_array(4,$this->user_menu_list)){?>
                <li class="bg_lo"> <a href="index.php?action=Product&mode=getProductList"> <i class="icon-paste"></i> 产品</a> </li>
                <?php }?>
            </ul>
        </div>
        <!--<div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li class="bg_lb"><a href="interface.html"><i class="icon-group"></i>下属信息查询</a></li>
                <li class="bg_ls"><a href="interface.html"><i class="icon-group"></i>人员变动查询</a></li>
            </ul>
        </div>
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li class="bg_lg"><a href="interface.html"><i class="icon-tint"></i>下属绩效查询</a></li>
                <li class="bg_lo"><a href="interface.html"><i class="icon-tint"></i>个人绩效查询</a></li>
                <li class="bg_lb"><a href="interface.html"><i class="icon-tint"></i>绩效申诉</a></li>
            </ul>
        </div>-->
        <div class="row-fluid">
            <!--<div class="span6">
                <div class="widget-box widget-plain">
                    <div class="center">
                        <ul class="stat-boxes2">
                            <li>
                                <div class="left peity_bar_neutral"><span><span style="display: none;"><span style="display: none;"><span style="display: none;"><span style="display: none;">20,4,9,7,12,10,12</span><canvas width="50" height="24"></canvas></span>
              <canvas width="50" height="24"></canvas>
              </span><canvas width="50" height="24"></canvas></span><canvas width="50" height="24"></canvas></span>+10%</div>
                                <div class="right"> <strong>15598</strong> 订单数 </div>
                            </li>
                            <li>
                                <div class="left peity_line_neutral"><span><span style="display: none;"><span style="display: none;"><span style="display: none;"><span style="display: none;">10,15,8,14,13,10,10,15</span><canvas width="50" height="24"></canvas></span>
              <canvas width="50" height="24"></canvas>
              </span><canvas width="50" height="24"></canvas></span><canvas width="50" height="24"></canvas></span>10%</div>
                                <div class="right"> <strong>150</strong> 客户数 </div>
                            </li>
                            <li>
                                <div class="left peity_bar_bad"><span><span style="display: none;"><span style="display: none;"><span style="display: none;"><span style="display: none;">3,5,6,16,8,10,6</span><canvas width="50" height="24"></canvas></span>
              <canvas width="50" height="24"></canvas>
              </span><canvas width="50" height="24"></canvas></span><canvas width="50" height="24"></canvas></span>-40%</div>
                                <div class="right"> <strong>4560</strong> 商品数</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>-->
            <div class="span6">

            </div>
            <div class="span6">
                <!--<div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
                        <h5>待审批列表</h5>
                    </div>
                    <div class="widget-content">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="font-size: 20px">处理</th>
                                <th style="font-size: 20px">开始日期</th>
                                <th style="font-size: 20px">标题</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="odd gradeX">
                                <td><button class="btn btn-info ">处理</button></td>
                                <td>--</td>
                                <td>--</td>
                            </tr>
                            <tr class="even gradeC">
                                <td><button class="btn btn-info ">处理</button></td>
                                <td>--</td>
                                <td>--</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
                        <h5>绩效待审批列表</h5>
                    </div>
                    <div class="widget-content">
                    </div>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
                        <h5>主菜单</h5>
                    </div>
                    <div class="widget-content">
                        <div class="todo">
                            <ul>
                                <li class="clearfix">
                                    <div class="txt"><span class="by label">个人信息</span></div>
                                    <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-hand-up"></i></a></div>
                                </li>
                                <li class="clearfix">
                                    <div class="txt"><span class="by label">医疗报销</span></div>
                                    <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-hand-up"></i></a></div>
                                </li>
                                <li class="clearfix">
                                    <div class="txt"><span class="by label">薪酬福利</span></div>
                                    <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-hand-up"></i></a></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
        <div class="row-fluid" >
            <div class="span12">
                <div class="sheet__heading text-center"  >
                    <h1 class="giga text-heading-alt text-primary xs-margin-null xs-margin-bottom-small sm-margin-bottom-small md-margin-bottom-small">CHAMPLUS 尚嘉品鉴</h1>
                    <div class="content--large xs-margin-bottom-large md-margin-bottom-large">
                        <p class="weight--normal xs-margin-null text-primary">
                            欢迎加入我们，让我们一起共创未来！
                        </p></div>
                    <!--<a href="/calendar/" class="btn btn-warning">开始您的工作</a>-->
                </div>
                <div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-main-container-part-->
<script type="text/javascript">

    var endList = <?php echo json_encode($endList);?>;
    $(document).ready(function(){
        // === jQeury Gritter, a growl-like notifications === //
        for (var i = 0; i < endList.length; i++) {
            var obj = endList[i];
            $.gritter.add({
                title:  '年费过期客户提醒！',
                text: obj.cus_name+'即将过期，过期时间：'+obj.end_date+',还有'+obj.days+'天过期',
                //image:  'upload/img/demo/envelope.png',
                sticky: true
            });
        }

    });
</script>