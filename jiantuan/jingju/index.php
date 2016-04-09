<?php
$examples= array();
$example_jian= "";
$multi_chars= "";

foreach (glob("data/*.php") as $filename){
    include $filename;
}

include 'elements.php';
include 'multi.php';

function print_random(){

	global $examples,$elements,$multi,$example_jian,$multi_chars;
	$example_jian="";
	$multi_chars="";
	//Generate a random example
	$print_example = $examples[rand(0,count($examples)-1)];

	for ($i=0 ; $i<mb_strlen($print_example,'utf-8') ; $i++) {
		//find all jian chars and put in example_jian
		$char=mb_substr($print_example, $i, 1, 'utf-8');
		if(in_array($char,$elements) && strpos($example_jian, $char)===false){
			$example_jian .= $char;
			//find all polyphonic-chars in example_jian and put in multi_chars
			if(in_array($char,$multi)){
				$print_example=str_replace($char, "<span style='color:#4B5CC4'>".$char."</span>", $print_example);
				$multi_chars .= $char;
			}
		}
	}
	return $print_example;
}
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
	<script src="/jquery-1.11.2.min.js"></script>
	<title>尖团-京剧</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div id="navigation">
	<div class="homepage">
		<a class="nav" href="/ch/">首页</a>
	</div>
	<div class="jiantuan">
		<a class="now" href="/ch/jiantuan">尖团</a>
	</div>
	<div class='sub'>
		<a class="nav" href="/ch/jiantuan/jingju">京剧</a><br>
		<a class="nav" href="/ch/jiantuan/query-jian">查询</a><br>
	</div>
	<div class="rusheng">
		<a class="nav" href="/ch/rusheng/shici">入声</a>
	</div>
	<div class="kunqu_tools">
		<a class="nav" href="/ch/kunqu_tools/fanliji">工具</a>
	</div>
	<div class="about">
		<a class="nav" href="/ch/about/">关于</a>
	</div>
</div>

<div id="gui">
	<h1><img src="logo.png" width="40" height="40">尖团-京剧</h1>
	<p><span style='color:#4B5CC4;font-weight:bold;'>蓝色</span>字体为多音字，请自行判断是否为尖字<br>（判断结果不影响最终结果）</p>
		
	<button onclick="alert('戏词：'+localStorage.getItem('one_piece')+'\n'+'尖字： '+localStorage.getItem('another_piece'));">回顾</button>
	<input type="text"  placeholder=" 尖 字" style="width:200px;height:28px;" autofocus="focus" onkeydown="if(event.keyCode==13){get_user_input();}">
	<input type="submit" name="userInput" value="确定" onclick="get_user_input();" >

	<div class="note">请输入下列句子中<span style="color:#ff0000;font-weight:bold">所有的</span>尖字</div>

	<div class="example" id="text"></div>
</div>

<div id="sidebar">
	参考资料来源：<br><br><a class="side" href="http://xikao.com/" target="_blank">戏考</a><br>
	<a class="side" href="http://ytenx.org/" target="_blank">韵典网</a><br><br>
	反馈：<br><a class="side" href="http://weibo.com/message/history?uid=1344082573" target="_blank">微博</a><br>
	<a class="side" href="mailto:mail@messyxin.com" target="_blank">邮件</a><br>
	<a class="side" href="http://www.douban.com/doumail/16932498/" target="_blank">豆瓣</a><br>
	关于：<br><a class="side" href="http://sou-yun.com/" target="_blank">说明</a><br>
	<a class="side" href="http://sou-yun.com/" target="_blank">更新日志</a><br>
	书签：<br><a class="side" href="http://www.zdic.net/" target="_blank">汉典</a><br>
	<a class="side" href="http://yedict.com/" target="_blank">字海</a><br>
	<a class="side" href="http://xikao.com/" target="_blank">戏考</a><br>
	<a class="side" href="http://sou-yun.com/" target="_blank">搜韵网</a><br>
	<a class="side" href="http://ytenx.org/" target="_blank">韵典网</a><br>
	<a class="side" href="http://bbs.gxsd.com.cn/" target="_blank">国学数典论坛</a><br>
