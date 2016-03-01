

<form class="form-horizontal" style="">
    <div class="control-group">
        <label class="control-label">
            <em class="required">*</em>
            客服电话：
        </label>
        <div class="controls">
          <input type="text" name="mobile" placeholder="请输入客服电话"  value="<?php echo $information['service_tel'];?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <em class="required">*</em>
            客服 QQ：
        </label>
        <div class="controls">
            <input type="text" name="qq" placeholder="请输入客服QQ" value="<?php echo $information['service_qq'];?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <em class="required">*</em>
            客服微信：
        </label>
        <div class="controls">
            <input type="text" name="weixin" placeholder="请输入您的微信号" value="<?php echo $information['service_weixin'];?>">
        </div>
    </div>    
    <div class="form-actions" style="margin-top:25px">
        <button type="button" id="sub-button" class="ui-btn ui-btn-primary js-physical-submit">添加</button>
    </div>
</form>
<script>
          
  $(function(){
    
     $('.js-physical-submit').live('click', function(){
        var tel = $("input[name='mobile']").val();
        var qq = $("input[name='qq']").val();
        var weixin = $("input[name='weixin']").val();
        $.post(information_url, {'type': 'add', 'tel': tel, 'qq': qq, 'weixin': weixin}, function(data){
            if (data.err_code == 0) {
                $('.notifications').html('<div class="alert in fade alert-success">' + data.err_msg + '</div>');
                $('.modal').animate({'margin-top': '-' + ($(window).scrollTop() + $(window).height()) + 'px'}, "slow",function(){
                    $('.modal-backdrop,.modal').remove();
                });
            } else {
                $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
            }
            t = setTimeout('msg_hide()', 3000);
        })
    })
       /*var nowDom = $(this);
            layer.closeAll();
            var formObj = $('.form-horizontal').serialize();
            
            $('.js-physical-submit').click(function(){
                  var mobile = $("input[name='mobile']").val();
                  var qq = $("input[name='qq']").val();
                  var weixin = $("input[name='weixin']").val();
                  if(!/^1[0-9]{10}$/.test(mobile))
                     {
                        $("input[name='mobile']").parents('.controls').addClass('error');
                        $("input[name='mobile']").next('.error').remove();
                        $("input[name='mobile']").after('<p class="error" style="color:red;">请正确填写手机号</p>');
                     }
                     else 
                    {
                         $("input[name='mobile']").parents('.controls').children().remove('p');
                         $("input[name='mobile']").next('.error-message').remove();
                    }
                  if($("input[name='qq']").val() == '')
                  {
                      $("input[name='qq']").parents('.controls').addClass('error');
                      $("input[name='qq']").next('.error').remove();
                      $("input[name='qq']").after('<p class="error" style="color:red;">请填写QQ号</p>');
                  }
                  else
                  {
                      $("input[name='qq']").parents('.controls').children().remove('p');
                      $("input[name='qq']").next('.error-message').remove();
                  }
                  
                  if($("input[name='weixin']").val() == '')
                  {
                      $("input[name='weixin']").parents('.controls').addClass('error');
                      $("input[name='weixin']").next('.error').remove();
                      $("input[name='weixin']").after('<p class="error" style="color:red;">请填写QQ号</p>');
                  }
                  else
                  {
                      $("input[name='weixin']").parents('.controls').children().remove('p');
                      $("input[name='weixin']").next('.error-message').remove();
                  }
                  alert($('.error').length);
                  if ($('.error').length == 0) {
                  $.post(information_url,{'tel':mobile, 'qq': qq, 'weixin':weixin},function(result){
                    if(result.code==0){
                         alert('l');
                    }else{
                        alert('kkk');
                    }
                });
                  }
            });*/
  });
</script>

