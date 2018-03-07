<?php
/*----------------------------------------
 *  买家账号：joqjlq5385@sandbox.com
 *  登录密码：111111
 *  支付密码：11111
 *  证件号码：356832190808272106
 * 
 *  商家账号：gsvymh7752@sandbox.com
 *  商户UID：2088102172443237
 *  登录密码：111111
 */
// return [

//     // 商户公钥
//     'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAytWYpGIj0kNCjmw/0VnflRdx1+au9FFkUIm7zoZt+IVDFflwDblidA2LdolWrTN9QVgG2STf1vSvM4we7LFpUC957H5+r8CuOriGsZObFezDEwW1b0B5NOu+nxqi6yENeUldyB17ofLrZxXrXSbbQmZJ6N7P70lC7po96tw1hhf8oIeAV+qmKL4jm4bVSNzfyD4ijn0zio0GL/YC532I8fnQDX5ppKn8sWSGP02UVs1Z3riPXvJJhIWIAkhCWbPk3ID1+W7ijIz+SLAtLygRVxt4zuwyht9ttxDEZjVT98TnDThw7VATGWFXaX/IYWJgVAGeucODNQOpviAnkbm1JQIDAQAB',

//     // 商户私钥
//     'private_key' => 'MIIEowIBAAKCAQEAytWYpGIj0kNCjmw/0VnflRdx1+au9FFkUIm7zoZt+IVDFflwDblidA2LdolWrTN9QVgG2STf1vSvM4we7LFpUC957H5+r8CuOriGsZObFezDEwW1b0B5NOu+nxqi6yENeUldyB17ofLrZxXrXSbbQmZJ6N7P70lC7po96tw1hhf8oIeAV+qmKL4jm4bVSNzfyD4ijn0zio0GL/YC532I8fnQDX5ppKn8sWSGP02UVs1Z3riPXvJJhIWIAkhCWbPk3ID1+W7ijIz+SLAtLygRVxt4zuwyht9ttxDEZjVT98TnDThw7VATGWFXaX/IYWJgVAGeucODNQOpviAnkbm1JQIDAQABAoIBAQCYKRxVVPoiZiqrxTEq0A0WP3w7xuZAij6C3JBzIg2lffMRrQoOgaAGB5Mz3VuUmye/uVWJ2EvDadN1DAy263BhovwIGezX0+fgTUVeOakCDZdZ0dKGHwvOU3uwx76oPSdqcUtVMCjrGNzXfG4Qd3HMogeYFm5Ox9raPANvCLtuV4EmRb0V0cRJJrfiVWYa5DrTnYphAiYozTBl8Rxo6PIUUx0QptK4eCbQ4ePFLdKW0Sb11uTrW1OG7MXFVzbr7oA77LNu8NDp6J4KgI6YpRsTpTIovXaA3CEWfc6QJb6xhpzgKX7trULKvNjRTTZ3mg5Nz0oDvdYxI84jYNM1QPeRAoGBAPfifOo+Om3q4oq7xcurqkVZPMrHvxQ2t2pOqqdahb1y6EgQgZAXKiH2smrhZWbKSmf38eyLQHhgLak8fGueWOPpNFfeLZUPtF1eCFvNoXA4blg690f4a1UwqEiVvPVl6Y32AvN5FKgxH+lRhJ6q6Z24YypETkZWvcZ2CAtNnp6bAoGBANF5iwRDZHorLherwpOZhQ2oAp+0LpQTRS35UoKif08KfI9yKwIr53U0/tsIgLmoPDlI/gZaeRDYBug9DbzuG0S2fyvNiykQ0qwFzVLn0/dS9Cm7eEiwI9BHbeS8o91lkbUoa+tDzjEb3q+GXDlJvtuvkGrnWPwOWuLuicG2/lc/AoGAZyn4iNhrItHNhKWPNSt1irUP6ujkqjn3baPdvBadcHIBH/TWpyws2cO9D2RM+lGYU9rJ8YMmyrJkbnmA4p0dK4Ujxqnt2IqXqv/2hJZ46KZjrg6kWWyW5vaAIu1Gne7TG8TWB5RNt43yVP0bL5m77msNwkp5NSbmhlEbGfEs4Z8CgYANmncMr9O2m1dC8kSQkUeyu1ZwSKM9uoKjma1iDmt2FphAOInSekdttSNX1hWF+QbwMW1NvFJgZacNLenWeyAKNk/L6G1BHXaQ9U+AMs3xIdH5Y9NL5SRuQjVAxX9ewVnVCer4PE8HoWe0vmT+02sxmRUn3B5LQCNm2VbY3almwQKBgG6T2ji4zgPsjzrXzeci9nyjbg0+sZmyjvRWFyzuN580DIHdNW+EWTuE3FqjrHkKwflKba4qmtLCKnMsM6yL7e9FinSligaKDrYNEhj/7yC4LEeXIklvLGJ+Qn7T3mhUedk1ca8qHFOOGqBiiJ9jHy//7erilkGER65d4bIkVsCs',

