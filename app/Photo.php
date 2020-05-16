<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // ★

class Photo extends Model
{
    /** プライマリキーの型 */
    protected $keyType = 'string';

    /* JSONに含める属性 */
    // アクセサは定義しただけではモデルのJSON表現には現れない
    // ユーザー定義のアクセサをJSON表現に含めるには、明示的に$appendsプロパティに登録する必要がある
    protected $appends = [
        'url'
    ];

    /** JSONに含める属性 */
    protected $visible = [
    'id', 'owner', 'url', 'comments'
    ];

    // ページネーションの1ページあたりの表示数
    protected $perPage = 6; 

    /** IDの桁数 */
    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    /**
     * アクセサを定義
     * @return string
     */
    public function getUrlAttribute()
    {
        // クラウドストレージのurlメソッドは、S3上のファイルの公開URLを返す
        // .envで定義したAWS_URLと引数のファイル名を結合した値になる
        return Storage::cloud()->url($this->attributes['filename']);
    }
    
    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() // リレーション先のモデル名と別名にしている
    {
        // モデル名と関係のない名前をつけた場合は、引数は省略せずに記述する必要がある
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
}
