<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Userview;
use App\Models\Feed;
use App\Models\Post;
// use Egulias\EmailValidator\Warning\Comment;
use App\Models\Agenda;
use App\Models\DetailAgenda;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use App\Models\School;
use App\Models\FeedComment;
use App\Models\FeedNotification;
use App\Models\Question;
use App\Models\Answer;
use DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name', 'asc')->paginate(8);
        // dd($users);
        return view('admin.user.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'identity' => 'required|numeric',
            'grade' => 'required|numeric|min:10|max:12',
            'phone' => 'required|numeric|min:0|digits_between:10,15',
            // 'file' => ''
        ]);

        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request['password']),
            'identity' => $request->identity,
            'grade' => $request->grade,
            'phone' => $request->phone,
        ]);
        $data->assignRole('Guru');

        return redirect()->route('user.index')->with('success', 'User has been added');
    }

    public function show(User $user)
    {
        // dd($user);
        return view('admin.user.trash', [
           'user' =>  $user
        ]);
    }

    public function edit(User $user)
    {
        $schools = School::get();
        $roles = DB::table('roles')->where('name','!=','admin')->get();
        
        $checkrole1 = User::whereHas('roles',function($q){
                $q->where('name','guest');
            })->where('id',$user->id)->get();
        $checkrole2 = User::whereHas('roles',function($q){
                $q->where('name','Guru');
            })->where('id',$user->id)->get();
        $checkrole3 = User::whereHas('roles',function($q){
                $q->where('name','Murid');
            })->where('id',$user->id)->get();
        if(!$checkrole1->isEmpty())
        {
            $status = 'guest';
        }elseif(!$checkrole2->isEmpty())
        {
            $status = 'Guru';
        }else
        {
            $status = 'Murid';
        }
        return view('admin.user.edit',[
            'user' => $user,
            'schools' => $schools,
            'status' => $status,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $users = User::find($user->id);


        if($users->hasRole('Murid')){
            $this->validate($request,[
                'name' => 'required|regex:/^[\pL\s]+$/u',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'nis' => 'required|min:0|numeric|unique:users,nis,'.$user->id,
                'grade' => 'required|numeric|min:10|max:12',
                'phone' => 'required|numeric|min:0|digits_between:10,15',
                'school_id' => 'required',
                // 'file' => ''
            ]);
            $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'grade' => $request->grade,
            'phone' => $request->phone,
            'school_id' => $request->school_id,
        ]);
        $users->removeRole('Murid');
        $users->assignRole($request->status);
        return redirect()->route('user.murid')->with('info', 'User Murid berhasil di edit');

        }else if($users->hasRole('Guru')){
            $this->validate($request,[
                'name' => 'required|regex:/^[\pL\s]+$/u',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'nip' => 'required|min:0|numeric|digits:18|unique:users,nip,'.$user->id,
                'grade' => 'required|numeric|min:10|max:12',
                'phone' => 'required|numeric|min:0|digits_between:1,15',
                'school_id' => 'required',
                'gender' => 'required',

                // 'file' => ''
            ]);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'grade' => $request->grade,
                'phone' => $request->phone,
                'school_id' => $request->school_id,
                'gender' => $request->gender,

            ]);
            $users->removeRole('Guru');
            $users->assignRole($request->status);
        return redirect()->route('user.guru')->with('info', 'User Guru berhasil di edit');

        }else if($users->hasRole('guest')){
            $this->validate($request,[
                'name' => 'required|regex:/^[\pL\s]+$/u',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'phone' => 'required|numeric|min:0|digits_between:10,15',
                'agency' => 'required'
            ]);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'agency' => $request->agency,

            ]);
            $users->removeRole('guest');
            $users->assignRole($request->status);
        return redirect()->route('user.guest')->with('info', 'User Responder berhasil di edit');

        }

    }


    public function resetPassword(Request $request, User $user)
    {
        // $user = User::find($id);
        $user->update([
            'password' => Hash::make(12345678),
        ]);
        return back()->with('info', 'Password berhasil direset');
    }

    public function destroy(User $user)
    {
        // $user = User::find($id);
        $user->delete();
        $user->posts()->delete();
        $user->feeds()->delete();
        $user->comments()->delete();
        $user->answers()->delete();
        $user->questions()->delete();
        $user->agendas()->delete();
        $user->feedcomments()->delete();
        $user->feednotifications()->delete();


        // caused di page kosong yg sudah dihapus harusnya back to page awal/route view tsb
        return back()->with('danger', 'User telah dihapus sementara, silahkan cek menu trash ');


    }

    public function destroyPermanent($id)
    {
        $user = User::onlyTrashed()->where('id', $id);
        $user1 = User::withTrashed()->where('id', $id)->first();

        if($agenda = Agenda::withTrashed()->where('user_id', $id)->first() !=null )
        {
        $agenda = Agenda::withTrashed()->where('user_id', $id)->first();
            if($agenda->detailAgenda->file ){
                Storage::delete($agenda->detailAgenda->file);
                $agenda->detailAgenda->users()->detach();
                $agenda->detailAgenda->forceDelete();
            }
        }
        if($posts = Post::withTrashed()->where('user_id', $id)->get() != null)
        {
        $posts = Post::withTrashed()->where('user_id', $id)->get();
            foreach($posts as $post){
                Storage::delete($post->file_1);
                Storage::delete($post->file_2);
            }
        }

        if($feeds = Feed::withTrashed()->where('user_id', $id)->get() != null)
        {
        $feeds = Feed::withTrashed()->where('user_id', $id)->get();
            foreach($feeds as $feed){
                Storage::delete($feed->file);
            }
        }

        $user1->posts()->forceDelete();
        $user1->comments()->forceDelete();
        $user1->feeds()->forceDelete();
        $user1->feedcomments()->forceDelete();
        $user1->feednotifications()->forceDelete();
        $user1->agendas()->forceDelete();
        $user1->answers()->forceDelete();
        $user1->questions()->forceDelete();

        // $user1->detailAgendas()->forceDelete();

        $user->forceDelete();

        return back()->with('danger', 'User telah dihapus secara permanen');
    }

    public function viewTrash()
    {
        $users = User::onlyTrashed()->paginate(8);
        return view('admin.user.trash', [
            'users' => $users
        ]);

    }

    public function restoreAll()
    {
        $user = User::onlyTrashed();
        $user->restore();
        Post::withTrashed()->restore();
        Feed::withTrashed()->restore();
        FeedComment::withTrashed()->restore();
        FeedNotification::withTrashed()->restore();
        Comment::withTrashed()->restore();
        Agenda::withTrashed()->restore();
        DetailAgenda::withTrashed()->restore();
        Question::withTrashed()->restore();
        Answer::withTrashed()->restore();

        return back()->with('success', 'Semua data telah direstore');
    }

    public function restoreTrash($id)
    {
        $user = User::onlyTrashed()->where('id', $id);
        $user->restore();
        $feed = Feed::onlyTrashed()->where('user_id',$id);
        $feed->restore();

        $feedcomment = FeedComment::onlyTrashed()->where('user_id',$id);
        $feedcomment->restore();

        $feednotification = FeedNotification::onlyTrashed()->where('user_id',$id);
        $feednotification->restore();

        $comment = Comment::onlyTrashed()->where('user_id',$id);
        $comment->restore();

        $post = Post::onlyTrashed()->where('user_id',$id);
        $post->restore();

        $agenda = Agenda::onlyTrashed()->where('user_id',$id);
        $agenda->restore();

        $agenda = Answer::onlyTrashed()->where('user_id',$id);
        $agenda->restore();

        $agenda = Question::onlyTrashed()->where('user_id',$id);
        $agenda->restore();

        return back()->with('success', 'Data telah di restore');
    }

    public function trashShow($id)
    {
        $user = User::withTrashed()->where('id', $id)->get();
        return view('admin.user.trash',[
            'user' => $user
        ]);
    }
}
