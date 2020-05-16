<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Comment; // ★
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePhoto; // ★
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        // 認証が必要、indexメソッドは写真一覧表示なので認証ミドルウェアの適用対象から外す
        // exceptメソッドで外せる
        $this->middleware('auth')->except(['index', 'download', 'show']);
    }

    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
        // 投稿写真の拡張子を取得する
        $extension = $request->photo->extension();

        $photo = new Photo();

        // インスタンス生成時に割り振られたランダムなID値と
        // 本来の拡張子を組み合わせてファイル名とする
        $photo->filename = $photo->id . '.' . $extension;

        // S3にファイルを保存する
        // 第三引数の'public'はファイルを公開状態で保存するため
        Storage::cloud()
            ->putFileAs('', $request->photo, $photo->filename, 'public');

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($photo->filename);
            throw $exception;
        }

        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($photo, 201);
    }

    public function index()
    {   
        // withメソッドはリレーションを事前にロードしておくメソッド
        $photos = Photo::with(['owner'])
                // paginateメソッドはページ送り機能を実現する
                // JSONレスポンスでも示した総ページ数や現在のページといった情報が自動的に追加される
                // クエリパラメータ（/api/photos/?page=2）を取得するようなコードを記述しなくても
                // Laravel側で勝手にページを適用してくれる
                ->orderBy(Photo::CREATED_AT, 'desc')->paginate();
        return $photos;
    }

    /**
     * 写真ダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
        // 写真の存在チェック
        if (! Storage::cloud()->exists($photo->filename)) {
            abort(404);
        }

        $disposition = 'attachment; filename="' . $photo->filename . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => $disposition,
        ];

        return response(Storage::cloud()->get($photo->filename), 200, $headers);
    }

    // 写真詳細
    public function show(string $id) // 引数でパスパラメータIDを受け取っている
    {  
        $photo = Photo::where('id', $id)->with(['owner', 'comments.author'])->first();

        // 写真データが見つからなかった場合は404を返却
        return $photo ?? abort(404);
    }

    // コメント投稿
    public function addComment(Photo $photo, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $photo->comments()->save($comment);

        // authorリレーションをロードするためにコメントを取得しなおす
        $new_comment = Comment::where('id', $comment->id)->with('author')->first();

        return response($new_comment, 201);
    }
}