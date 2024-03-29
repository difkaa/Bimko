<?php

namespace App\Http\Controllers\User;

// use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Feed;
use App\Models\School;
use App\Models\User;
use App\Models\Catfeed;
use App\Models\FeedComment;
use App\Models\FeedNotification;
use Image;
use DateTime;
use Illuminate\Support\Facades\Auth;


class FeedController extends Controller
{
    public function __construct()
    {
        $date = new DateTime();
        $this->month = $date->format('m') - 1;
        $this->year = $date->format('Y');

        $this->maps = School::orderBy('name', 'asc')->get();
        $this->populars = Feed::orderBy('readby', 'desc')->where('status','=',1)->limit(4)->get();
        $this->latests = Feed::orderBy('updated_at', 'desc')->where('status','=',1)->limit(4)->get();


    }

    public function indexFeed()
    {
        $categories = Catfeed::get();
        $feeds = Feed::orderBy('updated_at','DESC')->where('status','=',1)->paginate(5);

        // dd($latests);

        return view('user.feed.index', [
            'feeds' => $feeds,
            'categories' => $categories,
            'populars' => $this->populars,
            'latests' => $this->latests,
            'maps' => $this->maps,
            'controller' => $this
        ]);
    }
    public function search(Request $request)
    {
        $categories = Catfeed::get();
        
        $input = strip_tags($request->cari);


        $feeds = new Feed;
        if(request()->has('cari')){
            $feeds = $feeds->where([
            ['name','LIKE',"%{$input}%"],
            ['status','=',1],
           ]);
       }

       $feeds = $feeds->paginate(4)->appends([
        'cari' =>  request('cari'),
    ]);
    
    return view('user.feed.search',[
        'feeds' => $feeds,
        'input' => $input,
        'populars' => $this->populars,
        'latests' => $this->latests,
        'feeds' => $feeds,
        'maps' => $this->maps,
        'categories' => $categories,
        'controller' => $this
        ]);
    }
    

    public function category($id)
    {   if($category_name = Catfeed::where('slug',$id)->count() == 0)
        {
            return view('errors.404');
        }
        $category_name = Catfeed::where('slug',$id)->select('name')->get();

        $feeds = Feed::whereHas('catfeeds', function($q) use ($id){
            $q->where('slug', $id);
        })->where('status','=',1)->latest()->paginate(5);


        $categories = Catfeed::get();

        return view('user.feed.category', [
            'category_name' => $category_name,
            'categories' => $categories,
            'populars' => $this->populars,
            'latests' => $this->latests,
            'feeds' => $feeds,
            'maps' => $this->maps,
            'controller' => $this
        ]);
    }

    public function create()
    {
        $catfeeds = Catfeed::get();
        return view('user.templates.panel.guest.create', [
            'catfeeds' => $catfeeds
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:feeds|max:32',
            'content' => 'required',
            'catfeed' => 'required',
            'file' => 'required|image|mimes:jpeg,jpg,png,gif|max:10024'
        ],[
            'name.unique'  => '*Judul telah digunakan, gunakan judul lain',
            'name.required'  => '*Judul artikel kosong',
            'name.max' => '*Maksimal karakter judul 32',
            'content.required' => '*konten artikel kosong',
            'catfeed.required' => '*kategori artikel kosong',
            'file.required' => '*file gambar kosong',
            'file.max' => 'ukuran file maksimal 10MB',
            'file.image' => 'file harus berupa gambar atau berformat (.jpeg, .bmp, .jpg, .png)',
            'file.mimes' => 'hanya untuk gambar berformat (.jpeg, .bmp, .jpg, .png)'
        ]);

        if($validate->fails())
        {
            return back()
                ->with('danger', 'Gagal menambah artikel')
                ->withInput($request->all())
                ->withErrors($validate);
        }
        
        
        $image = $request->file('file');
        $resize = Image::make($image->getRealPath())->resize(960,960, function($constraint){
            $constraint->aspectRatio();
        })->encode('jpg');
        // $hash = md5($resize->__toString());
        $hash = str_random(60);
        $path = "images/feeds/{$hash}.jpg";
        $resize->save(public_path($path));
        $file = "feeds/{$hash}.jpg";
        // $file = $request->file('file')->store('feeds');

        $date = new DateTime();
        $newDate = $date->format('dmy');

        $feed = Feed::create([
            'name' => $request->name,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'file' => $file,
            'slug' => str_slug($request->name." ".$newDate),
            'readby' => $request->readby,
        ]);
        // dd($date);

        $catfeeds = Catfeed::find($request->catfeed);
        $feed->catfeeds()->attach($catfeeds);
        return redirect()->route('guest.showfeed')->with('msg', 'Berhasil Menambahkan Artikel, Akan tampil setelah disetujui admin (1x24jam)');
    }

