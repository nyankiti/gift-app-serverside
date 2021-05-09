<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Carbon\Carbon;
use Google\Cloud\Core\Timestamp;
use DateTime;
use Guzzle\Http\Exception\ClientErrorResponseException;


class FetchArticlesController extends Controller
{
    public function index()
    {
        // はてなブログはdatestring型を扱っているので、datastring型を返すCarbonインスタンスを現在時刻に使う
        $now = Carbon::now();

        // $client = new \GuzzleHttp\Client();

        // $response = $client->request('GET', 'http://seiproject.hateblo.jp/entry/2020/04/09/172716', [
        //     'http_errors' => false,
        // ]);
        // dd($response);

        // pageパラメーターは変化するので注意
        // GiftBlogPageList = [1588494655, 1583932861, 1554912303, 1522322442, 1505971397, 1499956565, 1496681211]

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://blog.hatena.ne.jp/seiproject/seiproject.hateblo.jp/atom/entry?page=1616849637', [
            'auth' => ['seiproject', 'tbevqkdqnc']
        ]);
        $xml = simplexml_load_string($res->getBody(),'SimpleXMLElement',LIBXML_NOCDATA);

        // json
        $json = json_encode($xml);

        // array
        $array = json_decode($json, true);

        // dd($array);


        // 保存する形式へdateの整形する
        $data = [];
        // dd($array['entry']);
        foreach ($array['entry'] as $key => $value){
            // 下書きの場合はcontinueする

            // statuscodeで下書きを判別する
            // dd($value['link'][1]['@attributes']['href']);
            $temp_res = $client->request('GET', $value['link'][1]['@attributes']['href'], [
                'http_errors' => false,
            ]);
            if($temp_res->getStatusCode() == 404){
                continue;
            }

            $data[$key]['id'] = $value['id'];
            $data[$key]['title'] = $value['title'];
            $data[$key]['author'] = $value['author'];
            $data[$key]['imageUrl'] = $this->extractImageSrc($value['content']);
            $data[$key]['html'] = $value['content'];
            $data[$key]['created_at'] = new Timestamp(new DateTime($value['published']));
            $data[$key]['updated_at'] = new Timestamp(new DateTime($value['updated']));
        }

        // dd($data);

        // firestoreへ保存
        foreach ($data as $value){
            $newsRef = app('firebase.firestore')->database()->collection('news')->document($value['id']);
            $newsRef->set([
                'title' => $value['title'],
                'author' => $value['author'],
                'imageUrl' => $value['imageUrl'],
                'html' => $value['html'],
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                ]);
        }

    }
    public function extractImageSrc($html)
    {
        $pattern = '/<img.*?src\s*=\s*[\"|\'](.*?)[\"|\'].*?>/i';
        preg_match($pattern, $html, $result);

        // dd($result);
        if(array_key_exists(1, $result)){
            return $result[1];
        }
        return $result;
    }
}
