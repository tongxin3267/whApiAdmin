<?php
namespace app\common\utils;

use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;
use Firebase\JWT\JWT;
/*
 * token生成类
 */

class TokenUtils
{
    private static $key = 'wenhao';
    private static $iss = "http://www.wenhao.com";//签发者
    private static $aud = "http://wenhao.com";//接受者
    public static function create($id,$username,$time = 86400)
    {
        $token = [
            'id' =>$id,
            'username'=>$username,
            'exp'=>strtotime(date('Y-m-d H:i:s'))+$time, //过期时间
            'iat' => strtotime(date('Y-m-d H:i:s')), //创建时间
            'aud' => self::$aud,//接受者
            'iss' => self::$iss //签发者
        ];
        $tokenResult = JWT::encode($token,self::$key);
        return $tokenResult;
    }

    public static function decode($token)
    {
        try{
            $decodeResult = JWT::decode($token,self::$key,['HS256']);
            if(empty($decodeResult->id)){
                return ResultVo::error(ErrorCode::AUTH_FAILED,'登录失败');
            }
            return $decodeResult;
        }catch(\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            return Responses::arrays('签名错误',1);
        }catch(\Firebase\JWT\BeforeValidException $e) { //
            return Responses::arrays($e->getMessage(),1);
        }catch(\Firebase\JWT\ExpiredException $e) { // token过期
            return Responses::arrays('登录凭证失效',-1);
        }catch(Exception $e) { //其他错误
            return Responses::arrays($e->getMessage());
        }

    }
}