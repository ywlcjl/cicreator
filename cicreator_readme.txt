安装说明:
1. 配置CodeIgniter 2.2.5框架使其能在虚拟机的根目录访问.
2. 新建mysql数据库, 导入cicreator.sql
3. 使用浏览器打开配置CodeIgniter框架的首页

关于对CodeIgniter框架修改说明:
   CICreator并没有对CodeIgniter的核心进行任何改动, (即/system), 而所有在修改过的非框架核心文件都加有”~cc”的标识, 具体请自行搜索. 
   另外对于分页类的重载是基于原分页类有一处未能满足要求, 详细请在/application/libraries/Pagination.php查看, 如想使用默认分页类, 请将此文件移除.


关于日后对CodeIgniter框架的升级:
    由于CICreator并没有对CodeIgniter框架的核心部分进行任何修改, 所以如果你想日后升级CodeIgniter框架，只需替换/ststem目录即可.
