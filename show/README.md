#TV Show Helper
Alfred Workflow to help you to remember which episde have you watched!  
一个帮助你记住你的电视剧们都看到哪里了的Alfred Workflow

__Version: beta 0.4__

##Getting Started
Use Keyword `watch` to activate this workflow with parameter whether complete or incomplete name of the show.  
使用关键词 `watch` 来激活这个workflow，并后缀上电视剧名

Use `<Enter>` Key to confirm, which means you have watched another episode of it  
回车已确认，来表示你又看了一集

then you will get a feedback at notification bar  
确认之后你会在通知中心获得一个反馈

if the show wasn`t in the local plist, input the complete name of it and press `<Enter>` key to add it in the plist file  
如果你看的片并不在本地属性文件中，请在参数处输入完整的剧名并按回车键确认添加此剧

##What's New
####0.4
_Jul 5th, 2014_

- __Change Plist Path__  
	Put plist file in Application Supports

- __Hotkey added__ 

- __few bug fixed__ 

####0.3
_Jun 28th, 2014_

- __Init Plist File__  
	Automate to init plist file when you first use it.  
	在第一次使用时自动初始化一个plist文件
		
- __Undo Support__  
	Now you can use "watch undo" to undo your last change, and use "watch all" to display all the show in your plist file.  
	使用"watch undo"可以撤销上一次指令的效果，并可以使用"watch all"来显示所有剧名。
	
- __Init Episode Support__ 
	"watch init {eps} {name}" to init directly.

####0.2
_May 2nd, 2014_

- __Single Quoto Support__  
	Use double quoto "{query}" to deal with the parse error like `'Grey's Anatomy'`  

####0.1
_May 1st, 2014_  

- __Chinese Support__  
	now you can use pinyin of Chinese Character to search  
	你可以使用中文和中文拼音来查找一个中文剧名的电视剧了  
- __New Icon__  
	use brand new icon instead of Google TV logo  
	使用全新的图标，而不再是Google TV的图标了
	
	
##Contact Me
E-mail: fate_stigma@hotmail.com  
Website: fatestigma.github.io
