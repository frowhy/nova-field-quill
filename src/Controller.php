<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/7/21
 * Time: 下午4:49
 */

namespace Frowhy\NovaFieldQuill;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    public function upload(Request $request)
    {
        $filesystem_driver = config('nova-field-quill.filesystem_driver');
        $file_maxsize      = config('nova-field-quill.file_maxsize');
        $file_field        = 'img';

        if ($request->hasFile($file_field)) {

            $file     = $request->file($file_field);
            $data     = $request->all();
            $rules    = [
                $file_field => "max:{$file_maxsize}",
            ];
            $messages = [
                $file_field . '.max' => "文件过大, 文件大小不得超出 {$file_maxsize}KB",
            ];

            $validator = Validator($data, $rules, $messages);

            if ($validator->fails()) {
                return $this->response(0, $validator->errors()->first());
            } else {
                if ($file->isValid()) {
                    $disk     = \Storage::disk($filesystem_driver);
                    $ext      = $file->getClientOriginalExtension();
                    $realPath = $file->getRealPath();
                    $filename = md5(uniqid()) . '.' . $ext;
                    $bool     = $disk->put($filename, file_get_contents($realPath));

                    if ($bool) {
                        return $this->response(1, '', $filename, $disk->url($filename));
                    } else {
                        return $this->response(0, '上传失败');
                    }
                } else {
                    return $this->response(0, '文件错误');
                }
            }
        } else {
            return $this->response(0, '文件不存在');
        }
    }

    protected function response($uploaded, $error = '', $filename = '', $url = '')
    {
        return [
            "uploaded" => $uploaded,
            "fileName" => $filename,
            "url"      => $url,
            "error"    => [
                "message" => $error,
            ],
        ];
    }
}
