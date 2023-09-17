<?php

use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

final class storePostImages extends Post
{
    public function storeImages($post, $request) {

        dd("images service");
        
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                $request->flash();
                return Redirect::back()
                    ->with('error', $errors);
            }
            if (extension_loaded('gd')) {
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //$request->file->storeAs('thumbnails', $file_name, 'public');
                $post->thumbnail = $file_name;
                $post->thumbnail_src = null;
            } else {
                $file_extension = $request->file->extension();
                $file_name = Str::slug($request->title) . '-' . time() . '.' . $file_extension;
                Storage::disk('public')->put('/thumbnails/' . $file_name, $request->file);
                $post->thumbnail = $file_name;
                $post->thumbnail_src = null;
            }
        } else {
            if ($request->thumbnail_src != null) {
                if (extension_loaded('gd')) {
                    $image_file_data = file_get_contents($request->thumbnail_src);
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                    $encoded = Image::make($image_file_data)->encode('webp', 100); //->resize(150, 212)
                    Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);

                    $post->thumbnail = $file_name;
                    $post->thumbnail_src = $request->thumbnail_src;
                } else {
                    $image_file_data = file_get_contents($request->thumbnail_src);
                    $extension = pathinfo($image_file_data, PATHINFO_EXTENSION);
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . $extension;
                    Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);

                    $post->thumbnail = $file_name;
                    $post->thumbnail_src = $request->thumbnail_src;
                }
            } else {
                $request->flash();
                return Redirect::back()->with('error', "Post not created, images not founds");
            }
        }
        if ($request->hasFile('banner')) {
            $validator = Validator::make($request->all(), [
                'banner' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                $request->flash();
                return Redirect::back()
                    ->with('error', $errors);
            }

            if (extension_loaded('gd')) {
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $post->banner = $file_name;
                $encoded = Image::make($request->banner)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
            } else {
                $extension = pathinfo($request->banner, PATHINFO_EXTENSION);
                $file_name = Str::slug($request->title) . '-' . time() . '.' . $extension;
                $post->banner = $file_name;
                Storage::disk('public')->put('/anime_banner/' . $file_name, $request->banner);
            }
        } else {
            if ($request->banner_src != null) {
                $banner_file_data = file_get_contents($request->banner_src);
                if (extension_loaded('gd')) {
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                    $encoded = Image::make($banner_file_data)->encode('webp', 100); //->resize(150, 212)
                    Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
                    $post->banner = $file_name;
                    $post->banner_src = $request->banner_src;
                } else {
                    $extension = pathinfo($banner_file_data, PATHINFO_EXTENSION);
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . $extension;
                    Storage::disk('public')->put('/anime_banner/' . $file_name, $banner_file_data);
                    $post->banner = $file_name;
                    $post->banner_src = $request->banner_src;
                }
            } else {
                $post->banner = null;
                $post->banner_src = null;
            }
        }
        return $post;
    }
}
