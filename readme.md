## Serving Your Application
`php -S localhost:8000 -t public`

## Facades

```
'Illuminate\Support\Facades\App', 'App'
'Illuminate\Support\Facades\Auth', 'Auth'
'Illuminate\Support\Facades\Bus', 'Bus'
'Illuminate\Support\Facades\DB', 'DB'
'Illuminate\Support\Facades\Cache', 'Cache'
'Illuminate\Support\Facades\Cookie', 'Cookie'
'Illuminate\Support\Facades\Crypt', 'Crypt'
'Illuminate\Support\Facades\Event', 'Event'
'Illuminate\Support\Facades\Hash', 'Hash'
'Illuminate\Support\Facades\Log', 'Log'
'Illuminate\Support\Facades\Mail', 'Mail'
'Illuminate\Support\Facades\Queue', 'Queue'
'Illuminate\Support\Facades\Request', 'Request'
'Illuminate\Support\Facades\Schema', 'Schema'
'Illuminate\Support\Facades\Session', 'Session'
'Illuminate\Support\Facades\Storage', 'Storage'
'Illuminate\Support\Facades\Validator', 'Validator' 

```

## nginx setting
try_files $uri $uri/ /index.php?$query_string;

## test 单元测试
1. phpunti 全部测试
2. phpunti tests/PayTest //指定文件