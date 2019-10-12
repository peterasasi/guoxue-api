# core-api

## 配置
TEST_CLIENT_ID=by04esfH0fdc6Y
CDN_IMG_URI=图片根域名
ROOT_DOMAIN=接口根域名-用于控制台环境下生成绝对地址用的



#Route 
alipay_query       ANY        ANY      ANY    /alipay-query-{payCode}                                   
alipay_pc          GET|POST   ANY      ANY    /alipay-pc-{payCode}-{money}-{subject}                    
alipay_show        GET        ANY      ANY    /alipay/show                                              
alipay_notify      GET|POST   ANY      ANY    /alipay/notify                                            
index_api_entry    POST|GET   ANY      ANY    /base                                                     
t_encrypt          ANY        ANY      ANY    /transport/encrypt3                                       
tV4_callback       ANY        ANY      ANY    /transport/callback                                       
rsav3_encrypt      ANY        ANY      ANY    /transport/rsav3                                          
tV4_encrypt        ANY        ANY      ANY    /transport/encryptV4                                      
web_detect         ANY        ANY      ANY    /web/detect                                               
web_card           ANY        ANY      ANY    /web/card                                                 
web_lend           ANY        ANY      ANY    /web/lend                                                 
_twig_error_test   ANY        ANY      ANY    /_error/{code}.{_format}                                  
admin              ANY        ANY      ANY    /admin 

# 注意事项

参数不能直接用   
client_id   
project_id       
alg            

Entity设置字段用 驼峰式
Service中查询操作返回多是数组   
Service中获取操作返回多是单个Entity对象

   

# 指令 
### 本地开启服务器
````
php bin/console server:run
````

### 部署到测试服务器
````
php bin/console deploy dev
````

### 监控日志
symfony/var-dumper
```
bin/console server:log -vvv
```

### 初始化数据库

```
doctrine:fixtures:load
```


####

重写父类Entity
/**
 * @Entity
 * @AttributeOverrides({
 *      @AttributeOverride(name="id",
 *          column=@Column(
 *              name     = "guest_id",
 *              type     = "integer",
 *              length   = 140
 *          )
 *      ),
 *      @AttributeOverride(name="name",
 *          column=@Column(
 *              name     = "guest_name",
 *              nullable = false,
 *              unique   = true,
 *              length   = 240
 *          )
 *      )
 * })
 */

## 使用 supervisor 

[https://symfony.com/doc/current/messenger.html#installation](https://symfony.com/doc/current/messenger.html#installation)

```
;/etc/supervisor/conf.d/messenger-worker.conf
[program:messenger-consume]
command=php /path/to/your/app/bin/console messenger:consume async --time-limit=3600
user=ubuntu
numprocs=2
autostart=true
autorestart=true
process_name=%(program_name)s_%(process_num)02d
```
```
// 重新载入
sudo supervisorctl reread
// 更新
sudo supervisorctl update
// 启动
sudo supervisorctl start messenger-consume
```

## messenger 消费

bin/console messenger:consume [message transport]

## 任务 爬取任务
