<?php
/**
 * 观察者模式应用场景实例
 *
 * 免责声明:本文只是以哈票网举例，示例中并未涉及哈票网任何业务代码，全部原创，如有雷同，纯属巧合。
 *
 * 场景描述：
 * 哈票以购票为核心业务(此模式不限于该业务)，但围绕购票会产生不同的其他逻辑，如：
 * 1、购票后记录文本日志
 * 2、购票后记录数据库日志
 * 3、购票后发送短信
 * 4、购票送抵扣卷、兑换卷、积分
 * 5、其他各类活动等
 *
 * 传统解决方案:
 * 在购票逻辑等类内部增加相关代码，完成各种逻辑。
 *
 * 存在问题：
 * 1、一旦某个业务逻辑发生改变，如购票业务中增加其他业务逻辑，需要修改购票核心文件、甚至购票流程。
 * 2、日积月累后，文件冗长，导致后续维护困难。
 *
 * 存在问题原因主要是程序的"紧密耦合"，使用观察模式将目前的业务逻辑优化成"松耦合"，达到易维护、易修改的目的，
 * 同时也符合面向接口编程的思想。
 *
 * 观察者模式典型实现方式：
 * 1、定义2个接口：观察者（通知）接口、被观察者（主题）接口
 * 2、定义2个类，观察者对象实现观察者接口、主题类实现被观者接口
 * 3、主题类注册自己需要通知的观察者
 * 4、主题类某个业务逻辑发生时通知观察者对象，每个观察者执行自己的业务逻辑。
 *
 * 示例：如以下代码
 *
 */
ini_set('display_errors',1);
error_reporting(E_ALL); 

/*
 *
 *定义主题契约规范
 *
 */

interface Subject
{
	public function register(Oberser $oberser); //定义注册树模型
	public function notity(array $obj); //推送消息信息
}

/*
 *
 * 定义观察者契约接口规范
 *
 */
 
 interface Oberser
 {
	 public function bugTickHipiao($sender,$args); //规定买票函数 $sender契约主模式  $args 参数
 }
/*
 *
 * 实现主题契约规范接口
 *
 */
 class BuyOnTickHipiao implements Subject()
 {
	public $_obversers = [];  // 存储oberser对象信息的集合
	
	// 定义注册树模型
	public function register(Oberser $oberser)
	{
		$this->_obversers [] = $oberser;
	}
	// 实现信息的推送 与记录
	public function notity(array $obj)
	{
		foreach($this->_obversers as $oberser){
			
			$oberser->bugTickHipiao($this,$obj);
			
		}
	}
 }
 
 //实现观察者契约接口的实现(消息类)
 
 class HipiaoMSM implements Oberser  //个人意识有点像依赖倒置
 {
	 public function bugTickHipiao($sender,$ticket)
	 {
		  echo (date ( 'Y-m-d H:i:s' ) . " 短信日志记录：购票成功:{$ticket['remake']}<br>");
	 }
 }
 
 // 购票之后的文本文档记录信息
 class  HipiaoText implements Oberser
 {
	 public function bugTickHipiao($sender,$ticket)
	 {
		// 操作逻辑
		
		echo (date ( 'Y-m-d H:i:s' ) . " 文本文档日志记录：购票成功:{$ticket['remake']}<br>");
	 }
 }
 
 //优惠抵扣
 class HipiaoDatabase implements Oberser
 {
	 public function bugTickHipiao($sender,$ticket)
	 {
		echo (date ( 'Y-m-d H:i:s' ) . " 赠送抵扣卷：购票成功:{$ticket['remake']} 赠送10元抵扣卷1张。<br>");
	 }
 }
 
 $buyOnTickHipiao = new BuyOnTickHipiao();
 
 $bugTickHipiao->register(new HipiaoMSM());
 $bugTickHipiao->register(new HipiaoText());
 $bugTickHipiao->register(new HipiaoDatabase());
 $bugTickHipiao->notity(['id'=>1,'order_on'=>'20191218025139','remake' => '一排一号']);
 