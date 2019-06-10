## 一、 FOSRestBundle Rest风格的API扩展
[文档](http://symfony.com/doc/master/bundles/FOSRestBundle/index.html)
```bash 
http://symfony.com/doc/master/bundles/FOSRestBundle/index.html
```

[GITHUB](https://github.com/FriendsOfSymfony/FOSRestBundle) 
```bash 
https://github.com/FriendsOfSymfony/FOSRestBundle
```

安装 
```bash
composer require friendsofsymfony/rest-bundle

//添加至内核中 app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new FOS\RestBundle\FOSRestBundle(),
        ];
        // ...
    }
}
```

### 配置config.yml
```bash
fos_rest:
    view:
        formats:
            jsonp: true
            json: true
            xml: true
            rss: true
        templating_formats:
            html: true
        force_redirects:
            html: true
        failed_validation: HTTP_BAD_REQUEST
        default_engine: twig
        jsonp_handler: ~
```

### handleView
```
setData($data) 将$data进行序列化
setTemplateData($data) 设置模板数据数组或匿名函数,封闭应该返回数组
setHeader($name, $value) 设置一个信息放在http响应内容中
setHeaders(array $headers) 设置多个信息放在http响应内容中
setStatusCode($code) 设置HTTP状态码
getContext()
setTemplate($template) 在HTML渲染的情况下使用的模板名称
setTemplateVar($templateVar) 当传递给html模板时,设置数据所在的变量名称，默认值为data
setEngine($engine) 
setFormat($format) 
setLocation($location)
setRoute($route) 重定向到指定的路由
setRouteParameters($parameters) 设置重定向路由参数
setResponse(Response $response)
```

## 安装序列化扩展 serializer
使用rest-bundle时，必须安装序列化扩展(将数据序列化为请求指定的格式输出)
`jms/serializer-bundle`(EKT项目中使用) 或者使用 `Symfony Serializer` (symfony提供的对数据进行序列化/反序列化)

### jms/serializer-bundle
[文档](http://jmsyst.com/bundles/JMSSerializerBundle) 
```bash
http://jmsyst.com/bundles/JMSSerializerBundle
```

[GITHUB](https://github.com/schmittjoh/JMSSerializerBundle) 
```bash
https://github.com/schmittjoh/JMSSerializerBundle
```

安装
```bash
composer require jms/serializer-bundle 

//添加至内核中 app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new JMS\SerializerBundle\JMSSerializerBundle(),
        ];
        // ...
    }
}
```

### symfony serializer
[文档](http://symfony.com/doc/current/serializer.html) 
```bash
http://symfony.com/doc/current/serializer.html
```

安装 
```bash
composer require serializer 
```

## 二、 NelmioApiDocBundle 对API接口生成文档，可以在接口调试时 返回文档信息和测试数据
[文档](https://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html)
```bash
https://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html
```

[GITHUB](https://github.com/nelmio/NelmioApiDocBundle)
```bash
https://github.com/nelmio/NelmioApiDocBundle
```

### 安装 
```bash
composer require nelmio/api-doc-bundle

//添加至内核中 app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
        ];
        // ...
    }
}
```

###  配置 app/config/routing.yml
```bash
NelmioApiDocBundle:
    resource: "@NelmioApiDocBundle/Resources/config/routing.yml"
    prefix:   /api/doc
```

###  配置 app/config/config.yml
```bash
nelmio_api_doc:
    name: '测试API文档'
    cache:
        enabled: false
    request_listener:
        enabled:   true
        parameter: _doc
    sandbox:
        request_format:
            method: accept_header
```

### 最后通过/api/doc访问 可以查看项目中， 已经定义的所有接口信息  、
![](leanote://file/getImage?fileId=5a6827105061ee3c43000000)



## 三、 测试DEMO
### 3.1 添加路由 route.yml
```bash
icsoc_app_test_index:
    path:     /api/app/test.{_format}
    defaults: { _controller: IcsocUIBundle:App:test, _format: json }
    methods: [GET] //指定请求方式
    requirements:
        _format: json|jsonp|xml  //指定数据内容支持的格式 可以通过配置文件设置默认值
```

### 3.2 添加controller
```bash
use FOS\RestBundle\Controller\FOSRestController;
class AppController extends FOSRestController
{
    /**
     * # Demo示例
     *
     * ### 成功返回
     * ```json
     * {
     *      "code": 0,
     *      "message": "ok",
     *      "data": {
     *          "name": "zhangsan",
     *      }
     * }
     * ```
     *
     * ### 失败返回
     * ```json
     * {
     *      "code": 400,
     *      "message": "参数不正确",
     *      "data": {}
     * }
     * ```
     *
     * @ApiDoc(
     *     section="Demo",
     *     description="Demo示例",
     *     tags={"stable"},
     *     filters={
     *         {"name"="page", "dataType"="integer"},
     *         {"name"="sort", "dataType"="string", "pattern"="(foo|bar) ASC|DESC"}
     *     },
     *     parameters={
     *         {"name"="name", "description"="姓名","dataType"="string","format"="a|b|c","required"=true},
     *         {"name"="age", "description"="年龄","dataType"="integer","format"="0","required"=true},
     *     },
     * )
     *
     * @param string $_format 响应消息体格式
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction($_format)
    {
        $data = array();  //接口返回的数据
        $view = $this->view($this->successData($data), 200)->setFormat($_format);  //成功时
        //or
        $view = $this->view($this->errorData($errCode, $errMsg))->setFormat($_format); //失败时
        return $this->handleView($view);   //用fos_rest.view_handler服务进行处理
    }
}
```

### ApiDoc
```
section 分类名称,相当于导航栏菜单
description 接口描述,支持html语法
tags 接口标签版本描述
views 
filters 过滤参数
    name
    dataType
    pattern
parameters 
    name 参数名称
    dataType 参数类型
    required 是否为必备内容
    format 参数格式或者默认值
    description 参数描述
```