</div>
<script>
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (elt /*, from*/) {
        var len = this.length >>> 0;
        var from = Number(arguments[1]) || 0;
        from = (from < 0) ? Math.ceil(from) : Math.floor(from);
        if (from < 0) from += len;

        for (; from < len; from++) {
            if (from in this && this[from] === elt) return from;
        }
        return -1;
    };
}
$(function() {$('[autofocus]').focus()});
var example_jian=[];
var print_example="";
var multi_chars=[];
var one_piece="";
var j=0;

$(function(){
    print_example = <?php echo json_encode(print_random()); ?>;
	example_jian=(<?php echo json_encode($example_jian); ?>).split("");
	multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
	$('.example').html(print_example);
});

var not_example=[];
var not_jian=[];
var input_characters=[];

var get_user_input=function(){
	
	not_example=[];
	not_jian=[];
	input_characters=[];

	//get user input
	var unprocessed_input=$('input:text').val();
	
	if (unprocessed_input.length===0){
		$('.note').html('请输入下列句子中<span style="color:#ff0000;font-weight:bold">所有的</span>尖字');
		return;
	}

	unprocessed_input=unprocessed_input.split(/，| |,|/).join("");

	//unprocessed user input convert to array
	var unprocessed_characters=unprocessed_input.split("");
	for(var i=0;i<(unprocessed_input.length);i++){
		//alert("xyz");
		//alert(input_characters.indexOf('a'));
		if(input_characters.indexOf(unprocessed_characters[i])<0){
			input_characters.push(unprocessed_characters[i]);
		}
	}
	//alert(input_characters);

	//combine multi char with input chars
	for(var i=0;i<multi_chars.length;i++){
		if(input_characters.indexOf(multi_chars[i])<0){
			input_characters.push(multi_chars[i]);
		}
	}
	
	for (var i=0;i<input_characters.length;i++){
		//find input not in example and put in not_example
		if (print_example.search(input_characters[i])<0) {not_example.push(input_characters[i]);};
	}
	if (not_example.length != 0){
		//print a note
		$('.note').html("字符"+"<span style='font-weight:bold'>“"+not_example+"”</span>不在戏词中，请重新输入");
		return;
	}

	for (var i=0;i<input_characters.length;i++){
		//找出user inut中not尖字and put in not_jian
		if (example_jian.indexOf(input_characters[i])<0){
			not_jian.push(input_characters[i]);
		}
	}
	if (not_jian.length!=0){
		//print a note
		$('.note').html("字符"+"<span style='font-weight:bold'>“"+not_jian+"”</span>不是尖字，请重新输入");
		return;
	}

//////  Correct answer!!!!
	if (input_characters.length===example_jian.length){
		localStorage.setItem("one_piece", document.getElementById('text').innerText);
		localStorage.setItem("another_piece", example_jian);
		$('.note').html("<span style='font-weight:bold'>↖(^ω^)↗答案正确！请继续</span>");
		funcA(j);
		j=j+1;
		$('input:text').val("");
	}
	else {
		$('.note').html("你没有找到所有的尖字，请继续尝试");
	}
}
var funcA=function(j){
	switch(j){
		case 0:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 1:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 2:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 3:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 4:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 5:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 6:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 7:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 8:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		case 9:
			$(function(){
			    print_example = <?php echo json_encode(print_random()); ?>;
				example_jian=(<?php echo json_encode($example_jian); ?>).split("");
				multi_chars=(<?php echo json_encode($multi_chars); ?>).split("");
				$('.example').html(print_example);
			});
			break;
		default:
			location.reload(true);
	}
	setTimeout(funcB,1000);
}
var funcB=function(){
	$('.note').html('请输入下列句子中<span style="color:#ff0000;font-weight:bold">所有的</span>尖字');
}
</script>
</body>