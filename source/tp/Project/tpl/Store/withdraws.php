<include file="Public:header"/>        <style type="text/css">            .c-gray {                color: #999;            }            .table-list tfoot tr {                height: 40px;            }            .green {                color: green;            }            .date-quick-pick {                display: inline-block;                color: #07d;                cursor: pointer;                padding: 2px 4px;                border: 1px solid transparent;                margin-left: 12px;                border-radius: 4px;                line-height: normal;            }            .date-quick-pick.current {                background: #fff;                border-color: #07d!important;            }            .date-quick-pick:hover{border-color:#ccc;text-decoration:none}        </style>        <if condition="$withdrawal_count gt 0">            <script type="text/javascript">                $(function(){	                $('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录 <label style="color:red">(' + {pigcms{$withdrawal_count} + ')</label>')                })            </script>            <else/>            <script type="text/javascript">                $(function(){	                $('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录');                })            </script>        </if>        <script type="text/javascript">            $(function(){                if ($('.choice').length == $('.disabled').length) {                    $('.choice-all').attr('disabled', true);                }                $('.choice-all').live('click', function(){                    if ($(this).is(':checked')) {                        $('.choice').attr('checked', true);                        $('.disabled').attr('checked', false);                        $("select[name='batch_edit_status']").attr('disabled', false);                        $("select[name='batch_edit_status']").css('background-color', 'white');                    } else {                        $('.choice').attr('checked', false);                        $("select[name='batch_edit_status']").attr('disabled', true);                        $("select[name='batch_edit_status']").css('background-color', '#ddd');                    }                })                $('.choice').live('click', function(e){                    if ($(this).is(':checked')) {                        if (!$(".choice:not(:checked)").length) {                            $('.choice-all').attr('checked', true);                        }                        $("select[name='batch_edit_status']").attr('disabled', false);                        $("select[name='batch_edit_status']").css('background-color', 'white');                    } else {                        $('.choice-all').attr('checked', false);                        if (!$('.choice:checked').length) {                            $("select[name='batch_edit_status']").attr('disabled', true);                            $("select[name='batch_edit_status']").css('background-color', '#ddd');                        }                    }                })                $('.label-choice-all').live('click', function(){                    $('.choice-all').attr('checked', true);                    $('.choice').attr('checked', true);                    $('.disabled').attr('checked', false);                    $("select[name='batch_edit_status']").attr('disabled', false);                    $("select[name='batch_edit_status']").css('background-color', 'white');                })                $('.label-choice-cancel').live('click', function(){                    $('.choice-all').attr('checked', false);                    $('.choice').attr('checked', false);                    $("select[name='batch_edit_status']").attr('disabled', true);                    $("select[name='batch_edit_status']").css('background-color', '#ddd');                })                $("select[name='edit_status']").change(function(){                    if (confirm('确定修改？')) {                        var status = $(this).val();                        var id = $(this).closest('tr').children('td:eq(0)').children('.choice').val();                        var url = window.location.href;                        $.post("<?php echo U('Store/withdraw_status'); ?>", {'id': id, 'status': status}, function(data){                            if (data == 1) {                                window.location.href = url;                            }                        })                    }                })                $("select[name='batch_edit_status']").change(function(){                    if (confirm('确定修改？')) {                        var status = $(this).val();                        var id = [];                        $('.choice:checked').each(function (i) {                            id[i] = $(this).val();                        })                        var id = id.toString();                        var url = window.location.href;                        $.post("<?php echo U('Store/withdraw_status'); ?>", {                            'id': id,                            'status': status                        }, function (data) {                            if (data == 1) {                                window.location.href = url;                            }                        })                    }                })            })        </script>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Store/withdraw')}" class="on">提现记录</a>				</ul>			</div>			<table class="search_table" width="100%">				<tr>					<td>						<form action="{pigcms{:U('Store/withdraw')}" method="get">							<input type="hidden" name="c" value="Store"/>							<input type="hidden" name="a" value="withdraw"/>							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" />							<select name="type">								<option value="trade_no" <if condition="$_GET['type'] eq 'trade_no'">selected="selected"</if>>交易单号</option>                                <option value="bank_account" <if condition="$_GET['type'] eq 'bank_account'">selected="selected"</if>>银行账户</option>								<option value="store" <if condition="$_GET['type'] eq 'store'">selected="selected"</if>>店铺名称</option>                                <option value="user" <if condition="$_GET['type'] eq 'user'">selected="selected"</if>>申请人</option>								<option value="tel" <if condition="$_GET['type'] eq 'tel'">selected="selected"</if>>联系电话</option>							</select>                            &nbsp;&nbsp;                            收款银行：                            <select name="bank">                                <option value="0">收款银行</option>                                <volist name="banks" id="bank">                                <option value="{pigcms{$bank.bank_id}" <if condition="$Think.get.bank eq $bank['bank_id']">selected</if>>{pigcms{$bank.name}</option>                                </volist>                            </select>                            &nbsp;&nbsp;提现状态：                            <select name="status">                                <option value="0">全部</option>                                <option value="1" <if condition="$Think.get.status eq 1">selected</if>>申请中</option>                                <option value="2" <if condition="$Think.get.status eq 2">selected</if>>银行处理中</option>                                <option value="3" <if condition="$Think.get.status eq 3">selected</if>>提现成功</option>                                <option value="4" <if condition="$Think.get.status eq 4">selected</if>>提现失败</option>                            </select>                            &nbsp;&nbsp;申请时间：                            <input type="text" name="start_time" id="js-start-time" class="input-text Wdate" style="width: 150px" value="{pigcms{$Think.get.start_time}" readonly />- <input type="text" name="end_time" id="js-end-time" style="width: 150px" class="input-text Wdate" value="{pigcms{$Think.get.end_time}" readonly />                            <span class="date-quick-pick" data-days="7">最近7天</span>                            <span class="date-quick-pick" data-days="30">最近30天</span>                            <input type="submit" value="查询" class="button"/>						</form>					</td>				</tr>			</table>            <div class="table-list">                <table width="100%" cellspacing="0">                    <thead>                        <tr>                            <th><input type="checkbox" class="choice-all" value="1" /></th>                            <th>编号</th>                            <th>交易单号</th>                            <th>申请时间</th>                            <th>银行账户</th>                            <th>店铺名称</th>                            <th>提现金额(元)</th>                            <th>可提现余额</th>                            <th>处理完成时间</th>                            <th>状态</th>                            <th>申请人</th>                            <th>联系方式</th>                            <th>备注</th>                            <th class="textcenter">操作</th>                        </tr>                    </thead>                    <tbody>                        <if condition="is_array($withdrawals)">                            <volist name="withdrawals" id="withdrawal">                                <tr>                                    <td><input type="checkbox" value="{pigcms{$withdrawal.pigcms_id}" <if condition="$withdrawal['status'] eq 3 OR $withdrawal['status'] eq 4">disabled="true" class="choice disabled"<else/>class="choice"</if> /></td>                                    <td>{pigcms{$withdrawal.pigcms_id}</td>                                    <td><span class="c-gray">{pigcms{$withdrawal.trade_no}</span></td>                                    <td>{pigcms{$withdrawal.add_time|date='Y-m-d H:i:s', ###}</td>                                    <td>                                        <span class="c-gray">账户类型：</span><if condition="$withdrawal['withdrawal_type'] eq 0">个人账户<else/>公司账户</if><br/>                                        <span class="c-gray">收款银行：</span><?php echo $withdrawal['bank']; ?><br />                                        <span class="c-gray">开户银行：</span><?php echo $withdrawal['opening_bank']; ?><br />                                        <span class="c-gray">银行帐户：</span><?php echo $withdrawal['bank_card']; ?><br />                                        <span class="c-gray">帐户名称：</span><?php echo $withdrawal['bank_card_user']; ?><br/><br/>                                    </td>                                    <td>{pigcms{$withdrawal.store}</td>                                    <td class="red">{pigcms{$withdrawal.amount}</td>                                    <td class="green">{pigcms{$withdrawal.balance}</td>                                    <td><if condition="$withdrawal['complate_time']">{pigcms{$withdrawal['complate_time']|date='Y-m-d H:i:s', ###}</if></td>                                    <td><if condition="$withdrawal['status'] eq 1">申请中<elseif condition="$withdrawal['status'] eq 2"/>银行处理中<elseif condition="$withdrawal['status'] eq 3"/><span class="green">提现成功</span><elseif condition="$withdrawal['status'] eq 4"/><span class="c-gray">提现失败</span></if></td>                                    <td>{pigcms{$withdrawal.nickname}</td>                                    <td>{pigcms{$withdrawal.mobile}</td>                                    <td>{pigcms{$withdrawal.bak}</td>                                    <td class="textcenter">                                        <select name="edit_status" <if condition="$withdrawal['status'] eq 3 OR $withdrawal['status'] eq 4">disabled="true" style="background-color:#ddd;width: 100px"<else/>style="width: 100px"</if>>                                            <if condition="$withdrawal['status'] eq 1">                                            <option value="1" <if condition="$withdrawal['status'] eq 1">selected</if>>申请中</option>                                            </if>                                            <if condition="$withdrawal['status'] eq 1 OR $withdrawal['status'] eq 2">                                            <option value="2" <if condition="$withdrawal['status'] eq 2">selected</if>>银行处理中</option>                                            </if>                                            <if condition="$withdrawal['status'] eq 1 OR $withdrawal['status'] eq 2 OR $withdrawal['status'] eq 3">                                            <option value="3" <if condition="$withdrawal['status'] eq 3">selected</if>>提现成功</option>                                            </if>                                            <if condition="$withdrawal['status'] neq 3">                                            <option value="4" <if condition="$withdrawal['status'] eq 4">selected</if>>提现失败</option>                                            </if>                                        </select>                                    </td>                                </tr>                            </volist>                        </if>                    </tbody>                    <tfoot>                        <if condition="is_array($withdrawals)">                        <tr>                            <td class="pagebar" colspan="14">                                <div>                                    <div style="float: left">                                        <label style="cursor: pointer;color: #3865B8;" class="label-choice-all">全选</label> / <label style="cursor: pointer;color: #3865B8;" class="label-choice-cancel">取消</label>                                        <select name="batch_edit_status" disabled="true" style="background-color: #ddd">                                            <option value="0">批量操作</option>                                            <option value="2">银行处理中</option>                                            <option value="3">提现成功</option>                                            <option value="4">提现失败</option>                                        </select>                                    </div>                                    <div style="float: right">                                        {pigcms{$page}                                    </div>                                </div>                            </td>                        </tr>                        <else/>                        <tr><td class="textcenter red" colspan="14">列表为空！</td></tr>                        </if>                    </tfoot>                </table>            </div>		</div><include file="Public:footer"/>