//     // 支付宝公钥
//     'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtyv8T39O+lI8M6NuQsWXMAa7cjxe7sUw4X+pbRZ9XeohpqSOlFOjQNN/H2s6vKDDKdG9iEKyn6owRJrIcxeM28IR8dKoD4x54q5cIsP8rvkShjVjjNLFs+knZikX2c8O9ixx5t3dYpVmNrBzDTWnpUaWIocjJIwOVu0qeUAl7eSyJJeI+wdZ1534fyKtFVLBXEdBIbUvY+jgOIcOZjTu1oSKRs5NI2xb47JmOow0GcYFE/u5gurWEBcJJ5sRvbC1Uh3CcdQDsWE0/fLudGetkhHY+HWJgZPOJBoIirKpLcJsoTNv0hfuHFPtjiWwytX++xULVIidtPu3rDCa+ojpfwIDAQAB',

//     // 商户app_id
//     'app_id' => '2016082100306410',

//     // 支付宝网关
//     'gateway' => 'https://openapi.alipaydev.com/gateway.do',

//     // 同步跳转
//     'return_url' => "http://ts.cool1024.com/web/alipay/order/home",

//     // 异步通知地址
//     'notify_url' => "http://ts.cool1024.com/web/alipay/order/notify_url",
// ];

return [
    // 商户公钥
    'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwz8j7JYQypj/pa3s8pJSWr0BACqy+1fFKiLa/EjD3acpSbCt7MhiF3tYdS0WLQp/eLRtKavs44Mg/k0gULStqO5OQ4U1Kz3+dGoTTI7T30ivhTUGRR8ktZEopiKe3fAOP/R2YZAWUsqlgqKecn3dV+KaSa0Q4Qr0ghbs6/217OKvaNjV76ad3Zdg5bpR69dFBcs3wKCdE2BQSkMC7JdFKlu8Lq1g3KUHIxCxJ9gEvoWa/CZD3KUn4tOIaJCkpYpaxqWboID18OhH9CY9yg8pGALZyBtveQUqfYG25g/jCp4FKryki8jaTm31KykXxnaGjkeqh03/TRU+k+XQxLCuCwIDAQAB',
    // 商户私钥
    'private_key' => 'MIIEpAIBAAKCAQEAwz8j7JYQypj/pa3s8pJSWr0BACqy+1fFKiLa/EjD3acpSbCt7MhiF3tYdS0WLQp/eLRtKavs44Mg/k0gULStqO5OQ4U1Kz3+dGoTTI7T30ivhTUGRR8ktZEopiKe3fAOP/R2YZAWUsqlgqKecn3dV+KaSa0Q4Qr0ghbs6/217OKvaNjV76ad3Zdg5bpR69dFBcs3wKCdE2BQSkMC7JdFKlu8Lq1g3KUHIxCxJ9gEvoWa/CZD3KUn4tOIaJCkpYpaxqWboID18OhH9CY9yg8pGALZyBtveQUqfYG25g/jCp4FKryki8jaTm31KykXxnaGjkeqh03/TRU+k+XQxLCuCwIDAQABAoIBAHO7dpDjdvqQbGEJ0n0KzLQqenBd3w/rO9y5InMOssMtNeUPTFkhgjuHCq0SV6XFJkAnOtnLpjRJ4wG6N+B+6L1M3KlbSWPjcHQS+HOV6fLdg+UCxD+usTTK4Xxw4xiNFfVE8/Lq1MCojCj+OcuF3fEdSfVIF8w9kSeXbUKJdg/+WWn8aMXtuRwoy/GOpuGpmvLFO6KXXdW5KbnTjhtmMlr/yQgu3XQo7w7KbkUq4vMdZYbitrfasDoxrH4A6zVEcgQFlGayZ2wRC/ZOHi8Mnz9aOkQHAit+AFNI2HMtCkEGmLCvP/0oPxxB1HfjcebSGAkp7outSybIosPtjxzxL/kCgYEA5vYxE1K6TfZTaOCuZ45hNpk1qyILymCOFg+8gPbKzUZn+E4kknq+tHLRLrUqxPqLISuhVudW2B4Pq2Izlp8W8Br6DrNpOTXXUQipjEc6n6Bhh+phhS6G+oKd9aFbcYlK1hPcXh/eObYalm4/y63Q+T/kpuwi1jRHsdz4smhHyk0CgYEA2GnCkdoHSoiJFxbKFDWP9spECsP6tSD46gQH/n9XnenG3GllMJsE1UN/Vud4MUzyx/gzzk36SFRO4xwI/55K08is2I6nQlNnNrOkrZNkXg2HW54ZMQSGt02KLe/FMTTgN6rpyVrNBFL3fqX6lYbCHtE7BYURI092H6q2geLB1bcCgYAXPsgJrdXaauRSNerXuVjHafwg0Thmkfk69zJ9uFkI+AEW2KaNp5TyDGxJSyqW0LCYEhAW8wmmjr/8+9E9Y52nZM+uExiQDX8yRLDr0W9xCW9Hgqt2AAzNwb+sLSVK9Ap4KV+QiZyQKmfapBX/perWazvUIQgQjwp4OEk/i4dOMQKBgQCznSuAk/2DLcjiIeb8WOwwfvV0He4hkMkmqMvdvOECTjnfS5ZRfgfH3Op+PUSzXlMOBwEwU+XLTDXhJq6NQVqHZcGXRuapFMHQU870xSUDLqVOM+xik4gf8LojoCIA0graCP9aQANyeE2zjpxop9zLaQpcbXGo5dV7ONua95G7BwKBgQCIhZ4kGs86CHVeWLrAhBOarlt6Xpn02oPZJgJaOy0KBa8D+hPSKQ6SdcWRCrT0bCQu6dTXcz6mBGZCucsH30jnJP1j3rE8GkYaAYrYcMJ33S6amxd6YMPpDgepR3EMulm/DTtfb7QzokgOvRVO/Z0STTlH1R2nEQgBrzhNUIcqSQ==',
    // 支付宝公钥
    //'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtyv8T39O+lI8M6NuQsWXMAa7cjxe7sUw4X+pbRZ9XeohpqSOlFOjQNN/H2s6vKDDKdG9iEKyn6owRJrIcxeM28IR8dKoD4x54q5cIsP8rvkShjVjjNLFs+knZikX2c8O9ixx5t3dYpVmNrBzDTWnpUaWIocjJIwOVu0qeUAl7eSyJJeI+wdZ1534fyKtFVLBXEdBIbUvY+jgOIcOZjTu1oSKRs5NI2xb47JmOow0GcYFE/u5gurWEBcJJ5sRvbC1Uh3CcdQDsWE0/fLudGetkhHY+HWJgZPOJBoIirKpLcJsoTNv0hfuHFPtjiWwytX++xULVIidtPu3rDCa+ojpfwIDAQAB',
    'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqoIVFTdzPHbUkSjW5UKhj0MyQmxVxlbFKWfhPqAQcvUdG2n6eUehz2vCNQqIhqj81/H6WQ3SHO8yfJp5nBibiynjgSArMxdurEhIcwEvVLMW/EXb2V3rPXdZiP6+odVOvaH5u+udVPvaAaRuVcC+qQW1ELgFE/SGeHrlaATBC4+rS/RXkKZFBsMwx8P6+sQSErzqKoaC68uks/tdEoZbacLkqG2xk0ccsh/V5g8HDhrfNUCxaUbRwpbtXii3mipcdlPUVc6PUcVSm6XQzDapku+hRMuQO3DwrsyPr6SPZjhJi2ccx6o1DWHUoG9GGz8Qyj5Lbmzk26azVeIcP5JUKQIDAQAB',
        

    // 商户app_id
    'app_id' => '2017091208684867',
    // 支付宝网关
    'gateway' => 'https://openapi.alipaydev.com/gateway.do',
    // 同步跳转
    'return_url' => "http://fyapi.anasit.com/web/alipay/order/home",
    // 异步通知地址
    'notify_url' => "http://fyapi.anasit.com/web/alipay/order/notify_url",
];