<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Event;
use App\Models\Blog;

class EventController extends Controller
{

    public function lastEvents() {
        $events = Event::leftJoin('blogs', 'events.blog_id', '=', 'blogs.id')
            ->select('events.*', 'blogs.slug as blogSlug', 'blogs.title as blogTitle')
            ->orderBy('events.updated_at', 'desc')->take(10)->get();

        return response()->json(compact('events'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function allEvents() {
        $events = Event::leftJoin('blogs', 'events.blog_id', '=', 'blogs.id')
            ->select('events.*', 'blogs.slug as blogSlug', 'blogs.title as blogTitle')
            ->orderBy('events.created_at', 'desc')->get();

        return response()->json(compact('events'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function search(Request $request) {
        $searchTerm = $request->search;
        $search = Event::leftJoin('blogs', 'events.blog_id', '=', 'blogs.id')
            ->select('events.*', 'blogs.slug as blogSlug', 'blogs.title as blogTitle')
            ->where(function($query) use ($searchTerm) {
                $query->where('events.title', 'like', '%'.$searchTerm.'%')
                    ->orWhere('events.start_dtime', 'like', '%'.$searchTerm.'%')
                    ->orWhere('events.end_dtime', 'like', '%'.$searchTerm.'%')
                    ->orWhere('events.capacity', 'like', '%'.$searchTerm.'%');
            })
            ->orderBy('events.updated_at', 'desc')
            ->get();

        return response()->json(compact('search'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function addEvent(Request $request) {
        $event = new Event;
        $blog = Blog::find($request->input('blog_id'));
        $event->blog_id = $blog->id;
        $event->title = $request->input('title');
        $event->subtitle = $request->input('subtitle');
        $event->slug = $request->input('slug');
        // $event->banner_img = $request->input('banner_img');
        $event->content = $request->input('content');
        $event->start_dtime = $request->input('start_dtime');
        $event->end_dtime = $request->input('end_dtime');
        $event->ubication = $request->input('ubication');
        $event->section = $request->input('section');
        $event->capacity = $request->input('capacity');

        if ($request->hasFile('banner_img')) {
            // Verifica si 'profile_img' es un array y obtiene el primer archivo
            $banner_img_files = $request->file('banner_img');
            $banner_img_file = is_array($banner_img_files) ? $banner_img_files[0] : $banner_img_files;


            // Asegúrate de que 'profile_img' no esté vacío
            if ($banner_img_file) {
                $banner_img_name = $banner_img_file->getClientOriginalName();
                $reactPublicPath = env('IMG_LOCATION').'events/' . $blog->slug;

                // Crear la carpeta si no existe
                if (!File::exists($reactPublicPath)) {
                    File::makeDirectory($reactPublicPath, 0755, true, true);
                }

                // Mover el archivo a la carpeta de destino en React
                $banner_img_file->move($reactPublicPath, $banner_img_name);

                // Guardar el nombre de archivo en la cadena
                $event->banner_img = $blog->slug . '/' . $banner_img_name;
            }
        } else {
            $event->banner_img = $request->input('banner_img');
        }

        $event->save();

        return $event;
    }

    public function userEvents() {
        $user = auth()->user();

        foreach ($user->bloggers as $blog) {
            $events = $blog->events()->leftJoin('blogs', 'events.blog_id', '=', 'blogs.id')
            ->select('events.*', 'blogs.slug as blogSlug')->orderBy('events.updated_at', 'desc')->get();
        }

        return response()->json(compact('events'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function searchEvent(Request $request, $eventSlug) {
        $event = Event::where('slug', $eventSlug)->first();

        if ($event) {
            return $event;
        }

        return [];
    }
}