    public function info()
    {
        $categories = Catfeed::get();
        return view('user.feed.info', [
            'categories' => $categories,
            'populars' => $this->populars,
            'latests' => $this->latests,
            'maps' => $this->maps,
            'controller' => $this
        ]);
    }


    public function show(Feed $feed)
    {
        $categories = Catfeed::get();
        if($feed->status == 1){
            $feed->readby = $feed->readby + 1;
            $feed->save();
        }

        $relates = $feed->catfeeds;

        foreach($relates as $relate){
            $relatednews = Feed::whereHas('catfeeds', function($q) use ($relate){
                $q->where([
                    ['slug', $relate->slug]
                    ]);
            })->where([
                    ['slug','!=',$feed->slug],
                    ['status','=',1]
                ])->latest()->limit(2)->get();
        }
            $feedcomments = FeedComment::where('feedcomments.feed_id', $feed->id)
                                        ->where('parent_id','==', '0')
                                        ->orderBy('id', 'ASC')->get();

            $feedreplies = FeedComment::where('feedcomments.feed_id', $feed->id)
                                        ->where('parent_id','!=', '0')
                                        ->orderBy('id', 'ASC')->get();

            return view('user.feed.show',[
                'feed' => $feed,
                'relatednews' => $relatednews,
                'categories' => $categories,
                'feedcomments' => $feedcomments,
                'feedreplies' => $feedreplies,
                'populars' => $this->populars,
                'latests' => $this->latests,
                'maps' => $this->maps,
                'controller' => $this

            ]);

    }

    public function addComment(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'message' => 'required',
        ],[
            'message.required' => '*komentar tidak boleh kosong'
        ]);

        if($validate->fails())
        {
            return back()
                ->withInput($request->all())
                ->withErrors($validate);
        }

        $feedcomment = new FeedComment;
        $feedcomment->user_id = Auth::user()->id;
        $feedcomment->feed_id = $request->feed_id;
        $feedcomment->message = $request->message;
        $feedcomment->save();

        $feednotification = new FeedNotification;
        $feednotification->user_id = Auth::user()->id;
        $feednotification->feed_id = $request->feed_id;
        $feednotification->parent_id = $request->parent_id;
        $feednotification->type     = 1;
        $feednotification->parentuser_id = $request->parentuser_id;
        $feednotification->save();

        return back();

    }

    public function reply(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'message' => 'required',
        ],[
            'message.required' => '*balasan komentar tidak boleh kosong'
        ]);

        if($validate->fails())
        {
            return back()
                ->withInput($request->all())
                ->withErrors($validate);
        }

        $feedcomment = new FeedComment;
        $feedcomment->user_id = Auth::user()->id;
        $feedcomment->feed_id = $request->feed_id;
        $feedcomment->parent_id = $request->parent_id;
        $feedcomment->message = $request->message;
        $feedcomment->save();

        $feednotification = new FeedNotification;
        $feednotification->user_id = Auth::user()->id;
        $feednotification->feed_id = $request->feed_id;
        $feednotification->parent_id = $request->parent_id;
        $feednotification->parentuser_id = $request->parentuser_id;
        $feednotification->save();

        return back();

    }

    public function exceptionFeed()
    {
        $categories = Catfeed::get();
         return view('user.feed.show404',[
            'categories' => $categories,
            'populars' => $this->populars,
            'latests' => $this->latests,
            'maps' => $this->maps,
            'controller' => $this
        ]);
    }

    public function showCategory(Feed $feed)
    {

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function tanggal($tgl)//only date
    {
        $date = new DateTime($tgl);
        $month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $time = $date->format('d')." ".$month[$date->format('m') - 1]." ".$date->format('Y');
        echo '<li class="entry__meta-date"><i class="ui-date"></i>'.$time.'</li>';

    }

    public function fullTime($tgl)//using clock
    {
        $date = new DateTime($tgl);
        $month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $time = $date->format('d')." ".$month[$date->format('m') - 1]." ".$date->format('Y')." ".$date->format("H:i:s");
        echo '<li class="entry__meta-date"><i class="ui-date"></i>'.$time.'</li>';
    }

    public function fullTimeShow($tgl)//using clock
    {
        $date = new DateTime($tgl);
        $month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $time = $date->format('d')." ".$month[$date->format('m') - 1]." ".$date->format('Y')." ".$date->format("H:i:s");
        echo $time;
    }


}
